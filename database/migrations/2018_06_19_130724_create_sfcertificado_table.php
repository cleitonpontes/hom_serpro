<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfcertificadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfcertificado', function (Blueprint $table) {
            $table->increments('id');
            $table->string('certificado');
            $table->string('chaveprivada');
            $table->date('vencimento');
            $table->boolean('situacao');
            $table->timestamps();

            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sfcertificado');
    }
}
