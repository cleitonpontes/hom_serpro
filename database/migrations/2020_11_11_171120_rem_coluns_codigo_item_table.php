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
            $table->integer('fornecedor_id')->nullable();
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores')->onDelete('cascade');
            $table->integer('unidade_autorizada_id')->nullable();
            $table->foreign('unidade_autorizada_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->decimal('quantidade', 10, 5)->default(0);
            $table->decimal('valorunitario', 17, 4)->default(0);
            $table->decimal('valortotal', 17, 2)->default(0);
        });
    }
}
