<?php

use Illuminate\Database\Seeder;

class TypeNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('type_notifications')->insert([
            'name' => 'Approvisionnement boutique',
        ]);

        DB::table('type_notifications')->insert([
            'name' => 'Seuil produit',
        ]);
    }
}
