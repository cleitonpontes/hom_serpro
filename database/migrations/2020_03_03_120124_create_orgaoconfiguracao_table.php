<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgaoconfiguracaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orgaoconfiguracao', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('orgao_id')->unique();
            $table->string('padrao_processo_marcara');
            $table->string('api_migracao_conta_url')->nullable();
            $table->string('api_migracao_conta_token')->nullable();
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
        Schema::dropIfExists('orgaoconfiguracao');
    }
}
