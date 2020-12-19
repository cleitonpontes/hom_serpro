<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCamposValorContratohistorico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratohistorico', function (Blueprint $table) {
            $table->decimal('valor_inicial',19,4)->default(0)->change();
            $table->decimal('valor_global',19,4)->default(0)->change();
            $table->decimal('valor_parcela',19,4)->default(0)->change();
            $table->decimal('valor_acumulado',19,4)->default(0)->change();
            $table->decimal('novo_valor_global',19,4)->default(0)->change();
            $table->decimal('novo_valor_parcela',19,4)->default(0)->change();
            $table->decimal('retroativo_valor',19,4)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratohistorico', function (Blueprint $table) {
            $table->decimal('valor_inicial',17,2)->default(0)->change();
            $table->decimal('valor_global',17,2)->default(0)->change();
            $table->decimal('valor_parcela',17,2)->default(0)->change();
            $table->decimal('valor_acumulado',17,2)->default(0)->change();
            $table->decimal('novo_valor_global',17,2)->default(0)->change();
            $table->decimal('novo_valor_parcela',17,2)->default(0)->change();
            $table->decimal('retroativo_valor',17,2)->default(0)->change();
        });
    }
}
