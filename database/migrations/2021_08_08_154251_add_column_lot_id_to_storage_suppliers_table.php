<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLotIdToStorageSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('storage_suppliers', function (Blueprint $table) {
            $table->integer('lot_id')->default(0)->after('liaison_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('storage_suppliers', function (Blueprint $table) {
            $table->dropColumn('lot_id');
        });
    }
}
