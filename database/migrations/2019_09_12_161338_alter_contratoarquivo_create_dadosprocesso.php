<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoarquivoCreateDadosprocesso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrato_arquivos', function (Blueprint $table) {
            $table->string('processo')->nullable();
            $table->string('sequencial_documento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contrato_arquivos', function ($table) {
            $table->dropColumn('processo');
            $table->dropColumn('sequencial_documento');
        });
    }
}
