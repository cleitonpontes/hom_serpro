<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableContratocontasEncerramento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratocontas', function (Blueprint $table) {
            $table->date('data_encerramento')->nullable();
            $table->integer('user_id_encerramento')->nullable();
            $table->text('obs_encerramento')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratocontas', function (Blueprint $table) {
            $table->dropColumn('data_encerramento');
            $table->dropColumn('user_id_encerramento');
            $table->dropColumn('obs_encerramento');
        });
    }
}
