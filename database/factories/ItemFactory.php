<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->firstName();
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'brand' => $this->faker->company(),
            'tumpnail' => $this->faker->imageUrl(),
            'city' => $this->faker->city(),
            'stock' => $this->faker->numberBetween(1, 100),
            'currency' => $this->faker->currencyCode(),
            'price' => $this->faker->numberBetween(50000, 10000000),
            'discount' => 0,
            'description' => $this->faker->text(230),
            'seller_id' => Seller::factory()->create()->getKey(),
            'category_id' => Category::factory()->create()->getKey(),
            'enabled_at' => now(),
        ];
    }
}
