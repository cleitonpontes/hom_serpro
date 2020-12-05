<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoitensCreateColumnNumeoItemCompra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratoitens', function (Blueprint $table) {
            $table->string('numero_item_compra')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratoitens', function (Blueprint $table) {
            $table->dropColumn('numero_item_compra');
        });
    }
}
