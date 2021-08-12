<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnClientIdToTableArticleShops extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_shops', function (Blueprint $table) {
            $table->integer('client_id')->default(0)->after('liaison_id');
            $table->boolean('dette')->defaul(0)->after('price_got');
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
            //
        });
    }
}
