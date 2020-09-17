<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfdocrelacionadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfdocrelacionado', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('sfdadosbasicos_id');
                $table->integer('codugemit')->nullable();
                $table->string('numdocrelacionado')->nullable();
            });

            Schema::table('sfdocrelacionado', function (Blueprint $table) {
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
        Schema::table('sfdocrelacionado', function (Blueprint $table) {
            Schema::dropIfExists('sfdocrelacionado');
        });
    }
}
