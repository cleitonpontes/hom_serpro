<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContaCorrentePassivoAnteriorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conta_corrente_passivo_anterior', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('minutaempenho_id');
            $table->string('conta_corrente');
            $table->decimal('valor',17,2)->default(0);
            $table->json('conta_corrente_json')->nullable();

            $table->foreign('minutaempenho_id')->references('id')->on('minutaempenhos')->onDelete('cascade');

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
        Schema::dropIfExists('conta_corrente_passivo_anterior');
    }
}
