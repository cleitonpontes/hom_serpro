<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompraItemQuantidadeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compra_items', function (Blueprint $table) {
            $table->decimal('quantidade', 10, 5)->default(0)->change();
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
            $table->integer('quantidade')->default(0);
        });
    }
}
