<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumsContratopublicacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratopublicacoes', function (Blueprint $table) {
            $table->text('log')->change();
            $table->boolean('publicar')->nullable()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratopublicacoes', function (Blueprint $table) {
            $table->string('log')->change();
            $table->dropColumn('publicar');
        });
    }
}
