<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'item_id' => Item::factory()->create()->getKey(),
            'ip' => $this->faker->ipv4(),
            'browser' => 'Chrome',
        ];
    }
}
