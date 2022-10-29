<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'currency' => 'COP',
            'document_type' => 'CC',
            'reference' => Str::substr(Str::uuid(), 1, 20),
            'document' => $this->faker->numerify('###########'),
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'city' => $this->faker->city(),
            'address' => $this->faker->address(),
        ];
    }
}
