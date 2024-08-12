<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $name = $this->faker->unique()->word;
        return [
            'name' => $name,
            'slug' => Str::slug($name . '-' . Str::random(6)), // Ensure slug is unique
            'short_description' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'regular_price' => $this->faker->randomFloat(2, 10, 1000),
            'SKU' => 'SKU-' . strtoupper(Str::random(8)),
            'stock_status' => 'instock',
            'quantity' => $this->faker->numberBetween(1, 100),
            'image' => $this->faker->imageUrl,
            'images' => json_encode([$this->faker->imageUrl]), // Generate a valid JSON structure
            'category_id' => \App\Models\Category::inRandomOrder()->first()->id,
            'brand_id' => \App\Models\Brand::inRandomOrder()->first()->id,
            'store_id' => \App\Models\Store::inRandomOrder()->first()->id,
        ];
    }
}
