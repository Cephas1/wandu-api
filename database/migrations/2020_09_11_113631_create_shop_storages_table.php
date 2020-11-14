<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_storages', function (Blueprint $table) {
            $table->id();
            $table->integer("article_id");
            $table->integer("shop_id");
            $table->integer("storage_id");
            $table->integer("liaison_id");
            $table->integer("user_id");
            $table->dateTime("date");
            $table->integer("quantity");
            $table->dateTime("deleted_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_storages');
    }
}