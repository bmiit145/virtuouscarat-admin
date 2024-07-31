<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
use App\Jobs\ApproveProductJob;
use App\Jobs\ApproveProductsJob;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\WpProduct;
use Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    protected $defaultImage;
    protected $defaultImageGallery;
    protected $headerMapping;

    public function __construct(){
        $this->defaultImage = asset('storage/CategoryProductImage/default.jpeg');
        $this->defaultImageGallery = [
        ];

        $this->headerMapping = [
            'name' => [
                'header' => ['name'],
                'default' => null
            ],
            'description' => [
                'header' => ['DESCRIPTION'],
                'default' => 'No description available.'
            ],
            'short_description' => [
                'header' => ['SHORT DESCRIPTION'],
                'default' => 'No short description available.'
            ],
            'sku' => [
                'header' => ['sku' , 'SKU' , 'REPORTNO' , 'REPORT #' ,  'Certificate #'],
                'default' => null
            ],
            'igi_certificate' => [
                'header' => ['CERTI LINK'],
                'default' => null
            ],
            'category' => [
                'header' => ['SHAPE' , 'Shape' ],
                'default' => 'Uncategorized'
            ],
            'main_photo' => [
                'header' => ['Image Link' , 'Image' , 'main_photo' ],
                'default' => $this->defaultImage
            ],
            'photo_gallery' => [
                'header' => ['photo_gallery' , 'photo_gallery' , 'photo_gallery' ],
                'default' =>  json_encode($this->defaultImageGallery)
            ],
            'quantity' => [
                'header' => ['quantity'],
                'default' => 1
            ],
            'document_number' => [
                'header' => ['REPORTNO' , 'REPORT #' , 'RE FNO.' , 'RE FNO.Ψ' , 'Certificate #'],
                'default' => null
            ],
            'video_link' => [
                'header' => ['video_link' , '360 VIDEO LINKS' , 'Video Link'],
                'default' => null
            ],
            'location' => [
                'header' => ['LOC' , 'LOCATION' , 'Location' , 'City'],
                'default' => null
            ],
            'comment' => [
                'header' => ['COMMENT' , 'COMMENT Ψ' , ],
                'default' => null
            ],
            'CTS' => [
                'header' => ['CTS', 'CARAT' , 'CARAT WEIGHT' , 'Weight' , 'WEIGHT' , 'WEIGHT (CTS)'],
                'default' => 0
            ],
            'RAP' => [
                'header' => ['RAP' , 'RAP PRICE' , 'Price' , 'PRICE' , 'PRICE (RAP)'],
                'default' => 0
            ],
            'discount' => [
                'header' => ['discount' , 'DISCOUNT' , 'Discount Percent'],
                'default' => 0
            ],
        ];

    }

    protected  $attributeMapping = [
        'LOC' => ['location', 'LOC' , 'City'],
        'LAB' => ['lab', 'LAB' , 'Lab'],
        'SHAPE' => ['shape', 'SHAPE' , 'Shape'],
        'COLOR' => ['color', 'COLOR' , 'Color'],
        'CLARITY' => ['clarity', 'CLARITY' , 'Clarity'],
        'CUT' => ['cut', 'CUT' , 'Cut Grade'],
        'POLISH' => ['polish', 'POLISH' , 'Polish'],
        'SYM' => ['sym', 'SYM' , 'Symmetry'],
        'FL' => ['fl', 'FL' , 'Fluorescence Intensity'],
        'MEASUREMENT' => ['measurement', 'MEASUREMENT' , 'Measurements'],
        'TBL' => ['tbl', 'TBL' , 'Table Percent'],
        'T.DEPTH' => ['t_depth', 'T.DEPTH' ,'Depth Percent'],
        'TYPE' => ['type', 'TYPE' , 'Growth Type']
    ];

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

        $this->validate($request, [
            'category_id' => 'required|exists:categories,id',
            'prod_name' => 'required|string|max:255',
//            'price' => 'required|numeric',
//            'sale_price' => 'nullable|numeric|lte:price',
            'CTS' => 'required|numeric',
            'RAP' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'sku' => 'nullable|string|max:255|unique:wp_products,sku', // Replace 'products' with your actual table name
            'quantity' => 'nullable|integer|min:1',
            'IGI_certificate' => 'nullable|string|max:255',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'attributes' => 'nullable|array',
            'attributes.*' => 'required',
            'video_link' => 'nullable|string|max:255',
        ], [
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Selected category does not exist',
//            'prod_name.required' => 'Product name is required',
//            'price.numeric' => 'Price must be a number',
//            'sale_price.numeric' => 'Sale price must be a number',
//            'sale_price.lt' => 'Sale price must be less than regular price',
//            'sku.string' => 'SKU must be a string',
            'CTS.required' => 'Carat Weight is required',
            'CTS.numeric' => 'Carat Weight must be a number',
            'RAP.required' => 'Rate Per Carat is required',
            'RAP.numeric' => 'Rate Per Carat must be a number',
            'discount.numeric' => 'Discount must be a number',
            'sku.max' => 'SKU must not exceed 255 characters',
            'sku.unique' => 'SKU already exists',
            'quantity.integer' => 'Quantity must be an integer',
            'quantity.min' => 'Quantity must be greater than zero',
            'IGI_certificate.string' => 'IGI certificate must be a string',
            'photo.image' => 'Main photo must be an image',
            'photo.mimes' => 'Main photo must be a file of type: jpeg, png, jpg, gif, svg',
            'photo.max' => 'Main photo may not be greater than 2048 kilobytes',
            'gallery.array' => 'Gallery must be an array',
            'gallery.*.image' => 'Gallery images must be images',
            'gallery.*.mimes' => 'Gallery images must be files of type: jpeg, png, jpg, gif, svg',
            'gallery.*.max' => 'Gallery images may not be greater than 2048 kilobytes',
            'attributes.array' => 'Attributes must be an array',
        ]);

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

        $price = $request->CTS * $request->RAP;
        $discounted_price = $price - ($price * $request->discount / 100);

        $regular_price = $price + ($price * 10 / 100);
        $sale_price = $discounted_price + ($discounted_price * 10 / 100);

        $wpProduct = WpProduct::create([
            'vendor_id' => Auth::id(),
            'category_id' => $request->category_id,
            'name' => $request->prod_name,
            'description' => $request->description,
            'short_description' => $request->short_desc,
            'price' => $price,
            'discounted_price' => $discounted_price,
            'regular_price' => $regular_price,
            'sale_price' => $sale_price,
            'CTS' => $request->CTS,
            'RAP' => $request->RAP,
            'discount' => $request->discount,
            'sku' => $request->sku,
            'stock_status' => 1,
            'igi_certificate' => $request->IGI_certificate,
            'main_photo' => $fullMainPhotoUrl,
            'photo_gallery' => json_encode($galleryPaths),
            'quantity' => $request->quantity ?? 1,
            'document_number' => $request->document_number ?? 123,
            'video_link' => $request->video_link,
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

        return redirect()->route('product.index')->with('success', 'Products Created successfully.');
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

    //old code

//    public function Approvel(Request $request, $id) {
//        $aprovel = WpProduct::find($id);
//        $aprovel->is_approvel = $request->is_approvel;
//
//        $response =  WooCommerceProductController::sendDataToWooCommerce($aprovel);
//
//        // check if there is an error
//        if (is_array($response) && isset($response['error'])) {
//            return back()->with('error', 'Failed to send product to WooCommerce: ' . $response['error']);
//        }
//        $aprovel->save();
//
//
//        // send data to woo commerce for product creation
//        return back()->with('success', 'Product sent to WooCommerce successfully.');
//    }

    public function Approvel(Request $request, $id) {


        // Set a custom timeout for the database connection
        config(['database.connections.mysql.options' => [
            \PDO::ATTR_TIMEOUT => 30,
        ]]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Find the product and lock it for update
            $aprovel = WpProduct::where('id', $id)->lockForUpdate()->first();

            // Check if the product is already approved
            if ($aprovel->is_approvel) {
                // Rollback the transaction if already approved
                DB::rollBack();
                return back()->with('error', 'Product is already approved.');
            }

            // Update the approval status
            $aprovel->is_approvel = $request->is_approvel;

            // Send data to WooCommerce
            $response = WooCommerceProductController::sendDataToWooCommerce($aprovel);

            // Check if there is an error
            if (is_array($response) && isset($response['error'])) {
                // Rollback the transaction on error
                DB::rollBack();
                return back()->with('error', 'Failed to send product to WooCommerce: ' . $response['error']);
            }

            // Save the product
            $aprovel->save();

            // Commit the transaction
            DB::commit();

            // Return success response
            return back()->with('success', 'Product sent to WooCommerce successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction on exception
            DB::rollBack();
            \Log::error('Approval Error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred during approval: ' . $e->getMessage());
        }
    }


    //approveAll using job
    public function approveAll(Request $request){
        $productIds = WpProduct::where('is_approvel', 0)->pluck('id')->toArray();

        // dispatch the job
        foreach ($productIds as $productId) {
            ApproveProductJob::dispatch($productId);
        }
        if ($request->json()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'All products approval process has been started.');
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
        // return $request->all();
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

        $price = $request->CTS * $request->RAP;
        $discounted_price = $price - ($price * $request->discount / 100);
        $regular_price = $price + ($price * 10 / 100);
        $sale_price = $discounted_price + ($discounted_price * 10 / 100);

        // Update product fields
        $product->category_id = $request->category_id;
        $product->name = $request->prod_name;
        $product->description = $request->description;
        $product->short_description = $request->short_desc;
        $product->CTS = $request->CTS;
        $product->RAP = $request->RAP;
        $product->price = $price;
        $product->discount = $request->discount;
        $product->discounted_price = $discounted_price;
        $product->regular_price = $regular_price;
        $product->sale_price = $sale_price;
        $product->sku = $request->sku;
        $product->stock_status = 1;
        $product->igi_certificate = $request->IGI_certificate;
        $product->quantity = $request->quantity ?? 1;
        $product->document_number = $request->document_number ?? 123;
        $product->video_link = $request->video_link;

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


        $wooResponse = [];
        // Call the WooCommerce update function
        //  $wooResponse = WooCommerceProductController::editProductInWooCommerce($sku, $product);

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
            'import_file' => 'required|mimes:csv,xlsx|max:2048',
        ]);

        // read and store data in database as field as name , vendor , quantity
        $file = $request->file('import_file');

        set_time_limit(300);
        $extension = $file->getClientOriginalExtension();
        if (in_array($extension, ['xlsx', 'xls'])) {
            $reader = IOFactory::createReader($extension === 'xls' ? 'Xls' : 'Xlsx');
            $spreadsheet = $reader->load($file);
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        }
        elseif ($file->getClientOriginalExtension() == 'csv') {
            $rows = array_map('str_getcsv', file($file));
        }

        if (empty($rows)) {
            return redirect()->route('product.index')->with('error', 'No data found in the file.');
        }

        $headers = array_shift($rows);
        $headerMapping = $this->headerMapping;
        $mappedHeaders = $this->mapHeaders($headers, $this->headerMapping);
        $mappedAttributes = $this->mapAttributes($headers, $this->attributeMapping);
        $count = 0;

        foreach ($rows as $row) {
            $data = array_combine($headers, $row);
            $productData = [
                'name' => $data[$mappedHeaders['name'] ?? ''] ?? $headerMapping['name']['default'] ?? null,
                'vendor_id' => Auth::id(),
                'description' => $data[$mappedHeaders['description'] ?? ''] ?? $headerMapping['description']['default'] ?? null,
                'short_description' => $data[$mappedHeaders['short_description'] ?? ''] ?? $headerMapping['short_description']['default'] ?? null,
                'sku' => $data[$mappedHeaders['sku'] ?? ''] ?? $headerMapping['sku']['default'] ?? null,
                'stock_status' => 1,
                'igi_certificate' => $data[$mappedHeaders['igi_certificate'] ?? ''] ?? $headerMapping['igi_certificate']['default'] ?? null,
                'main_photo' => $data[$mappedHeaders['main_photo'] ?? ''] ?? $headerMapping['main_photo']['default'] ?? $this->defaultImage,
                'photo_gallery' => $data[$mappedHeaders['photo_gallery'] ?? ''] ?? json_encode($this->defaultImageGallery) ,
                'quantity' => $data[$mappedHeaders['quantity'] ?? ''] ?? $headerMapping['quantity']['default'] ?? 1,
                'document_number' => $data[$mappedHeaders['document_number'] ?? ''] ?? $headerMapping['document_number']['default'] ?? null,
                'category' => $data[$mappedHeaders['category'] ?? ''] ?? $headerMapping['category']['default'] ?? 'Uncategorized',
                'video_link' => $data[$mappedHeaders['video_link'] ?? ''] ?? $headerMapping['video_link']['default'] ?? null,
                'location' => $data[$mappedHeaders['location'] ?? ''] ?? $headerMapping['location']['default'] ?? null,
                'comment' => $data[$mappedHeaders['comment'] ?? ''] ?? $headerMapping['comment']['default'] ?? null,
                'CTS' => (float)($data[$mappedHeaders['CTS'] ?? ''] ?? $headerMapping['CTS']['default'] ?? 0),
                'RAP' => (float)($data[$mappedHeaders['RAP'] ?? ''] ?? $headerMapping['RAP']['default'] ?? 0),
//                    'discount' => $data[$mappedHeaders['discount'] ?? ''] ?? $headerMapping['discount']['default'] ?? 0,
                'discount' => abs((float)$data[$mappedHeaders['discount'] ?? ''] ?? $headerMapping['discount']['default'] ?? 0),
            ];

            if ($productData['sku'] == null || !is_numeric($productData['CTS']) || !is_numeric($productData['RAP']) || $productData['CTS'] == null || $productData['RAP'] == null) {
                continue;
            }

            $productData['price'] = $productData['CTS'] * $productData['RAP'];
            $productData['discounted_price'] = $productData['price'] - ($productData['price'] * $productData['discount'] / 100);

            // add 10 % commission
            $productData['regular_price'] = $productData['price'] + ($productData['price'] * 10 / 100);
            $productData['sale_price'] = $productData['discounted_price'] + ($productData['discounted_price'] * 10 / 100);

            //category_id
            $category = Category::where('title', $data[$mappedHeaders['category'] ?? ''] ?? $headerMapping['category']['default'] ?? 'Uncategorized')->first();
            $productData['category_id'] = $category->id ?? 15;
            $mainPhoto = $category ? Category::getProductImageLink($category) : $this->defaultImage;
            $productData['main_photo'] = $mainPhoto;

            if (empty($productData['name'])) {
//                $productData['name'] = $productData['CTS'] . ' ' . $productData['category'] . ' Shaped Loose Lab Grown Diamond';
                $productData['name'] = $productData['CTS'] . ' ct ' . $productData['category'] ;
            }

            // Create the product
            $product = WpProduct::create($productData);
            if ($product) {
                $count++;
                // Map and create product attributes
                foreach ($mappedAttributes as $dbField => $excelHeader) {
                    if (isset($data[$excelHeader])) {
                        ProductAttribute::create([
                            'product_id' => $product->id,
                            'name' => $dbField,
                            'value' => $data[$excelHeader],
                        ]);
                    }
                }
            }
        }

        return redirect()->route('product.index')->with('success! ', $count . ' Products imported successfully.');

    }


    protected function mapHeaders($headers, $headerMapping)
    {
        $mapped = [];

        foreach ($headerMapping as $dbField => $mapping) {
            foreach ($mapping['header'] as $possibleHeader) {
                if (in_array($possibleHeader, $headers)) {
                    $mapped[$dbField] = $possibleHeader;
                    break;
                }
            }
        }

        return $mapped;
    }

    protected function mapAttributes($headers, $attributeMapping)
    {
        $mapped = [];

        foreach ($attributeMapping as $dbField => $mapping) {
            foreach ($mapping as $possibleAttribute) {
                if (in_array($possibleAttribute, $headers)) {
                    $mapped[$dbField] = $possibleAttribute;
                    break;
                }
            }
        }

        return $mapped;
    }

    // clearAllProducts
    public function clearAllProducts()
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate all product attributes and products
        ProductAttribute::truncate();
        WpProduct::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return redirect()->route('product.index')->with('success', 'All products deleted successfully.');
    }
}
