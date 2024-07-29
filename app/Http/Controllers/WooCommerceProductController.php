<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateWooCommerceProduct;
use App\Models\WpProduct;
use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WooCommerceProductController extends Controller
{
//    protected  $woocommerce;
    protected static $woocommerce;

    public function __construct(Client $woocommerce)
    {
//        $this->woocommerce = $woocommerce;
        self::$woocommerce = $woocommerce;
    }


    protected static function getExistingAttributes() {
        $woocommerce = self::$woocommerce ?? app(Client::class);
        try {
            return collect($woocommerce->get('products/attributes'));
        } catch (\Exception $e) {
            Log::error('WooCommerce API Error: ' . $e->getMessage());
            return collect([]);
        }
    }

    protected static function createAttribute($attributeName) {
        $woocommerce = self::$woocommerce ?? app(Client::class);
        $slug = strtolower(str_replace(' ', '_', $attributeName));

        // Ensure slug is not a reserved term
        if (in_array($slug, ['type', 'product', 'category'])) {
            $slug = 'custom_' . $slug;
        }

        $data = [
            'name' => $attributeName,
            'slug' => $slug,
            'type' => 'select', // Ensure 'select' is used for attributes with options
            'order' => 0,
        ];

        try {
            return $woocommerce->post('products/attributes', $data);
        } catch (\Exception $e) {
            \Log::error('WooCommerce API Error: ' . $e->getMessage());
            return null;
        }
    }

    protected static function addOptionsToAttribute($attributeId, $options) {
        $woocommerce = self::$woocommerce ?? app(Client::class);

//        $existingTerms = $woocommerce->get("products/attributes/$attributeId/terms");
//        $existingTerms = collect($existingTerms);
//        $existingTermNames = $existingTerms->pluck('name')->toArray();

        $existingTermNames = collect($woocommerce->get("products/attributes/$attributeId/terms"))->pluck('name')->toArray();


        foreach ($options as $option) {
            $option = trim($option);
            // Check if the term already exists
            if (in_array($option, $existingTermNames)) {
                Log::info("Term '{$option}' already exists for attribute ID {$attributeId}. Skipping...");
                continue;
            }
            // Check if only one option is provided
            $formattedOptions = ['name' => $option];

            // Log the data being sent
            Log::info('Adding options to attribute:', ['attributeId' => $attributeId, 'data' => $formattedOptions]);
            try {
                // Use JSON encoding to ensure proper format
                return $woocommerce->post("products/attributes/$attributeId/terms", $formattedOptions);
            } catch (\Exception $e) {
                Log::error('WooCommerce API Error: ' . $e->getMessage());
                return null;
            }
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'regular_price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'sku' => 'required|string|max:255',
            'stock_status' => 'required|string|in:instock,outofstock,onbackorder',
            'igi_certificate' => 'nullable|string',
            'main_photo' => 'required|url',
            'photo_gallery' => 'nullable|array',
            'photo_gallery.*' => 'url',
            'category_id' => 'required|integer',
            'vendor_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        $productData = [
            'name' => $data['name'],
            'type' => 'simple',
            'regular_price' => $data['regular_price'],
            'sale_price' => $data['sale_price'],
            'description' => $data['description'],
            'short_description' => $data['short_description'],
            'sku' => $data['sku'],
            'stock_status' => $data['stock_status'],
            'categories' => [
                ['id' => $data['category_id']]
            ],
            'images' => array_merge(
                [['src' => $data['main_photo']]] ,
                array_map(function($photo){
                    return ['src' => $photo];
                }, $data['photo_gallery'] ?? [])
            ),
            'meta_data' => [
                ['key' => 'igi_certificate', 'value' => $data['igi_certificate']],
                ['key' => 'vendor_id', 'value' => $data['vendor_id']]
            ],
            'stock_quantity' => $data['quantity']
        ];

        $response = $this->woocommerce->post('products', $productData);

        return response()->json($response);
    }


//    public static function sendDataToWooCommerce(WpProduct $product) {
//        $data = [
//            'name' => $product->name,
//            'type' => 'simple',
//            'regular_price' => (string) $product->regular_price,
//            'sale_price' => isset($product->sale_price) ? (string) $product->sale_price : '',
//            'description' => $product->description,
//            'short_description' => $product->short_description,
//            'sku' => $product->sku,
//            'stock_status' => $product->stock_status === 1 ? 'instock' : ($product->stock_status === 0 ? 'outofstock' : 'onbackorder'),
//            'categories' => [
//                ['id' => $product->category_id]
//            ],
//            'images' => array_merge(
//                [['src' => $product->main_photo]],
//                array_map(function($photo) {
//                    return ['src' => $photo];
//                }, json_decode($product->photo_gallery) ?? [])
//            ),
//            'meta_data' => [
//                ['key' => 'igi_certificate', 'value' => $product->igi_certificate],
//                ['key' => 'vendor_id', 'value' => $product->vendor_id]
//            ],
//            'stock_quantity' => $product->quantity,
//
//            // attributes
//            'attributes' => $product->attributes->map(function($attribute) {
//                return [
//                    'name' => $attribute->name,
//                    'options' => [$attribute->value],
//                    'position' => 0,  // Adjust position as needed
//                    'visible' => true,  // Adjust visibility as needed
//                    'variation' => false,  // Adjust variation as needed
//                ];
//            })->toArray(),
//        ];
//
//        if (!self::$woocommerce) {
//            self::$woocommerce = app(Client::class);
//        }
//
//        try {
//            $response = self::$woocommerce->post('products', $data);
//
//            // error handle for error from woocommerce
//            if (is_object($response) && property_exists($response, 'error')) {
//                return ['error' => $response->error];
//            }
//
//            if (is_array($response) && isset($response['error'])) {
//                return ['error' => $response['error']];
//            }
//
//            if (is_array($response) || (is_object($response) && property_exists($response, 'id'))) {
//                if (isset($response->id)) {
//                    $product->wp_product_id = $response->id;
//                    $product->save();
//                }
//                return $response;
//            } else {
//                // Handle unexpected response format
//                return ['error' => 'Unexpected response format from WooCommerce API'];
//            }
//            return $response;
//        } catch (\Exception $e) {
//            // Handle exception or log error message
//            \Log::error('WooCommerce API Error: ' . $e->getMessage());
//            return ['error' => $e->getMessage()];
//            return ['error' => "Something Wents Wrong! Product not created"];
//        }
//    }

    public static function sendDataToWooCommerce(WpProduct $product) {
        $existingAttributes = self::getExistingAttributes();
        $attributeMap = [];

        $attributes = $product->attributes->map(function ($attribute) use ($existingAttributes, &$attributeMap) {
            $formattedName = ucwords(strtolower($attribute->name));
            $existing = $existingAttributes->firstWhere('name', $formattedName);

            if (!$existing) {
                // Create a new global attribute if it does not exist
                $newAttribute = self::createAttribute($formattedName);
                $attributeId = $newAttribute->id ?? null;
                $slug = $newAttribute->slug ?? strtolower(str_replace(' ', '_', $attribute->name));
            }
            else
            {
                $slug = $existing->slug;
                $attributeId = $existing->id;
                // Add options to existing attribute
                $options = [$attribute->value];
                $updateResponse = self::addOptionsToAttribute($attributeId, $options);
                if ($updateResponse === null) {
                    Log::error('Failed to add options for attribute: ' . $formattedName);
                }
            }

            // Map attribute ID for later use
            $attributeMap[$formattedName] = $attributeId;

            return [
                'id' => $attributeId, // Ensure this is set
//                'name' => $formattedName,
//                'slug' => $slug,
                'position' => 0,
                'visible' => true,
                'variation' => false,
                'options' => [$attribute->value],
            ];
        })->toArray();

        // Prepare the product data
        $data = [
            'name' => $product->name,
            'type' => 'simple',
            'regular_price' => (string) $product->regular_price,
            'sale_price' => (string) $product->sale_price ?? '',
            'description' => $product->description,
            'short_description' => $product->short_description,
            'sku' => $product->sku,
            'stock_status' => ['instock', 'outofstock', 'onbackorder'][($product->stock_status ?? 2) - 1],
            'categories' => [['id' => $product->category_id]],
            'images' => array_merge([['src' => $product->main_photo]], array_map(fn($photo) => ['src' => $photo], json_decode($product->photo_gallery) ?? [])),
            'meta_data' => [['key' => 'igi_certificate', 'value' => $product->igi_certificate], ['key' => 'vendor_id', 'value' => $product->vendor_id]],
            'stock_quantity' => $product->quantity,
            'attributes' => $attributes,
        ];


        self::$woocommerce = self::$woocommerce ?? app(Client::class);

        try {
            $response = self::$woocommerce->post('products', $data);
            if (isset($response->error)) {
                Log::error('WooCommerce API Error: ' . $response->error);
                return ['error' => $response->error];
            }
            if (isset($response->id)) {
                $product->wp_product_id = $response->id;
                $product->save();
            }
            return $response;
        } catch (\Exception $e) {
            Log::error('WooCommerce API Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }


    // function to delete product from woocommerce by sku
    public static function deleteProductFromWooCommerce($sku) {
        if (!self::$woocommerce) {
            self::$woocommerce = app(Client::class);
        }

        try {
            $response = self::$woocommerce->get('products', ['sku' => $sku]);
            if (count($response) > 0) {
                $productId = $response[0]->id;
                $response = self::$woocommerce->delete('products/' . $productId, ['force' => true]);
                return $response;
            } else {
                return ['error' => 'Product not found'];
            }
        } catch (\Exception $e) {
            // Handle exception or log error message
            error_log('WooCommerce API Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }


    public static function editProductInWooCommerce($sku, WpProduct $product) {
        $data = [
            'name' => $product->name,
            'type' => 'simple',
            'regular_price' => (string) $product->regular_price,
            'sale_price' => isset($product->sale_price) ? (string) $product->sale_price : '',
            'description' => $product->description,
            'short_description' => $product->short_description,
            'sku' => $product->sku,
            'stock_status' => $product->stock_status == 1 ? 'instock' : ($product->stock_status == 0 ? 'outofstock' : 'onbackorder'),
            'categories' => [
                ['id' => $product->category_id]
            ],
            'images' => array_merge(
                [['src' => $product->main_photo]],
                array_map(function($photo) {
                    return ['src' => $photo];
                }, json_decode($product->photo_gallery) ?? [])
            ),
            'meta_data' => [
                ['key' => 'igi_certificate', 'value' => $product->igi_certificate],
                ['key' => 'vendor_id', 'value' => $product->vendor_id]
            ],
            'stock_quantity' => $product->quantity,

            // attributes
            'attributes' => $product->attributes->map(function($attribute) {
                return [
                    'name' => ucwords(strtolower($attribute->name)),
                    'slug' => strtolower(str_replace(' ', '_', $attribute->name)),
                    'position' => 0,  // Adjust position as needed
                    'visible' => true,  // Adjust visibility as needed
                    'variation' => false,  // Adjust variation as needed
                    'options' => [$attribute->value],
                ];
            })->toArray(),
        ];

        if (!self::$woocommerce) {
            self::$woocommerce = app(Client::class);
        }

        try {
//             Retrieve the product by SKU
            // check env variable UPDATE_PRODUCT_METHOD is set to JOB or not by case insensitive
            if (strtolower(env('WOOCOMMERCE_UPDATE_METHOD')) == 'job') {
                UpdateWooCommerceProduct::dispatch($sku, $data);
                return ['success' => 'Product update job has been dispatched.'];
            }

            $product = self::$woocommerce->get('products', ['sku' => $sku]);
            \Log::info("Product" , $product);
            if (count($product) > 0) {
                $productId = $product[0]->id;
                // Update the product details by ittercting data and update

                    $response = self::$woocommerce->put('products/' . $productId, $data , ['force' => true]);

                    \Log::info("Response");
                return ['success' => 'Product updated successfully.'];
            } else {
                return ['error' => 'Product not found'];
            }

            return ['success' => 'Process done'];

        } catch (\Exception $e) {
            // Handle exception or log error message
            \Log::error('WooCommerce API Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

}
