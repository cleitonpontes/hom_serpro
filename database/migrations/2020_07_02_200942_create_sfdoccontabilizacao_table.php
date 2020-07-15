<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfdoccontabilizacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfdoccontabilizacao', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfpadrao_id');
            $table->integer('anodoccont')->nullable();
            $table->string('codtipodoccont')->nullable();
            $table->string('numcodcont')->nullable();
            $table->integer('codugemit')->nullable();
        });

        Schema::table('sfdoccontabilizacao', function (Blueprint $table) {
            $table->foreign('sfpadrao_id')->references('id')->on('sfpadrao')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfdoccontabilizacao', function (Blueprint $table) {
            Schema::dropIfExists('sfdoccontabilizacao');
        });
    }
}
