<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCodigoRestricaoNullableAmparoLegalRestricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amparo_legal_restricoes', function($table)
        {
            $table->string('codigo_restricao')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amparo_legal_restricoes', function($table)
        {
            $table->string('codigo_restricao')->nullable(false)->change();
        });
    }
}
