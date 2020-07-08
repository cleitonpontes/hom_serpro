<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoTercerizadoTableDadosTelefone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratoterceirizados', function (Blueprint $table) {
            $table->string('telefone_fixo')->nullable(true);
            $table->string('telefone_celular')->nullable(true);
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
            $table->dropColumn('telefone_fixo');
            $table->dropColumn('telefone_celular');
        });
    }
}
