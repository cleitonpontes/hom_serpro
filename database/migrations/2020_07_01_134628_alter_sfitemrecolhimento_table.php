<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfitemrecolhimentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfitemrecolhimento', function (Blueprint $table) {
            $table->bigInteger('numseqitem')->nullable()->default(0)->change();
            $table->decimal('vlr',15,2)->nullable()->default(0)->change();
            $table->decimal('vlrbasecalculo',15,2)->nullable()->default(0)->change();
            $table->decimal('vlrmulta',15,2)->nullable()->default(0)->change();
            $table->decimal('vlrjuros',15,2)->nullable()->default(0)->change();
            $table->decimal('vlroutrasent',15,2)->nullable()->default(0)->change();
            $table->decimal('vlratmmultajuros',15,2)->nullable()->default(0)->change();
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
            $table->bigInteger('numseqitem')->nullable();
            $table->decimal('vlr',15,2)->nullable();
            $table->decimal('vlrbasecalculo',15,2)->nullable();
            $table->decimal('vlrmulta',15,2)->nullable();
            $table->decimal('vlrjuros',15,2)->nullable();
            $table->decimal('vlroutrasent',15,2)->nullable();
            $table->decimal('vlratmmultajuros',15,2)->nullable();
        });
    }
}
