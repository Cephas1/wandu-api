<?php

use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rules')->insert([
            'name' => 'Administrateur',
            'code'  => 'Admin'
        ]);
        DB::table('rules')->insert([
            'name' => 'Caissier',
            'code'  => 'CA'
        ]);
        DB::table('rules')->insert([
            'name' => 'Superviseur',
            'code'  => 'SUP'
        ]);
        DB::table('rules')->insert([
            'name' => 'Technicien',
            'code'  => 'Tech'
        ]);
    }
}
