<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratofaturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratofaturas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->integer('tipolistafatura_id');
            $table->integer('justificativafatura_id')->nullable();
            $table->integer('documentosiafi_id')->nullable();
            $table->string('numero');
            $table->date('emissao');
            $table->date('vencimento')->nullable();
            $table->date('prazo')->nullable();
            $table->decimal('valor',17,2);
            $table->decimal('juros',17,2)->default(0);
            $table->decimal('multa',17,2)->default(0);
            $table->decimal('glosa',17,2)->default(0);
            $table->decimal('valorliquido',17,2);
            $table->string('processo');
            $table->date('protocolo')->nullable();
            $table->date('ateste')->nullable();
            $table->boolean('repactuacao')->default(0);
            $table->string('infcomplementar')->nullable();
            $table->string('mesref');
            $table->string('anoref');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->foreign('tipolistafatura_id')->references('id')->on('tipolistafatura')->onDelete('cascade');
            $table->foreign('justificativafatura_id')->references('id')->on('justificativafatura')->onDelete('cascade');
            $table->foreign('documentosiafi_id')->references('id')->on('documentosiafi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratofaturas');
    }
}
