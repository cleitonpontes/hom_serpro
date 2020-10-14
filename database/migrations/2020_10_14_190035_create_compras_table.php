<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unidade_origem_id');
            $table->integer('unidade_subrrogada_id')->nullable();
            $table->integer('modalidade_id');
            $table->integer('tipo_compra_id');
            $table->string('numero_ano');
            $table->string('inciso')->nullable();
            $table->string('lei')->nullable();

            $table->foreign('unidade_origem_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->foreign('unidade_subrrogada_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->foreign('modalidade_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->foreign('tipo_compra_id')->references('id')->on('codigoitens')->onDelete('cascade');

            $table->unique([
                'unidade_origem_id',
                'modalidade_id',
                'numero_ano',
                'tipo_compra_id',
            ]);

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
        Schema::dropIfExists('compras');
    }
}
