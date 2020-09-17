<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfcronbaixapatrimonialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfcronbaixapatrimonial', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfpco_id')->nullable();
            $table->integer('outroslanc_id')->nullable();
            $table->string('parcela')->nullable();
        });

        Schema::table('sfcronbaixapatrimonial', function (Blueprint $table) {
            $table->foreign('sfpco_id')->references('id')->on('sfpco')->onDelete('cascade');
            $table->foreign('outroslanc_id')->references('id')->on('sfoutroslanc')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfcronbaixapatrimonial', function (Blueprint $table) {
            Schema::dropIfExists('sfcronbaixapatrimonial');
        });
    }
}
