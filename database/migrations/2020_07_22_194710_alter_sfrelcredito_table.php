<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfrelcreditoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfrelcredito', function (Blueprint $table) {
            $table->integer('sfdespesaanularitem_id')->nullable();

            $table->foreign('sfdespesaanularitem_id')->references('id')->on('sfdespesaanularitem')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfrelcredito', function (Blueprint $table) {
            $table->dropForeign('sfrelcredito_sfdespesaanularitem_id_foreign');
            $table->dropColumn('sfdespesaanularitem_id');
        });
    }
}
