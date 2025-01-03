<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\SoftDeletes;

class WpProduct extends Model
{
    use SoftDeletes;

    protected $table = 'wp_products';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'name',
        'wp_product_id',
        'description',
        'short_description',
        'regular_price',
        'sale_price',
        'sku',
        'stock_status',
        'igi_certificate',
        'main_photo',
        'photo_gallery',
        'category_id',
        'vendor_id',
        'quantity',
        'document_number',
        'CTS',
        'RAP',
        'price',
        'discounted_price',
        'discount',
        'video_link',
        'location',
        'comment',
        'is_processing',
        'is_approvel'
    ];


    public static function countActiveProduct()
    {
        $data = WpProduct::where('is_approvel', 1)->count();
        if ($data) {
            return $data;
        }
        return 0;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    /**
     * Get the attributes for the product.
     */
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public  function orderProduct(){
        return $this->hasMany(WpOrderProduct::class, 'product_id', 'wp_product_id');
    }
    public static function getAllProduct(){
        return WpProduct::with(['attributes',  'vendor' , 'category'])->orderBy('created_at', 'desc')->orderBy('wp_product_id','desc')->get();
    }
}
