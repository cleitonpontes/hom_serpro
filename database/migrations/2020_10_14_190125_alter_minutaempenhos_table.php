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
            $table->bigInteger('compra_id'); //tela01
            $table->bigInteger('fornecedor_compra_id')->nullable(); //tela02
            $table->bigInteger('fornecedor_empenho_id')->nullable(); //tela02 alterada pela tela06
            $table->bigInteger('saldo_contabil_id')->nullable(); //tela04
            $table->bigInteger('tipo_empenho_id')->nullable(); //tela 06
            $table->bigInteger('amparo_legal_id')->nullable(); //tela 06
            $table->integer('situacao_id');
            $table->date('data_emissao')->nullable(); //tela 06
            $table->string('processo')->nullable(); //tela 06
            $table->integer('numero_empenho_sequencial')->nullable(); //retorno numero empenho
            $table->decimal('taxa_cambio',10,4)->default(0);//tela 06
            $table->string('informacao_complementar', 100); //tela01
            $table->string('local_entrega', 250)->nullable();//tela 06
            $table->string('descricao', 468)->nullable();//tela 06
            $table->boolean('passivo_anterior')->default(false); //tela 07
            $table->string('conta_contabil_passivo_anterior')->nullable(); //tela 07
            $table->string('mensagem_siafi')->nullable(); //gravar retorno do WS
            $table->integer('etapa');
            $table->decimal('valor_total', 17,4)->default(0);
            //$table->string('situacao')->default('Em andamento'); //Em andamento, Em processamento, Erro, Empenho Emitido, Empenho cancelado

            $table->foreign('compra_id')->references('id')->on('compras')->onDelete('cascade');
            $table->foreign('fornecedor_compra_id')->references('id')->on('fornecedores')->onDelete('cascade');
            $table->foreign('fornecedor_empenho_id')->references('id')->on('fornecedores')->onDelete('cascade');
            $table->foreign('saldo_contabil_id')->references('id')->on('saldo_contabil')->onDelete('cascade');
            $table->foreign('tipo_empenho_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->foreign('amparo_legal_id')->references('id')->on('amparo_legal')->onDelete('cascade');
            $table->foreign('unidade_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->foreign('situacao_id')->references('id')->on('codigoitens')->onDelete('cascade');

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
