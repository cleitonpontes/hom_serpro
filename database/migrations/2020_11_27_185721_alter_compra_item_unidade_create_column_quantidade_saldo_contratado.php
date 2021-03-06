<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompraItemUnidadeCreateColumnQuantidadeSaldoContratado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compra_item_unidade', function (Blueprint $table) {
            $table->decimal('quantidade_saldo_contratado',17,4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compra_item_unidade', function (Blueprint $table) {
            $table->dropColumn('quantidade_saldo_contratado');
        });
    }
}
