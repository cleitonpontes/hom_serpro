<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgaosubcategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orgaosubcategorias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('orgao_id');
            $table->integer('categoria_id');
            $table->string('descricao');
            $table->boolean('situacao')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('orgao_id')->references('id')->on('orgaos')->onDelete('cascade');
            $table->foreign('categoria_id')->references('id')->on('codigoitens')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orgaosubcategorias');
    }
}
