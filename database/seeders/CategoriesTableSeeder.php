<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'Sample Category',
            'slug' => 'sample-category',
            'image' => 'path/to/image.jpg',
        ]);

        // Add more categories if needed
    }
}
