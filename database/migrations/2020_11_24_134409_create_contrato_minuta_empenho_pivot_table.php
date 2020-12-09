<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoMinutaEmpenhoPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_minuta_empenho_pivot', function (Blueprint $table) {
            $table->integer('contrato_id')->nullable();
            $table->integer('minuta_empenho_id')->nullable();

            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->foreign('minuta_empenho_id')->references('id')->on('minutaempenhos')->onDelete('cascade');

            $table->unique(['contrato_id', 'minuta_empenho_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contrato_minuta_empenho_pivot');
    }
}
