<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompraItemUnidadeDecimalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compra_item_unidade', function (Blueprint $table) {
            $table->decimal('quantidade_adquirir',15,5)->default(0)->change();
            $table->decimal('quantidade_adquirida',15,5)->default(0)->change();
            $table->decimal('quantidade_autorizada',15,5)->default(0)->change();
            $table->decimal('quantidade_saldo',15,5)->default(0)->change();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compra_item_unidade', function (Blueprint $table) {
            $table->integer('quantidade_adquirir')->default(0)->change();
            $table->integer('quantidade_adquirida')->default(0)->change();

            $table->decimal('quantidade_autorizada', 10, 5)->default(0)->change();
            $table->decimal('quantidade_saldo', 10, 5)->default(0)->change();

        });
    }
}
