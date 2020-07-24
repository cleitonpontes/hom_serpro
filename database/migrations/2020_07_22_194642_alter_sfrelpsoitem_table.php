<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfrelpsoitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfrelpsoitem', function (Blueprint $table) {
            $table->integer('sfdespesaanularitem_id')->nullable();
            $table->decimal('vlr')->nullable();

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
        Schema::table('sfrelpsoitem', function (Blueprint $table) {
            $table->dropForeign('sfrelpsoitem_sfdespesaanularitem_id_foreign');
            $table->dropColumn('vlr');
            $table->dropColumn('sfdespesaanularitem_id');
        });
    }
}
