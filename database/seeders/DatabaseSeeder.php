<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Store;
use App\Models\Brand;
use App\Models\Cart;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Category::factory(6)->create();
        Brand::factory(6)->create();
        Store::factory(6)->create();

        Product::factory(24)->create()->each(function ($product) {
            $categories = Category::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $product->categories()->attach($categories);
        });
        Cart::factory(6)->create();

    }
}

