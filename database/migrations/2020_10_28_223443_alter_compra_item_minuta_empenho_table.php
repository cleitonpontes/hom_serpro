<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompraItemMinutaEmpenhoTable extends Migration
{
    //TODO MIGRATE OK DELETAR
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compra_item_minuta_empenho', function (Blueprint $table) {
            $table->integer('subelemento_id')->nullable()->change();
            $table->decimal('quantidade', 10,5)->default(0)->nullable()->change();
            $table->decimal('valor', 17,2)->default(0)->nullable()->change();
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
            $table->integer('subelemento_id')->nullable(false)->change();
            $table->decimal('quantidade', 10,5)->default(0)->nullable(false)->change();
            $table->decimal('valor', 17,2)->default(0)->nullable(false)->change();
        });
    }
}
