<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImagesFactory extends Factory
{
    public function definition(): array
    {
        return [
            'url' => $this->faker->imageUrl(),
            'item_id' => Item::factory()->create()->getKey(),
        ];
    }
}
