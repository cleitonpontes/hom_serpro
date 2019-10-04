<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratocronogramaCriaCampoSomaSubtrai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratocronograma', function (Blueprint $table) {
            $table->boolean('soma_subtrai')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratocronograma', function ($table) {
            $table->dropColumn('soma_subtrai');
        });
    }
}
