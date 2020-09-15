<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlosasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('glosas', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('contratoitem_servico_indicador_id')->unsigned()->index();
            $table->foreign('contratoitem_servico_indicador_id')->references('id')
                ->on('contratoitem_servico_indicador')->onDelete('cascade');

            $table->decimal('valor_maior',17,2)->default(0);
            $table->decimal('valor_menor',17,2)->default(0);
            $table->decimal('valor_glosa',17,2)->default(0);

            //TODO FALTA TIPO ESCOPO

//            $table->string('nome')->unique();
//            $table->text('detalhe');
//            $table->boolean('situacao')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicos');
    }
}
