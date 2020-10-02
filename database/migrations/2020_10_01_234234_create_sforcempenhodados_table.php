<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSforcempenhodadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sforcempenhodados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('minutaempenho_id');
            $table->integer('ugemitente');
            $table->integer('anoempenho');
            $table->integer('tipoempenho');
            $table->integer('numempenho')->nullable();
            $table->date('dtemis');
            $table->string('txtprocesso', 20)->nullable();
            $table->decimal('vlrtaxacambio', 10,4)->nullable();
            $table->decimal('vlrempenho', 17,2)->nullable();
            $table->string('codfavorecido', 14);
            $table->integer('codamparolegal');
            $table->string('txtinfocompl')->nullable();
            $table->integer('codtipotransf')->nullable();
            $table->string('txtlocalentrega', 250)->nullable();
            $table->string('txtdescricao', 468);
            $table->string('numro')->nullable();
            $table->text('mensagemretorno')->nullable();
            $table->string('situacao'); //PENDENTE, SUCESSO, ERRO, FALHA WEBSERVICE
            $table->timestamps();

            $table->foreign('minutaempenho_id')->references('id')->on('minutaempenhos')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sforcempenhodados');
    }
}
