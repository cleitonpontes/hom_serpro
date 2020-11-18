<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompraItemUnidadePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra_item_unidade', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compra_item_id')->unsigned()->index();
            $table->foreign('compra_item_id')->references('id')->on('compra_items')->onDelete('cascade');
            $table->integer('unidade_id')->unsigned()->index();
            $table->foreign('unidade_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->integer('fornecedor_id')->nullable();
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores')->onDelete('cascade');
//            $table->primary(['compra_item_id', 'unidade_id']);
            $table->unique(['compra_item_id', 'unidade_id']);
            $table->decimal('quantidade_autorizada', 10, 5)->default(0);
            $table->decimal('quantidade_saldo', 10, 5)->default(0);
            $table->decimal('valor_item', 17, 4)->default(0);
            $table->decimal('valor_total', 17, 2)->default(0);
            $table->string('tipo_uasg')->nullable();
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
        Schema::dropIfExists('compra_item_unidade');
    }
}
