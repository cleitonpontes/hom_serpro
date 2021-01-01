<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollumnEmpenhopor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('minutaempenhos', function (Blueprint $table) {
            $table->dropColumn('empenhopor');
            $table->integer('tipo_empenhopor_id')->nullable()->unsigned()->index();
            $table->foreign('tipo_empenhopor_id')->references('id')->on('codigoitens')->onDelete('cascade');

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
            $table->dropColumn('tipo_empenhopor_id');
            $table->char('empenhopor',3)->nullable();
        });

    }
}
