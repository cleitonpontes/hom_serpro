<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemColunsCodigoItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compra_items', function (Blueprint $table) {
            $table->dropColumn('fornecedor_id');
            $table->dropColumn('unidade_autorizada_id');
            $table->dropColumn('quantidade');
            $table->dropColumn('valorunitario');
            $table->dropColumn('valortotal');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compra_items', function (Blueprint $table) {
            $table->integer('fornecedor_id');
            $table->integer('unidade_autorizada_id');
            $table->decimal('quantidade', 10, 5)->default(0);
            $table->decimal('valorunitario', 17, 4)->default(0);
            $table->decimal('valortotal', 17, 2)->default(0);
        });
    }
}
