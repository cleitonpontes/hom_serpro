<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApropriacoesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apropriacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('competencia', 7);
            $table->string('ug', 6);
            $table->string('nivel', 3)->nullable();
            $table->decimal('valor_liquido', 15, 2)->default(0);
            $table->decimal('valor_bruto', 15, 2)->default(0);
            $table->integer('fase_id')->default(0);
            $table->string('arquivos', 800)->nullable();
            $table->date('ateste')->nullable();
            $table->string('nup', 50)->nullable();
            $table->string('doc_origem', 50)->nullable();
            $table->text('observacoes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apropriacoes');
    }
}
