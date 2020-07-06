<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSfitemrecolhimentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfitemrecolhimento', function (Blueprint $table) {
            $table->integer('sfencargos_id')->nullable()->after('sfded_id');
            $table->integer('sfdadospgto_id')->nullable()->after('sfencargos_id');
        });
        Schema::table('sfitemrecolhimento', function (Blueprint $table) {
            $table->foreign('sfencargos_id')->references('id')->on('sfencargo')->onDelete('cascade');
            $table->foreign('sfdadospgto_id')->references('id')->on('sfdadospgto')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfitemrecolhimento', function (Blueprint $table) {
            $table->dropForeign('sfitemrecolhimento_sfded_id_foreign');
        });
    }
}
