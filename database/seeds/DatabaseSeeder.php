<?php

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
        // $this->call(UserSeeder::class);
        DB::table('users')->insert([
            'name' => 'Shahrukh',
            'email' => 'a@a.com',
            'password' => Hash::make('12345678'),
            'phone' => '030423232323',
            'type' => 'superadmin',
        ]);
        
        // units
        DB::table('units')->insert([
            'name' => 'litre',
            'placeholder' => 'ayy',
            'slug' => 'lt',
        ]);

        // items
        DB::table('items')->insert([
            'name' => 'Milk',
            'unit_id' => 1,
            'has_measurement' => 1,
        ]);

        DB::table('items')->insert([
            'name' => 'Meat',
            'has_measurement' => 0,
            'predefined_size' => 'small,medium,large',
        ]);
        
        // baskets
        DB::table('baskets')->insert([
            'name' => 'Dinner',
            'user_id' => 1,
        ]);

        DB::table('baskets')->insert([
            'name' => 'Breakfast',
            'user_id' => 1,
        ]);

        // DB::table('basket_items')->insert([
        //     'name' => 'Breakfast',
        // ]);
        
        // orders
        DB::table('orders')->insert([
            'user_id' => 1,
            'total' => '1000',
        ]);

        // order items
        DB::table('order_items')->insert([
            "order_id" => "1",
            "item_id" => "1",
            "quantity" => "1",
            "price" => "100",
        ]);

        DB::table('order_items')->insert([
            "order_id" => "1",
            "item_id" => "2",
            "quantity" => "1",
            "price" => "100",
        ]);

    }
}
