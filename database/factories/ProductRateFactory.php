<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductRateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'rate' => $this->faker->randomElement([1,2,3,4,5]),
            'user_id' => 1,
            'product_id' => Product::all()->random()->id,
        ];
    }
}
