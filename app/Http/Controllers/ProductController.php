<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\WpProduct;
use Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=WpProduct::getAllProduct();
        // return $products;
        return view('backend.product.index')->with('products',$products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brand=Brand::get();
        $category=Category::where('is_parent',1)->get();
        // return $category;
        return view('backend.product.create')->with('categories',$category)->with('brands', $brand);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */



     public function store(Request $request){
        // dd($request->all());
        $this->validate($request,['category_id' => 'required|integer',
        'prod_name' => 'required|string|max:255',
        'description' => 'required|string',
        'short_desc' => 'nullable|string',
        'price' => 'nullable|numeric',
        'sale_price' => 'nullable|numeric',
        'sku' => 'nullable|string|max:255',
        'quantity' => 'nullable|integer',
        'IGI_certificate' => 'nullable|string|max:255',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'gallery' => 'nullable|array',
        'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'attributes' => 'nullable|array']);
        if ($request->hasFile('photo')) {
            $mainPhotoPath = $request->file('photo')->store('photos', 'public');
            $fullMainPhotoUrl = asset('storage/' . $mainPhotoPath);
        } else {
            $fullMainPhotoUrl = null;
        }
        $galleryPaths = [];
    if ($request->hasFile('gallery')) {

        foreach ($request->file('gallery') as $galleryImage) {
            // Store the image in the 'public' disk under the 'photos' directory
            $path = $galleryImage->store('photos', 'public');

            // Get the full URL to the stored image
            $fullUrl = asset('storage/' . $path);

            // Store the full URL in the array
            $galleryPaths[] = $fullUrl;
        }
    }

    $wpProduct = WpProduct::create([
        'vendor_id' => Auth::id(),
        'category_id' => $request->category_id,
        'name' => $request->prod_name,
        'description' => $request->description,
        'short_description' => $request->short_desc,
        'regular_price' => $request->price,
        'sale_price' => $request->sale_price,
        'sku' => $request->sku,
        'stock_status' => $request->productStatus,
        'igi_certificate' => $request->IGI_certificate,
        'main_photo' => $fullMainPhotoUrl,
        'photo_gallery' => json_encode($galleryPaths),
        'quantity' => $request->quantity,
        'document_number' => $request->document_number,
    ]);

     // Add attributes if any
     if ($request->has('attributes')) {
        foreach ($request->input('attributes') as $name => $value) {
            $wpProduct->attributes()->create([
                'name' => $name,
                'value' => $value,
            ]);
        }
    }
    return redirect('admin/product')->with('success', 'Product created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brand=Brand::get();
        $product=Product::findOrFail($id);
        $category=Category::where('is_parent',1)->get();
        $items=Product::where('id',$id)->get();
        // return $items;
        return view('backend.product.edit')->with('product',$product)
                    ->with('brands',$brand)
                    ->with('categories',$category)->with('items',$items);
    }


    public function Approvel($id) {
        $aprovel = WpProduct::find($id);

        if ($aprovel) { // Check if the product exists
            if ($aprovel->is_approvel == 0) {
                $aprovel->is_approvel = 1;
                $response =  WooCommerceProductController::sendDataToWooCommerce($aprovel);

                if($response['error']){
                    return back()->with('error', $response['error']);
                }
            } else {
                $aprovel->is_approvel = 0;
            }

            $aprovel->save(); // Save the changes to the database
        }

        // send data to woo commerce for product creation
        return back();
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product=Product::findOrFail($id);
        $this->validate($request,[
            'title'=>'string|required',
            'summary'=>'string|required',
            'description'=>'string|nullable',
            'photo'=>'string|required',
            'size'=>'nullable',
            'stock'=>"required|numeric",
            'cat_id'=>'required|exists:categories,id',
            'child_cat_id'=>'nullable|exists:categories,id',
            'is_featured'=>'sometimes|in:1',
            'brand_id'=>'nullable|exists:brands,id',
            'status'=>'required|in:active,inactive',
            'condition'=>'required|in:default,new,hot',
            'price'=>'required|numeric',
            'discount'=>'nullable|numeric'
        ]);

        $data=$request->all();
        $data['is_featured']=$request->input('is_featured',0);
        $size=$request->input('size');
        if($size){
            $data['size']=implode(',',$size);
        }
        else{
            $data['size']='';
        }
        // return $data;
        $status=$product->fill($data)->save();
        if($status){
            request()->session()->flash('success','Product updated');
        }
        else{
            request()->session()->flash('error','Please try again!!');
        }
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product=WpProduct::findOrFail($id);

        $mainPhoto=$product->main_photo;
        $gallery=json_decode($product->photo_gallery,true);

        if ($mainPhoto) {
            $mainPhotoPath = parse_url($mainPhoto, PHP_URL_PATH); // Extract path from URL
            Storage::delete('public' . Str::after($mainPhotoPath, 'storage')); // Adjust path as needed
        }

        if ($gallery) {
            foreach ($gallery as $photo) {
                $photoPath = parse_url($photo, PHP_URL_PATH); // Extract path from URL
                Storage::delete('public' . Str::after($photoPath, 'storage')); // Adjust path as needed
            }
        }

        $status=$product->delete();
        if($status){
            request()->session()->flash('success','Product deleted');
        }
        else{
            request()->session()->flash('error','Error while deleting product');
        }
        return redirect()->route('product.index');
    }
}
