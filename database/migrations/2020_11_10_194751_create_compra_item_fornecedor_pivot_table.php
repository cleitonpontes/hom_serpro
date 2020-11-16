<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompraItemFornecedorPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra_item_fornecedor', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compra_item_id')->unsigned()->index();
            $table->foreign('compra_item_id')->references('id')->on('compra_items')->onDelete('cascade');
            $table->integer('fornecedor_id')->unsigned()->index();
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores')->onDelete('cascade');
//            $table->primary(['compra_item_id', 'fornecedor_id']);
            $table->unique(['compra_item_id', 'fornecedor_id']);
            $table->string('ni_fornecedor')->nullable();
            $table->string('classificacao')->nullable();
            $table->string('situacao_sicaf')->nullable();
            $table->integer('quantidade_homologada_vencedor')->default(0);
            $table->decimal('valor_unitario', 17, 4)->default(0);
            $table->decimal('valor_negociado', 17, 4)->default(0);
            $table->integer('quantidade_empenhada')->default(0);
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
        Schema::dropIfExists('compra_item_fornecedor');
    }
}
