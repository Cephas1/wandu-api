<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiaisonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liaisons', function (Blueprint $table) {
            $table->id();
            $table->string("name", 20);
            $table->string("number", 20);
            $table->integer("shop_id")->nullable();
            $table->integer("storage_id")->nullable();
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
        Schema::dropIfExists('liaisons');
    }
}
