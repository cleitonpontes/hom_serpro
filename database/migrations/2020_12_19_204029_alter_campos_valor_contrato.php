<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCamposValorContrato extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->decimal('valor_inicial',19,4)->change();
            $table->decimal('valor_global',19,4)->change();
            $table->decimal('valor_parcela',19,4)->change();
            $table->decimal('valor_acumulado',19,4)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->decimal('valor_inicial',17,2)->change();
            $table->decimal('valor_global',17,2)->change();
            $table->decimal('valor_parcela',17,2)->change();
            $table->decimal('valor_acumulado',17,2)->change();
        });
    }
}
