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

            $table->decimal('from',17,2)->default(0);
            $table->decimal('to',17,2)->default(0);
            $table->decimal('valor_glosa',17,2)->default(0);
            $table->integer('escopo_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('escopo_id')->references('id')->on('codigoitens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('glosas');
    }
}
