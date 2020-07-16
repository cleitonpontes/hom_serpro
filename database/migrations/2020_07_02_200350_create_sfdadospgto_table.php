<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfdadospgtoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfdadospgto', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfpadrao_id');
            $table->string('codcredordevedor')->nullable();
            $table->decimal('vlr',15,2)->nullable()->default(0);
        });

        Schema::table('sfdadospgto', function (Blueprint $table) {
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
        Schema::table('sfdadospgto', function (Blueprint $table) {
            Schema::dropIfExists('sfdadospgto');
        });
    }
}
