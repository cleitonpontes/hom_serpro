<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfregistroalteracaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfregistroalteracao', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sforcempenhodado_id');
            $table->foreign('sforcempenhodado_id')->references('id')->on('sforcempenhodados')->onDelete('cascade');
            $table->date('dtemis')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('txtmotivo', 467)->nullable();
            $table->boolean('indrindispcaixa')->default(false);
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
        Schema::dropIfExists('sfregistroalteracao');
    }
}
