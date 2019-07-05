<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatmatseritensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catmatseritens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('grupo_id');
            $table->bigInteger('codigo_siasg');
            $table->text('descricao');
            $table->boolean('situacao')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grupo_id')->references('id')->on('catmatsergrupos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catmatseritens');
    }
}
