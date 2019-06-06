<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratohistoricoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('contratohistorico');

        Schema::create('contratohistorico', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero');
            $table->integer('contrato_id');
            $table->integer('fornecedor_id')->nullable();
            $table->integer('unidade_id')->nullable();
            $table->integer('tipo_id')->nullable();
            $table->integer('categoria_id')->nullable();
            $table->char('receita_despesa')->nullable();
            $table->string('processo')->nullable();
            $table->text('objeto')->nullable();
            $table->text('info_complementar')->nullable();
            $table->string('fundamento_legal')->nullable();
            $table->integer('modalidade_id')->nullable();
            $table->string('licitacao_numero')->nullable();
            $table->date('data_assinatura');
            $table->date('data_publicacao')->nullable();
            $table->date('vigencia_inicio')->nullable();
            $table->date('vigencia_fim')->nullable();
            $table->decimal('valor_inicial', 17,2)->nullable();
            $table->decimal('valor_global',17,2)->nullable();
            $table->integer('num_parcelas')->nullable();
            $table->decimal('valor_parcela',17,2)->nullable();
            $table->decimal('valor_acumulado',17,2)->nullable();
            $table->string('situacao_siasg')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores')->onDelete('cascade');
            $table->foreign('unidade_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->foreign('categoria_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->foreign('tipo_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->foreign('modalidade_id')->references('id')->on('codigoitens')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratohistorico');
    }
}
