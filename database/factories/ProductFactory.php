<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'margin' => $this->faker->numberBetween(1, 100),
            'shipping_cost' => $this->faker->numberBetween(1, 100),
        ];
    }
}
