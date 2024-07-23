<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Category;

class WpProduct extends Model
{
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
    ];

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
        return WpProduct::with(['attributes',  'vendor' , 'category'])->orderBy('wp_product_id','desc')->orderBy('created_at', 'desc')->paginate(10);
    }


}
