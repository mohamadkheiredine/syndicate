<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'phone' => $this->faker->phoneNumber(),
            'code' => $this->faker->countryCode(),
            'name' => $this->faker->country(),
            'currency' => $this->faker->currencyCode(),
            'publish' => $this->faker->boolean(),
        ];
    }
}
