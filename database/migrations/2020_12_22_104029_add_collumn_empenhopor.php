<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollumnEmpenhopor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('minutaempenhos', function (Blueprint $table) {
            $table->char('empenhopor',3)->nullable();
            $table->char('numero_contrato',255)->nullable();
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
            $table->dropColumn('empenhopor');
            $table->dropColumn('numero_contrato');
        });
    }
}
