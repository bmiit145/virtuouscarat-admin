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
        $categories = [
            ['id' => 15, 'name' => 'Uncategorized'],
            ['id' => 154, 'name' => 'Asscher'],
            ['id' => 148, 'name' => 'Cushion'],
            ['id' => 150, 'name' => 'Emerald'],
            ['id' => 152, 'name' => 'Heart'],
            ['id' => 190, 'name' => 'Loose Lab Grown Diamonds'],
            ['id' => 153, 'name' => 'Marquise'],
            ['id' => 147, 'name' => 'Oval'],
            ['id' => 149, 'name' => 'Pear'],
            ['id' => 151, 'name' => 'Princess'],
            ['id' => 175, 'name' => 'Quickship'],
            ['id' => 155, 'name' => 'Radiant'],
            ['id' => 146, 'name' => 'Round'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'title' => $category['name'],
                'slug' => \Str::slug($category['name']),
                'summary' => '',
                'photo' => '',
                'status' => 'active',
                'is_parent' => true,
                'parent_id' => null,
                'added_by' => 1,
            ]);
        }
    }
}
