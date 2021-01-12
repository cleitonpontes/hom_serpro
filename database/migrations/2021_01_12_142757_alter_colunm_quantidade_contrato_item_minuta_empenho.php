<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColunmQuantidadeContratoItemMinutaEmpenho extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrato_item_minuta_empenho', function (Blueprint $table) {
            $table->decimal('quantidade', 15,5)->nullable()->change();
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
            $table->decimal('quantidade', 10,5)->nullable()->change();
        });
    }
}
