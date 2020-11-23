<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollumnFonteApropriacaoNotaEmpenho extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apropriacoes_nota_empenho', function($table)
        {
            $table->string('fonte', 15)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apropriacoes_nota_empenho', function($table)
        {
            $table->string('fonte', 3)->default('000')->change();
        });
    }
}
