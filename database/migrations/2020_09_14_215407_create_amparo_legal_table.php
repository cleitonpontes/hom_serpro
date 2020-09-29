<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmparoLegalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amparo_legal', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('modalidade_id');
            $table->string('ato_normativo')->nullable();
            $table->integer('artigo')->nullable();
            $table->string('paragrafo')->nullable();
            $table->string('inciso')->nullable();
            $table->string('alinea')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('modalidade_id')->references('id')->on('codigoitens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amparo_legal');
    }
}
