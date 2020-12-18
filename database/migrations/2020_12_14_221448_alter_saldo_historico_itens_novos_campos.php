<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSaldoHistoricoItensNovosCampos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saldohistoricoitens', function (Blueprint $table) {
            $table->integer('periodicidade')->nullable();
            $table->date('data_inicio')->nullable();
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
        Schema::table('saldohistoricoitens', function (Blueprint $table) {
            $table->dropColumn('periodicidade');
            $table->dropColumn('data_inicio');
            $table->dropColumn('numero_item_compra');
        });
    }
}
