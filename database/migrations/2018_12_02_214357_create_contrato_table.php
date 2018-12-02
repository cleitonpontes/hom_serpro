<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('numero');
            $table->integer('fornecedor_id');
            $table->integer('unidade_id');
            $table->integer('categoria_id')->nullable();
            $table->string('processo')->nullable();
            $table->text('objeto');
            $table->text('info_complementar')->nullable();
            $table->string('fundamento_legal');
            $table->string('modalidade');
            $table->string('licitacao_numero');
            $table->date('data_assinatura');
            $table->date('data_publicacao');
            $table->date('vigencia_inicio');
            $table->date('vigencia_fim');
            $table->decimal('valor_inicial');
            $table->decimal('valor_global');
            $table->integer('num_parcelas')->nullable();
            $table->decimal('valor_parcela')->nullable();
            $table->decimal('valor_acumulado');
            $table->string('situacao_siasg');
            $table->boolean('situacao');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('fornecedor_id')->references('id')->on('fornecedores')->onDelete('cascade');
            $table->foreign('unidade_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->foreign('categoria_id')->references('id')->on('codigoitens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratos');
    }
}
