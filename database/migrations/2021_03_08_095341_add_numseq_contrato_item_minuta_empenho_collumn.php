<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNumseqContratoItemMinutaEmpenhoCollumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrato_item_minuta_empenho', function (Blueprint $table) {
            $table->string('numseq')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contrato_item_minuta_empenho', function (Blueprint $table) {
            $table->dropColumn('numseq');
        });
    }
}
