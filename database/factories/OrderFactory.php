<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_number' => 'ORD-'.strtoupper(Str::random(10)),
            'user_id' => 1,
            'payment_status' => 'unpaid',
            'status' => 'new',
            'nom' => $this->faker->lastName(),
            'prenoms' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'email' =>$this->faker->email(),
        ];
    }
}
