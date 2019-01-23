<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfdocorigemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfdocorigem', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfdadosbasicos_id');
            $table->string('codidentemit')->nullable();
            $table->date('dtemis')->nullable();
            $table->string('numdocorigem')->nullable();
            $table->decimal('vlr',15,2)->nullable();
        });
        Schema::table('sfdocorigem', function (Blueprint $table) {
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
        Schema::dropIfExists('sfdocorigem');
    }
}
