<?php

use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker\Factory::create();

        for ($i = 1 ;$i <= 30; $i++){            
            DB::table('categories')->insert([
                'name'   => $faker->name
            ]);
        }
    }
}
