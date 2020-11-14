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
        DB::table('categories')->insert([
            'name' => 'Seau'
        ]);
        DB::table('categories')->insert([
            'name' => 'Chaise'
        ]);
        DB::table('categories')->insert([
            'name' => 'Pot'
        ]);
    }
}
