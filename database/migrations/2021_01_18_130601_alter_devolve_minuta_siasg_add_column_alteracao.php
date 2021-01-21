<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDevolveMinutaSiasgAddColumnAlteracao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devolve_minuta_siasg', function (Blueprint $table) {
            $table->boolean('alteracao')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devolve_minuta_siasg', function (Blueprint $table) {
            $table->dropColumn('alteracao');
        });
    }
}
