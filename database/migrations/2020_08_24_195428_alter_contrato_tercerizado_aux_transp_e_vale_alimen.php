<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoTercerizadoAuxTranspEValeAlimen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratoterceirizados', function (Blueprint $table) {
            $table->decimal('aux_transporte')->default(0);
            $table->decimal('vale_alimentacao')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratoterceirizados', function ($table) {
            $table->dropColumn('aux_transporte');
            $table->dropColumn('vale_alimentacao');
        });

    }
}
