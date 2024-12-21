<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\WpProduct;

class Category extends Model
{

    protected $fillable=['wp_category_id', 'title','slug','summary','photo','status','is_parent','parent_id','added_by'];

    public function parent_info(){
        return $this->hasOne('App\Models\Category','id','parent_id');
    }
    public static function getAllCategory(){
        return  Category::orderBy('id','DESC')->with('parent_info')->paginate(10);
    }

    public static function shiftChild($cat_id){
        return Category::whereIn('id',$cat_id)->update(['is_parent'=>1]);
    }
    public static function getChildByParentID($id){
        return Category::where('parent_id',$id)->orderBy('id','ASC')->pluck('title','id');
    }

    public function child_cat(){
        return $this->hasMany('App\Models\Category','parent_id','id')->where('status','active');
    }
    public static function getAllParentWithChild(){
        return Category::with('child_cat')->where('is_parent',1)->where('status','active')->orderBy('title','ASC')->get();
    }
    public function products(){
        // return $this->hasMany('App\Models\Product','cat_id','id')->where('status','active');
        return $this->hasMany(WpProduct::class, 'category_id');
    }
    public function sub_products(){
        return $this->hasMany('App\Models\Product','child_cat_id','id')->where('status','active');
    }
    public static function getProductByCat($slug){
        // dd($slug);
        return Category::with('products')->where('slug',$slug)->first();
        // return Product::where('cat_id',$id)->where('child_cat_id',null)->paginate(10);
    }
    public static function getProductBySubCat($slug){
        // return $slug;
        return Category::with('sub_products')->where('slug',$slug)->first();
    }
    public static function countActiveCategory(){
        $data=Category::where('status','active')->count();
        if($data){
            return $data;
        }
        return 0;
    }

    public static function getProductImageLink($category){
        $defaultImage = asset('storage/CategoryProductImage/default.jpeg');

         // array of image link
         $categoryImages = [
            'Uncategorized' => $defaultImage,
             'Ascher' => asset('storage/CategoryProductImage/Ascher.png'),
            'Asscher' => asset('storage/CategoryProductImage/Ascher.png'),
            'Cushion' => asset('storage/CategoryProductImage/Cushion.png'),
            'Emerald' => asset('storage/CategoryProductImage/Emerald.png'),
            'Heart' => asset('storage/CategoryProductImage/Heart.png'),
            'Marquise' => asset('storage/CategoryProductImage/Marquise.png'),
            'Oval' => asset('storage/CategoryProductImage/Oval.png'),
            'Pear' => asset('storage/CategoryProductImage/Pear.png'),
            'Princess' => asset('storage/CategoryProductImage/Princess.png'),
            'Radiant' => asset('storage/CategoryProductImage/Radiant.png'),
            'Round' => asset('storage/CategoryProductImage/Round.png'),
            'Trillion' => 'https://virtuouscarat.com/wp-content/uploads/2024/07/WhatsApp-Image-2024-07-24-at-9.32.44-AM-2.jpeg'
        ];

        $categoryName = $category->title;

        return isset($categoryImages[$categoryName]) ? $categoryImages[$categoryName] : $defaultImage;
    }
}
