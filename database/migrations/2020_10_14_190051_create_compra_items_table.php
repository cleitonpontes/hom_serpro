<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompraItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('compra_id');
            $table->integer('tipo_item_id');
            $table->integer('catmatseritem_id');
            $table->integer('fornecedor_id');
            $table->integer('unidade_autorizada_id');
            $table->text('descricaodetalhada');
            $table->decimal('quantidade', 10, 5)->default(0);
            $table->decimal('valorunitario', 17, 4)->default(0);
            $table->decimal('valortotal', 17, 2)->default(0);
            $table->integer('qtd_total')->nullable();
            $table->text('numero');

            $table->foreign('compra_id')->references('id')->on('compras')->onDelete('cascade');
            $table->foreign('tipo_item_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->foreign('catmatseritem_id')->references('id')->on('catmatseritens')->onDelete('cascade');
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores')->onDelete('cascade');
            $table->foreign('unidade_autorizada_id')->references('id')->on('unidades')->onDelete('cascade');

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
        Schema::dropIfExists('compra_items');
    }
}
