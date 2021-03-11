<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNumseqCompraItemMinutaEmpenhoCollumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compra_item_minuta_empenho', function (Blueprint $table) {
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
        Schema::table('compra_item_minuta_empenho', function (Blueprint $table) {
            $table->dropColumn('numseq');
        });
    }
}
