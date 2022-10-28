<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'api_token' => Str::random(60),
            'actif' => 1,
            'rule_id'   => 1,
        ]);

	DB::table('users')->insert([
            'name' => 'Cashier',
            'email' => 'cashier@gmail.com',
            'password' => Hash::make('password'),
            'api_token' => Str::random(60),
            'actif' => 1,
            'rule_id'   => 2,
        ]);

	DB::table('users')->insert([
            'name' => 'Storer',
            'email' => 'storer@gmail.com',
            'password' => Hash::make('password'),
            'api_token' => Str::random(60),
            'actif' => 1,
            'rule_id'   => 3,
        ]);

	DB::table('users')->insert([
            'name' => 'Techno',
            'email' => 'technician@gmail.com',
            'password' => Hash::make('password'),
            'api_token' => Str::random(60),
            'actif' => 1,
            'rule_id'   => 4,
        ]);
    }
}
