<?php

use Illuminate\Database\Seeder;

class StorageSeeder extends Seeder
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
            DB::table('storages')->insert([
                'name' => $faker->name,
                'location'  => $faker->address,
                'phone' => $faker->e164PhoneNumber,
                'email' => $faker->unique()->safeEmail
            ]);
        }
    }
}
