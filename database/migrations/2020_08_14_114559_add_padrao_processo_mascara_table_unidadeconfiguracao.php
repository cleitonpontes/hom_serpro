<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPadraoProcessoMascaraTableUnidadeconfiguracao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unidadeconfiguracao', function (Blueprint $table) {
            $table->string('padrao_processo_mascara')->nullable()->after('unidade_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unidadeconfiguracao', function (Blueprint $table) {
            $table->dropColumn('padrao_processo_mascara');
        });
    }
}
