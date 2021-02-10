<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetFornecedorUniqueOnCompraItemUnidade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compra_item_unidade', function($table) {
            $table->dropUnique('compra_item_unidade_compra_item_id_unidade_id_unique');
            $table->unique(['compra_item_id', 'unidade_id','fornecedor_id'],'compra_item_unidade_compra_item_id_unidade_id_fornecedor_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compra_item_unidade', function($table) {
            $table->dropUnique('compra_item_unidade_compra_item_id_unidade_id_fornecedor_id_unique');
            $table->unique(['compra_item_id', 'unidade_id'],'compra_item_unidade_compra_item_id_unidade_id_unique');

        });
    }
}
