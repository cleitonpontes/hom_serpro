<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoresponsavelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratoresponsaveis', function (Blueprint $table) {
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratoresponsaveis', function ($table) {
            $table->dropColumn('data_inicio');
            $table->dropColumn('data_fim');
        });
    }
}
