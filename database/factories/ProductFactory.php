<?php

namespace Database\Factories;

use App\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'libelle' => $this->faker->title(),
            'description' => $this->faker->text(),
            'photo' => $this->faker->image(public_path('Products/'), 640,480,'IGP',false,true).';'.$this->faker->image(public_path('Products/'), 640,480,'IGP',false,true).';'.$this->faker->image(public_path('Products/'), 640,480,'IGP',false,true),
            'stock' => $this->faker->randomDigit(),
            'price' => $this->faker->randomDigit(),
            'parent_id' => $this->faker->randomDigit(),
            'product_type_id' =>ProductType::all()->random()->id,
        ];
    }
}
