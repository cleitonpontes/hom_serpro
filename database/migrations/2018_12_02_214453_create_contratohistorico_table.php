<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratohistoricoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratohistorico', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero');
            $table->integer('contrato_id');
            $table->integer('fornecedor_id');
            $table->integer('tipo_id')->nullable();
            $table->integer('categoria_id')->nullable();
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
