<?php

namespace Database\Seeders;

use App\Lib\Helper;
use Illuminate\Database\Seeder;
use App\Category;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategoriesTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = File::get("database/data/categories.json");
        $categories = json_decode($categories);

        foreach ($categories as $category) {
            Category::create([
                'name'      => $category->Category,
                'image_url' => asset('images/categories').'/'. Helper::removeSpecialChFromString($category->Category) .'.png',
                'tariff'    => $category->Tariff
            ]);
        }
    }
}
