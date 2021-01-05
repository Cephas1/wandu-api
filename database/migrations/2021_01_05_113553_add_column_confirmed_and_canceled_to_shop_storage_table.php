<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnConfirmedAndCanceledToShopStorageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_storages', function (Blueprint $table) {
            $table->boolean('confirmed')->default(0)->after('user_id');
            $table->boolean('conceled')->default(0)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_storages', function (Blueprint $table) {
            $table->dropColumn(['confirmed', 'canceled']);
        });
    }
}
