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
            $table->string('numero');
            $table->integer('fornecedor_id');
            $table->integer('unidade_id');
            $table->integer('tipo_id')->nullable();
            $table->integer('categoria_id')->nullable();
            $table->string('processo')->nullable();
            $table->text('objeto');
            $table->text('info_complementar')->nullable();
            $table->string('fundamento_legal')->nullable();
            $table->integer('modalidade_id')->nullable();
            $table->string('licitacao_numero')->nullable();
            $table->date('data_assinatura');
            $table->date('data_publicacao')->nullable();
            $table->date('vigencia_inicio');
            $table->date('vigencia_fim');
            $table->decimal('valor_inicial', 15,2);
            $table->decimal('valor_global',15,2);
            $table->integer('num_parcelas')->nullable();
            $table->decimal('valor_parcela',15,2)->nullable();
            $table->decimal('valor_acumulado',15,2)->nullable();
            $table->string('situacao_siasg')->nullable();
            $table->boolean('situacao');
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('contratos');
    }
}
