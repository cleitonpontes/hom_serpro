<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasItemUnidadeContratoitens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras_item_unidade_contratoitens', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('compra_item_unidade_id');
            $table->bigInteger('contratoitem_id');
            
            $table->foreign('compra_item_unidade_id')->references('id')->on('compra_item_unidade')->onDelete('cascade');
            $table->foreign('contratoitem_id')->references('id')->on('contratoitens')->onDelete('cascade');

            $table->unique(['compra_item_unidade_id', 'contratoitem_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras_item_unidade_contratoitens');
    }
}
