<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfcelulaorcamentariaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfcelulaorcamentaria', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sforcempenhodado_id');
            $table->string('esfera', 1);
            $table->string('codptres', 6)->nullable();
            $table->string('codfonterec', 10);
            $table->string('codnatdesp', 6);
            $table->integer('ugresponsavel')->nullable();
            $table->string('codplanointerno', 11)->nullable();
            $table->timestamps();

            $table->foreign('sforcempenhodado_id')->references('id')->on('sforcempenhodados')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sfcelulaorcamentaria');
    }
}
