<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevolveMinutaSiasgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devolve_minuta_siasg', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('minutaempenho_id');
            $table->string('situacao');
            $table->timestamps();

            $table->foreign('minutaempenho_id')->references('id')->on('minutaempenhos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devolve_minuta_siasg');
    }
}
