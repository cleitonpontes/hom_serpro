<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfdadosbasicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfdadosbasicos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfpadrao_id');
            $table->date('dtemis')->nullable();
            $table->date('dtvenc')->nullable();
            $table->integer('codugpgto')->nullable();
            $table->decimal('vlr',15,2)->nullable();
            $table->string('txtobser')->nullable();
            $table->string('txtinfoadic')->nullable();
            $table->decimal('vlrtaxacambio',15,2)->nullable();
            $table->string('txtprocesso')->nullable();
            $table->date('dtateste')->nullable();
            $table->string('codcredordevedor');
            $table->date('dtpgtoreceb')->nullable();
        });

        Schema::table('sfdadosbasicos', function (Blueprint $table) {
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
        Schema::dropIfExists('sfdadosbasicos');
    }
}
