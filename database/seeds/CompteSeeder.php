<?php

use Illuminate\Database\Seeder;

class CompteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comptes')->insert([
            'name' => 'Ets KOME',
            'email'  => 'kome@congo.cg',
            'siege' => 'Pointe-Noire',
            'adresse'   => 'Fond tie-tie derriere la gare',
            'tel'   => '00242064306060',
            'created_at'    => now(),
            'updated_at'    => now()
        ]);
    }
}
