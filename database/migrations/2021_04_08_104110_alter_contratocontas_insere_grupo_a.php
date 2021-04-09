<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratocontasInsereGrupoA extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratocontas', function (Blueprint $table) {
            $table->float('percentual_grupo_a_13_ferias')->default(0);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratocontas', function (Blueprint $table) {
            $table->dropColumn('percentual_grupo_a_13_ferias');
        });
    }
}
