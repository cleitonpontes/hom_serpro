<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfoperacaoitemempenhoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfoperacaoitemempenho', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sfitemempenho_id');
            $table->string('tipooperacaoitemempenho');
            $table->decimal('quantidade', 15,5);
            $table->decimal('vlrunitario', 19, 4);
            $table->decimal('vlroperacao', 17, 2)->nullable();
            $table->timestamps();

            $table->foreign('sfitemempenho_id')->references('id')->on('sfitemempenho')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sfoperacaoitemempenho');
    }
}
