<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRepactuacoesCreateSalarioAtual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repactuacoes', function (Blueprint $table) {
            $table->text('salarios_atuais')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('repactuacoes', function (Blueprint $table) {
            $table->dropColumn('salarios_atuais');
        });
    }
}
