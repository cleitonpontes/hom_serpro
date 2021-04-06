<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDevolveMinutaSiasgAddColumnJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devolve_minuta_siasg', function (Blueprint $table) {
            $table->json('json_enviado')->nullable(); //gravar retorno do WS
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devolve_minuta_siasg', function (Blueprint $table) {
            $table->dropColumn('json_enviado');
        });
    }
}
