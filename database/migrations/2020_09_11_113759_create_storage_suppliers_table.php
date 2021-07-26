<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_suppliers', function (Blueprint $table) {
            $table->id();
            $table->integer("article_id");
            $table->integer("color_id");
            $table->integer("supplier_id");
            $table->integer("storage_id");
            $table->integer("liaison_id");
            $table->integer("user_id");
            $table->date("date");
            $table->time("time");
            $table->float("quantity", 8, 2);
            $table->integer("price_gave");
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
        Schema::dropIfExists('storage_suppliers');
    }
}
