<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UserSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(ArticleSeeder::class);
        $this->call(RuleSeeder::class);
        $this->call(ColorSeeder::class);
        $this->call(StorageSeeder::class);
        $this->call(ShopSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(TypeNotificationSeeder::class);
    }
}
