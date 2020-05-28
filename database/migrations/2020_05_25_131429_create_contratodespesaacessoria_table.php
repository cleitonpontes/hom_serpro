<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratodespesaacessoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratodespesaacessoria', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->integer('tipo_id');
            $table->integer('recorrencia_id');
            $table->string('descricao_complementar')->nullable();
            $table->date('vencimento')->nullable();
            $table->decimal('valor');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->foreign('tipo_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->foreign('recorrencia_id')->references('id')->on('codigoitens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratodespesaacessoria');
    }
}
