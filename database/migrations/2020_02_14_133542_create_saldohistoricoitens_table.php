<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaldohistoricoitensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saldohistoricoitens', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('saldoable');
            $table->integer('contratoitem_id');
            $table->integer('tiposaldo_id');
            $table->integer('quantidade');
            $table->decimal('valorunitario',17,2)->default(0);
            $table->decimal('valortotal',17,2)->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('contratoitem_id')->references('id')->on('contratoitens')->onDelete('cascade');
            $table->foreign('tiposaldo_id')->references('id')->on('codigoitens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saldohistoricoitens');
    }
}
