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
            'name'   => 'Seau'
        ]);
        
        DB::table('categories')->insert([
            'name'   => 'Gobelet'
        ]);
        
        DB::table('categories')->insert([
            'name'   => 'Chaise'
        ]);
        
        DB::table('categories')->insert([
            'name'   => 'Pot'
        ]);
        
        DB::table('categories')->insert([
            'name'   => 'Verre'
        ]);
        
        DB::table('categories')->insert([
            'name'   => 'Assiette'
        ]);
        
        DB::table('categories')->insert([
            'name'   => 'Culliere'
        ]);
        
        DB::table('categories')->insert([
            'name'   => 'Fourchette'
        ]);
        
        DB::table('categories')->insert([
            'name'   => 'Table'
        ]);
        
        DB::table('categories')->insert([
            'name'   => 'Vase'
        ]);
    }
}
