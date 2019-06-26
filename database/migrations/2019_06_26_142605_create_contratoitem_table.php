<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratoitens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->integer('tipo_id');
            $table->integer('grupo_id');
            $table->integer('catmatseritem_id');
            $table->integer('quantidade');
            $table->decimal('valorunitario',17,2)->default(0);
            $table->decimal('valortotal',17,2)->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->foreign('tipo_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->foreign('grupo_id')->references('id')->on('catmatsergrupos')->onDelete('cascade');
            $table->foreign('catmatseritem_id')->references('id')->on('catmatseritens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratoitens');
    }
}
