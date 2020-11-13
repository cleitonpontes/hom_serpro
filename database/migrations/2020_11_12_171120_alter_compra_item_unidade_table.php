<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompraItemUnidadeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compra_item_unidade', function (Blueprint $table) {
            $table->dropColumn('valor_item');
            $table->dropColumn('valor_total');
            $table->integer('quantidade_adquirir')->default(0);
            $table->integer('quantidade_adquirida')->default(0);
            $table->integer('quantidade_total')->default(0);

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
            $table->decimal('valor_item', 17, 4)->default(0);
            $table->decimal('valor_total', 17, 2)->default(0);
            $table->dropColumn('quantidade_adquirir');
            $table->dropColumn('quantidade_adquirida');
            $table->dropColumn('quantidade_total');
        });
    }
}
