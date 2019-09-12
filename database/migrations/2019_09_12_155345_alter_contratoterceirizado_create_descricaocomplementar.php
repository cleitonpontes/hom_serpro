<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoterceirizadoCreateDescricaocomplementar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratoterceirizados', function (Blueprint $table) {
            $table->string('descricao_complementar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratoterceirizados', function ($table) {
            $table->dropColumn('descricao_complementar');
        });
    }
}
