<?php

use Illuminate\Database\Seeder;

class RubriqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rubriques')->insert([
            'name' => 'Alimentation',
            'default_mb'    => 25.0,
            'shop_id'   => 1
        ]);
        
        DB::table('rubriques')->insert([
            'name' => 'Boucherie',
            'default_mb'    => 30.0,
            'shop_id'   => 1
        ]);
        
        DB::table('rubriques')->insert([
            'name' => 'Frigo',
            'default_mb'    => 30.0,
            'shop_id'   => 1
        ]);
    }
}
