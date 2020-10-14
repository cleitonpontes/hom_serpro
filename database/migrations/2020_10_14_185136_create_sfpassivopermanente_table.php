<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfpassivopermanenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfpassivopermanente', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sfpassivoanterior_id');
            $table->string('contacorrente');
            $table->decimal('vlrrelacionado',17,2);
            $table->timestamps();

            $table->foreign('sfpassivoanterior_id')->references('id')->on('sfpassivoanterior')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sfpassivopermanente');
    }
}
