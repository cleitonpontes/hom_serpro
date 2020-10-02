<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfptresdecodificadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfptresdecodificado', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sfcelulaorcamentaria_id');
            $table->integer('uo');
            $table->string('codpt', 17);
            $table->string('indrresultadolei', 1);
            $table->string('indrtipocredito', 1);
            $table->string('codplanoorc', 4);
            $table->string('codautoremenda', 12);
            $table->timestamps();

            $table->foreign('sfcelulaorcamentaria_id')->references('id')->on('sfcelulaorcamentaria')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sfptresdecodificado');
    }
}
