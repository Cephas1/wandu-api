<?php

use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 1; $i <= 20; $i++){
            DB::table('colors')->insert([
                'name' => $faker->colorName
            ]);
        }
    }
}
