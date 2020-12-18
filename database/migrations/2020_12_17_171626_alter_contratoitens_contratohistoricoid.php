<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoitensContratohistoricoid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratoitens', function (Blueprint $table) {
            $table->integer('contratohistorico_id')->nullable();
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
        Schema::table('contratoitens', function (Blueprint $table) {
            $table->dropColumn('contratohistorico_id');
        });
    }
}
