<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratocronogramaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratocronograma', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->integer('contratohistorico_id');
            $table->char('receita_despesa',1);
            $table->string('mesref');
            $table->string('anoref');
            $table->date('vencimento');
            $table->decimal('valor',17,2)->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->foreign('contratohistorico_id')->references('id')->on('contratohistorico')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratocronograma');
    }
}
