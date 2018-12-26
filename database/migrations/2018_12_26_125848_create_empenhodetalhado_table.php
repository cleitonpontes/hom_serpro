<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpenhodetalhadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empenhodetalhado', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empenho_id');
            $table->integer('naturezasubitem_id');
            $table->decimal('empaliquidar',17,2)->nullable(); //6.2.2.9.2.01.01
            $table->decimal('empemliquidacao',17,2)->nullable(); //6.2.2.9.2.01.02
            $table->decimal('empliquidado',17,2)->nullable(); //6.2.2.9.2.01.03
            $table->decimal('emppago',17,2)->nullable(); //6.2.2.9.2.01.04
            $table->decimal('empaliqrpnp',17,2)->nullable(); //6.2.2.9.2.01.05
            $table->decimal('empemliqrpnp',17,2)->nullable(); //6.2.2.9.2.01.06
            $table->decimal('emprpp',17,2)->nullable(); //6.2.2.9.2.01.07
            $table->decimal('rpnpaliquidar',17,2)->nullable(); //6.3.1.1.0.00.00
            $table->decimal('rpnpaliquidaremliquidacao',17,2)->nullable(); //6.3.1.2.0.00.00
            $table->decimal('rpnpliquidado',17,2)->nullable(); //6.3.1.3.0.00.00
            $table->decimal('rpnppago',17,2)->nullable(); //6.3.1.4.0.00.00
            $table->decimal('rpnpaliquidarbloq',17,2)->nullable(); //6.3.1.5.1.00.00
            $table->decimal('rpnpaliquidaremliquidbloq',17,2)->nullable(); //6.3.1.5.2.00.00
            $table->decimal('rpnpcancelado',17,2)->nullable(); //6.3.1.9.1.00.00
            $table->decimal('rpnpoutrocancelamento',17,2)->nullable(); //6.3.1.9.8.00.00
            $table->decimal('rpnpemliqoutrocancelamento',17,2)->nullable(); //6.3.1.9.9.00.00
            $table->decimal('rppliquidado',17,2)->nullable(); //6.3.2.1.0.00.00
            $table->decimal('rpppago',17,2)->nullable(); //6.3.2.2.0.00.00
            $table->decimal('rppcancelado',17,2)->nullable(); //6.3.2.9.1.01.00
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('empenho_id')->references('id')->on('empenhos')->onDelete('cascade');
            $table->foreign('naturezasubitem_id')->references('id')->on('naturezasubitem')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empenhodetalhado');
    }
}
