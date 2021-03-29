<?php

use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $faker = Faker\Factory::create();
        $price = rand(1, 20) * 100;

        for ($i = 1 ;$i <= 100; $i++){            
            DB::table('articles')->insert([
                'name'   => $faker->name,
                'description'   => $faker->paragraph,
                'price_1'       => $price,
                'price_2'   => $price + 300,
                'price_3'   => $price + 500,
                'price_4'   => $price + 1000,
                'category_id'   => rand(1, 10)
            ]);
        }
    }
}
