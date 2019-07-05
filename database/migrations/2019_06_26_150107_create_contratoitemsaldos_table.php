<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoitemsaldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratoitemsaldos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('contratoitem_id')->unique();
            $table->float('contratoitem_quantidade')->default(0);
            $table->decimal('contratoitem_valor_unitario',17,2)->default(0);
            $table->decimal('contratoitem_valor_total',17,2)->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('contratoitem_id')->references('id')->on('contratoitens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratoitemsaldos');
    }
}
