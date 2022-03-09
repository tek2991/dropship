<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'registration_number' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{2}[A-Z]{2}[0-9]{4}'),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
