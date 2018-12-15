<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoterceirizadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratoterceirizados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->string('cpf');
            $table->string('nome');
            $table->integer('oficio');
            $table->integer('jornada');
            $table->string('unidade');
            $table->decimal('salario');
            $table->decimal('custo');
            $table->integer('escolaridade');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->boolean('situacao');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratoterceirizados');
    }
}
