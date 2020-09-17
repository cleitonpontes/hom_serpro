<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSftramiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sftramite', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfdadosbasicos_id');
            $table->string('txtlocal')->nullable();
            $table->date('dtentrada')->nullable();
            $table->date('dtsaida')->nullable();
        });

        Schema::table('sftramite', function (Blueprint $table) {
            $table->foreign('sfdadosbasicos_id')->references('id')->on('sfdadosbasicos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sftramite', function (Blueprint $table) {
            chema::dropIfExists('sftramite');
        });
    }
}
