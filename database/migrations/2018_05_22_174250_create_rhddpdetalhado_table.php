<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRhddpdetalhadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rhddpdetalhado', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rhddp_id');
            $table->integer('rhsituacao_id');
            $table->integer('empenhodetalhado_id');
            $table->integer('nd');
            $table->integer('rubrica');
            $table->string('rubricadesc');
            $table->char('rubricatipo');
            $table->decimal('valor',15,2);
            $table->string('situacao');
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
        Schema::dropIfExists('rhddpdetalhado');
    }
}
