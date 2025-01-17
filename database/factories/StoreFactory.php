<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Store;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    protected $model = Store::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(2, true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(15),
            'logo_img' => $this->faker->imageUrl(300, 300),
            'covered_image' => $this->faker->imageUrl(600, 600),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
        ];
    }
}

