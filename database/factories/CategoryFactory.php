<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->firstName();
        return [
            'name' => $name,
            'code' => $this->faker->unique()->text(5),
            'slug' => Str::slug($name),
            'enabled_at' => now(),
        ];
    }
}
