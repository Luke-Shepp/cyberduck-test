<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    public function definition()
    {
        return [
            'quantity' => $this->faker->numberBetween(1, 100),
            'unit_cost' => $this->faker->randomFloat(2),
            'selling_price' => $this->faker->randomFloat(2),
        ];
    }
}
