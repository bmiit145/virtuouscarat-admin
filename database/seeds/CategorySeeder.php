<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {

//            $categoriesInUse = \App\Models\WpProduct::distinct()->pluck('category_id')->toArray();
//            Category::whereNotIn('id', $categoriesInUse)->each(function ($category) {
//                $category->delete();
//            });

        $categories = [
            ['id' => 133, 'name' => 'Uncategorized', 'status' => 'active'],
            ['id' => 103, 'name' => 'Asscher', 'status' => 'active'],
            ['id' => 99, 'name' => 'Cushion', 'status' => 'active'],
            ['id' => 100, 'name' => 'Emerald', 'status' => 'active'],
            ['id' => 69, 'name' => 'Heart', 'status' => 'active'],
//            ['id' => 190, 'name' => 'Loose Lab Grown Diamonds', 'status' => 'active'],
            ['id' => 102, 'name' => 'Marquise', 'status' => 'active'],
            ['id' => 71, 'name' => 'Oval', 'status' => 'active'],
            ['id' => 68, 'name' => 'Pear', 'status' => 'active'],
            ['id' => 101, 'name' => 'Princess', 'status' => 'active'],
//            ['id' => 175, 'name' => 'Quickship', 'status' => 'active'],
            ['id' => 104, 'name' => 'Radiant', 'status' => 'active'],
            ['id' => 15, 'name' => 'Round', 'status' => 'active'],
        ];


        foreach ($categories as $category) {
            $slug = \Str::slug($category['name']);
            $is_available = Category::where('slug', $slug)->first();

            if ($is_available) {
                // Update the existing category
                $is_available->update([
                    'wp_category_id' => $category['id'],
                    'title' => $category['name'],
                    'slug' => $slug,
                    'status' => $category['status'],
                ]);
            } else {
                // Create a new category
                Category::create([
                    'wp_category_id' => $category['id'],
                    'title' => $category['name'],
                    'slug' => $slug,
                    'status' => $category['status'],
                ]);
            }
        }
    }
}
