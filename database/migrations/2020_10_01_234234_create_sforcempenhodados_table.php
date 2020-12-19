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
            $table->integer('ugemitente'); //exemplo 110161
            $table->integer('anoempenho'); //2020
            $table->integer('tipoempenho'); //1 - Ordinário, 3 - Estimativo ou 5 - Global
            $table->integer('numempenho')->nullable(); //400001 a 800000
            $table->date('dtemis');
            $table->string('txtprocesso', 20)->nullable();
            $table->decimal('vlrtaxacambio', 10,4)->nullable();
            $table->decimal('vlrempenho', 17,2)->nullable(); //soma dos itens
            $table->string('codfavorecido', 14); //cnpj ou cpf ou ug ou idgenerico
            $table->integer('codamparolegal'); // codigo do amparo legal
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
