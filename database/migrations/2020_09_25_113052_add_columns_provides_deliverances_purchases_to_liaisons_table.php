<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsProvidesDeliverancesPurchasesToLiaisonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liaisons', function (Blueprint $table) {
            $table->integer("provides")->default(0)->after("supplier_id");
            $table->integer("deliverances")->default(0)->after("provides");
            $table->integer("purchases")->default(0)->after("deliverances");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('liaisons', function (Blueprint $table) {
            $table->dropColumn(["provides", "deliverances", "purchases"]);
        });
    }
}
