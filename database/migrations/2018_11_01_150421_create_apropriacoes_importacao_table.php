<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApropriacoesImportacaoTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apropriacoes_importacao', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome_arquivo', 400);
            $table->integer('numero_linha');
            $table->string('linha', 400);
            $table->integer('apropriacao_id');
            $table->string('competencia', 7);
            $table->string('nivel', 3);
            $table->string('categoria', 1);
            $table->string('conta', 8);
            $table->string('rubrica', 5);
            $table->string('descricao', 50);
            $table->decimal('valor', 15, 2)->default(0);
            $table->string('situacao', 8)->nullable();
            $table->string('vpd', 9)->nullable();
            // Marca o valor anterior da situação e vpd (quando o registro for atualizado após Passo 3)
            $table->string('situacao_original', 8)->nullable();
            $table->string('vpd_original', 9)->nullable();

            $table->foreign('apropriacao_id')
                ->references('id')
                ->on('apropriacoes')
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
        Schema::dropIfExists('apropriacoes_importacao');
    }
}
