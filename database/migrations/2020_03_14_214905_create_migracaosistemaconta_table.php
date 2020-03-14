<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMigracaosistemacontaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('migracaosistemaconta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('orgao_id');
            $table->timestamps();

            $table->foreign('orgao_id')->references('id')->on('orgaos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('migracaosistemaconta');
    }
}
