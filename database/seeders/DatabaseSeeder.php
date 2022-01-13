<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // \App\Models\Product::factory(10)->create();
        // \App\Models\Order::factory(10)->create();
        \App\Models\ProductRate::factory(30)->create();
        // \App\Models\OrderProduct::factory(30)->create();
    }
}
