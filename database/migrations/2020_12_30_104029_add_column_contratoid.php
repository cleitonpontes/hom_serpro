<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnContratoid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('minutaempenhos', function (Blueprint $table) {
            $table->integer('contrato_id')->nullable()->unsigned()->index();
            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('minutaempenhos', function (Blueprint $table) {
            $table->dropColumn('contrato_id');
        });

    }
}
