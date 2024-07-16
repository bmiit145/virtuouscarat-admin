<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\WpProduct;
use Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        // $this->validate($request,['category_id' => 'required|integer',
        // 'prod_name' => 'required|string|max:255',
        // 'description' => 'required|string',
        // 'short_desc' => 'nullable|string',
        // 'price' => 'nullable|numeric',
        // 'sale_price' => 'nullable|numeric',
        // 'sku' => 'nullable|string|max:255',
        // 'quantity' => 'nullable|integer',
        // 'IGI_certificate' => 'nullable|string|max:255',
        // 'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // 'gallery' => 'nullable|array',
        // 'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // 'attributes' => 'nullable|array']);




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
        // dd($product);
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
        $product=WpProduct::findOrFail($id);
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

                // check if there is an error
                if (is_array($response) && isset($response['error'])) {
                    return back()->with('error', 'Failed to send product to WooCommerce: ' . $response['error']);
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
        $product = WpProduct::findOrFail($id);
        $old_product = clone $product;
        $sku = $old_product->sku;

        // Update main photo
        if ($request->file('photo')) {
            $mainPhotoPath = $request->file('photo')->store('photos', 'public');
            $fullMainPhotoUrl = asset('storage/' . $mainPhotoPath);
            $product->main_photo = $fullMainPhotoUrl;
        }

        // Update photo gallery
        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $galleryImage) {
                $path = $galleryImage->store('photos', 'public');
                $fullUrl = asset('storage/' . $path);
                $galleryPaths[] = $fullUrl;
            }
            $product->photo_gallery = json_encode($galleryPaths);
        }

        // Update product fields
        $product->category_id = $request->category_id;
        $product->name = $request->prod_name;
        $product->description = $request->description;
        $product->short_description = $request->short_desc;
        $product->regular_price = $request->price;
        $product->sale_price = $request->sale_price;
        $product->sku = $request->sku;
        $product->stock_status = $request->productStatus;
        $product->igi_certificate = $request->IGI_certificate;
        $product->quantity = $request->quantity;
        $product->document_number = $request->document_number;

        // Update product attributes
        if ($request->has('attributes')) {
            $product->attributes()->delete();
            foreach ($request->input('attributes') as $name => $value) {
                $product->attributes()->create([
                    'name' => $name,
                    'value' => $value,
                ]);
            }
        }

        // Save the product
        $product->save();


        // Call the WooCommerce update function
         $wooResponse = WooCommerceProductController::editProductInWooCommerce($sku, $product);

        if (isset($wooResponse['error'])) {
            // Handle WooCommerce update error
            return redirect()->route('product.index')->with('error', 'Failed to update product in WooCommerce: ' . $wooResponse['error']);
        }

        return redirect()->route('product.index')->with('success', 'Product updated successfully.');
    }

    public function removeGalleryImage(Request $request)
    {
        // dd($request->all());
        $imageUrl = $request->imageUrl;

        // Logic to remove $imageUrl from $product->photo_gallery
        $product = WpProduct::find($request->id); // Adjust this to fetch your product

        $gallery = json_decode($product->photo_gallery);

        // Remove the image URL from the array
        $gallery = array_values(array_diff($gallery, [$imageUrl]));

        // Update the product's photo_gallery field
        $product->photo_gallery = json_encode($gallery);
        $product->save();

        return response()->json(['success' => true]);
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
        $wooCommerceResponse = WooCommerceProductController::deleteProductFromWooCommerce($product->sku);
        if($status){
            request()->session()->flash('success','Product deleted');
            return redirect()->route('product.index');
        }
        else{
            request()->session()->flash('error','Error while deleting product');
        }
        return redirect()->route('product.index');
    }


    public function  import(Request $request){
        $request->validate([
            'import_file' => 'required|mimes:csv,xlsx|max:2048', // Validate file type and size
        ]);

        // read and store data in database as field as name , vendor , quantity
        $file = $request->file('import_file');


        if ($file->getClientOriginalExtension() == 'xlsx' || $file->getClientOriginalExtension() == 'xls') {
            if ($file->getClientOriginalExtension() == 'xls') {
                $reader = IOFactory::createReader('Xls');
            } else {
                $reader = IOFactory::createReader('Xlsx');
            }
            $spreadsheet = $reader->load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            if (empty($rows)) {
                return redirect()->route('product.index')->with('error', 'No data found in the file.');
            }

            $headers = array_shift($rows);

            foreach ($rows as $row) {
                $data = array_combine($headers, $row);
                $productData = [
                    'name' => $data['RE FNO.'],
                    'vendor_id' => Auth::id(),
                    'description' => null,
                    'short_description' => null,
                    'regular_price' => null,
                    'sale_price' => $data['RAP'],
                    'sku' => null,
                    'stock_status' => 'in_stock',
                    'igi_certificate' => $data['CERTI LINK'],
                    'main_photo' => null,
                    'photo_gallery' => $data['360 VIDEO LINKS'],
                    'category_id' => null, // You need to determine the category ID based on your logic
                    'vendor_id' => null, // You need to determine the vendor ID based on your logic
                    'quantity' => $data['CTS'],
                    'document_number' => $data['REPORTNO'],
                ];

                // Create the product
                $product = WpProduct::create($productData);

                // Map data to product attributes
                $attributes = [
                    'LOC' => $data['LOC'],
                    'LAB' => $data['LAB'],
                    'SHAPE' => $data['SHAPE'],
                    'COLOR' => $data['COLOR'],
                    'CLARITY' => $data['CLARITY'],
                    'CUT' => $data['CUT'],
                    'POLISH' => $data['POLISH'],
                    'SYM' => $data['SYM'],
                    'FL' => $data['FL'],
                    'MEASUREMENT' => $data['MEASUREMENT'],
                    'TBL' => $data['TBL'],
                    'T.DEPTH' => $data['T.DEPTH'],
                    'TYPE' => $data['TYPE'],
                    'COMMENT' => $data['COMMENT'],
                ];

                foreach ($attributes as $name => $value) {
                    ProductAttribute::create([
                        'product_id' => $product->id,
                        'name' => $name,
                        'value' => $value,
                    ]);
                }
            }
        }
        elseif ($file->getClientOriginalExtension() == 'csv') {
            $rows = array_map('str_getcsv', file($file));
            $headers = array_shift($rows);

            dd($headers , $rows);
        }


        return redirect()->route('product.index')->with('success', 'Products imported successfully.');
    }
}
