<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompraItemMinutaEmpenhoPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra_item_minuta_empenho', function (Blueprint $table) {
            $table->integer('compra_item_id')->unsigned()->index();
            $table->integer('minutaempenho_id')->unsigned()->index();
            $table->integer('subelemento_id')->nullable();
            $table->decimal('quantidade', 10,5)->default(0)->nullable();
            $table->decimal('valor', 17,2)->default(0)->nullable();

            $table->foreign('minutaempenho_id')->references('id')->on('minutaempenhos')->onDelete('cascade');
            $table->foreign('compra_item_id')->references('id')->on('compra_items')->onDelete('cascade');
            $table->foreign('subelemento_id')->references('id')->on('naturezasubitem')->onDelete('cascade');
            $table->primary(['compra_item_id', 'minutaempenho_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compra_item_minuta_empenho');
    }
}
