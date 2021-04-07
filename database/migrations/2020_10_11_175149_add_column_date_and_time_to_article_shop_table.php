<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDateAndTimeToArticleShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_shops', function (Blueprint $table) {
            $table->date("date")->after("price_got");
            $table->time("time")->after("price_got");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_shops', function (Blueprint $table) {
            $table->dropColumn(["date", "time"]);
        });
    }
}
