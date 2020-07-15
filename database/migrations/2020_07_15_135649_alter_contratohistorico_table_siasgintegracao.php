<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratohistoricoTableSiasgintegracao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratohistorico', function (Blueprint $table) {
            $table->integer('unidadeorigem_id')->nullable();

            $table->foreign('unidadeorigem_id')->references('id')->on('unidades')->onDelete('cascade');
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
            $table->dropColumn(['unidadeorigem_id']);
        });
    }
}
