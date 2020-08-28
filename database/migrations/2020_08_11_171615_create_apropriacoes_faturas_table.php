<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApropriacoesFaturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apropriacoes_faturas', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('valor', 15, 2)->default(0);
            $table->integer('fase_id')->default(0);
            $table->timestamps();

            $table->foreign('fase_id')->references('id')->on('codigoitens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apropriacoes_faturas');
    }
}
