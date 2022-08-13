<?php

namespace Database\Factories;

use App\Constants\DocumentTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class SellerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'document_type' => $this->faker->randomElement(DocumentTypes::toArray()),
            'document' => $this->faker->unique()->numerify('##########'),
            'logo' => null,
        ];
    }
}
