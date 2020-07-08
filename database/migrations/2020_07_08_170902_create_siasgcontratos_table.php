<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiasgcontratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siasgcontratos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('compra_id')->nullable();
            $table->string('unidade');
            $table->integer('tipo_id');
            $table->string('numero');
            $table->string('ano');
            $table->string('codigo_interno')->nullable();
            $table->string('unidadesubrrogacao');
            $table->string('mensagem')->nullable();
            $table->string('situacao');
            $table->json('json')->nullable();
            $table->boolean('sisg')->default(true);
            $table->timestamps();

            $table->foreign('tipo_id')->references('id')->on('codigoitens')->onDelete('cascade');

            $table->unique(['unidade', 'tipo_id', 'numero', 'ano']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siasgcontratos');
    }
}
