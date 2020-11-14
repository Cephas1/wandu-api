<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_shops', function (Blueprint $table) {
            $table->id();
            $table->integer("article_id");
            $table->integer("color_id")->default("1");
            $table->integer("shop_id");
            $table->integer("liaison_id");
            $table->integer("user_id");
            $table->dateTime("date");
            $table->integer("quantity");
            $table->integer("price_got");
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
        Schema::dropIfExists('article_shops');
    }
}
