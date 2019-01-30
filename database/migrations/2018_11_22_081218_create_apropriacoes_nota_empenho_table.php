<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApropriacoesNotaEmpenhoTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apropriacoes_nota_empenho', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('apropriacao_situacao_id');
            $table->string('empenho', 12)->nullable();
            $table->string('fonte', 3)->default('000');
            $table->decimal('valor_rateado', 15, 2)->default(0);

            $table->foreign('apropriacao_situacao_id')
                ->references('id')
                ->on('apropriacoes_situacao')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apropriacoes_nota_empenho');
    }
}
