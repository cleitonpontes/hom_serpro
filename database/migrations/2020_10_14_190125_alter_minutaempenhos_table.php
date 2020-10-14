<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMinutaempenhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('minutaempenhos', function (Blueprint $table) {
            $table->bigInteger('compra_id');
            $table->bigInteger('fornecedor_compra_id');
            $table->bigInteger('fornecedor_empenho_id');
            $table->bigInteger('saldo_contabil_id');
            $table->bigInteger('tipo_empenho_id');
            $table->bigInteger('amparo_legal_id');
            $table->date('data_emissao');
            $table->string('processo');
            $table->integer('numero_empenho_sequencial')->nullable();
            $table->decimal('taxa_cambio',10,4)->default(0);
            $table->string('informacao_complementar', 100);
            $table->string('local_entrega', 250)->nullable();
            $table->string('descricao', 468);
            $table->boolean('passivo_anterior')->default(false);
            $table->string('conta_contabil_passivo_anterior')->nullable();

            $table->foreign('compra_id')->references('id')->on('compras')->onDelete('cascade');
            $table->foreign('fornecedor_compra_id')->references('id')->on('fornecedores')->onDelete('cascade');
            $table->foreign('fornecedor_empenho_id')->references('id')->on('fornecedores')->onDelete('cascade');
            $table->foreign('saldo_contabil_id')->references('id')->on('saldo_contabil')->onDelete('cascade');
            $table->foreign('tipo_empenho_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->foreign('amparo_legal_id')->references('id')->on('amparo_legal')->onDelete('cascade');
            $table->foreign('unidade_id')->references('id')->on('unidades')->onDelete('cascade');

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

        Schema::table('minutaempenhos', function (Blueprint $table) {
            $table->dropColumn('compra_id');
            $table->dropColumn('fornecedor_compra_id');
            $table->dropColumn('fornecedor_empenho_id');
            $table->dropColumn('saldo_contabil_id');
            $table->dropColumn('tipo_empenho_id');
            $table->dropColumn('amparo_legal_id');
            $table->dropColumn('data_emissao');
            $table->dropColumn('processo');
            $table->dropColumn('numero_empenho_sequencial');
            $table->dropColumn('taxa_cambio');
            $table->dropColumn('informacao_complementar');
            $table->dropColumn('local_entrega');
            $table->dropColumn('descricao');
            $table->dropColumn('passivo_anterior');
            $table->dropColumn('conta_contabil_passivo_anterior');
            $table->dropColumn('deleted_at');
            $table->dropForeign('minutaempenhos_unidade_id_foreign');
        });
    }
}
