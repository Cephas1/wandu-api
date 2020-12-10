<?php

use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for($i = 1; $i <= 5; $i++){
            DB::table('shops')->insert([
                'name' => $faker->name,
                'location'  => $faker->address,
                'phone' => $faker->e164PhoneNumber,
                'email' => $faker->unique()->safeEmail
            ]);
        }
    }
}
