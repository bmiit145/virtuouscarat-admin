<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->categoryInsert();
         $this->call(UsersTableSeeder::class);
         $this->call(SettingTableSeeder::class);
//         $this->call(CouponSeeder::class);
    }

    public function categoryInsert(){
        $categories = [
            ['id' => 15, 'name' => 'Uncategorized', 'status' => 'active'],
            ['id' => 154, 'name' => 'Asscher', 'status' => 'active'],
            ['id' => 148, 'name' => 'Cushion', 'status' => 'active'],
            ['id' => 150, 'name' => 'Emerald', 'status' => 'active'],
            ['id' => 152, 'name' => 'Heart', 'status' => 'active'],
            ['id' => 190, 'name' => 'Loose Lab Grown Diamonds', 'status' => 'active'],
            ['id' => 153, 'name' => 'Marquise', 'status' => 'active'],
            ['id' => 147, 'name' => 'Oval', 'status' => 'active'],
            ['id' => 149, 'name' => 'Pear', 'status' => 'active'],
            ['id' => 151, 'name' => 'Princess', 'status' => 'active'],
            ['id' => 175, 'name' => 'Quickship', 'status' => 'active'],
            ['id' => 155, 'name' => 'Radiant', 'status' => 'active'],
            ['id' => 15, 'name' => 'Round', 'status' => 'active'],
        ];

        // check if products table is not empty
//        if(\App\Models\WpProduct::count() == 0){
//        Category::truncate();
//        }else{
            $categoriesInUse = \App\Models\WpProduct::distinct()->pluck('category_id')->toArray();
            Category::whereNotIn('id', $categoriesInUse)->each(function ($category) {
                $category->delete();
            });
//        }

        $categories = [
            ['id' => 15, 'name' => 'Uncategorized', 'status' => 'active'],
            ['id' => 154, 'name' => 'Asscher', 'status' => 'active'],
            ['id' => 148, 'name' => 'Cushion', 'status' => 'active'],
            ['id' => 150, 'name' => 'Emerald', 'status' => 'active'],
            ['id' => 152, 'name' => 'Heart', 'status' => 'active'],
            ['id' => 190, 'name' => 'Loose Lab Grown Diamonds', 'status' => 'active'],
            ['id' => 153, 'name' => 'Marquise', 'status' => 'active'],
            ['id' => 147, 'name' => 'Oval', 'status' => 'active'],
            ['id' => 149, 'name' => 'Pear', 'status' => 'active'],
            ['id' => 151, 'name' => 'Princess', 'status' => 'active'],
            ['id' => 175, 'name' => 'Quickship', 'status' => 'active'],
            ['id' => 155, 'name' => 'Radiant', 'status' => 'active'],
            ['id' => 146, 'name' => 'Round', 'status' => 'active'],
        ];

        foreach ($categories as $category) {
            $slug = \Str::slug($category['name']);
            $is_available = Category::where('slug', $slug)->first();

            if ($is_available) {
                // Update the existing category
                $is_available->update([
                    'id' => $category['id'],
                    'title' => $category['name'],
                    'slug' => $slug,
                    'status' => $category['status'],
                ]);
            } else {
                // Create a new category
                Category::create([
                    'id' => $category['id'],
                    'title' => $category['name'],
                    'slug' => $slug,
                    'status' => $category['status'],
                ]);
            }
        }
    }
}
