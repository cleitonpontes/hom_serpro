<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfpadraoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfpadrao', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk')->nullable();
            $table->string('categoriapadrao');
            $table->string('decricaopadrao');
            $table->integer('codugemit');
            $table->integer('anodh')->nullable();
            $table->char('codtipodh',2);
            $table->integer('numdh')->nullable();
            $table->date('dtemis')->nullable();
            $table->string('txtmotivo', 468)->nullable();
            $table->string('msgretorno')->nullable();
            $table->char('tipo',1);
            $table->char('situacao',1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sfpadrao');
    }
}
