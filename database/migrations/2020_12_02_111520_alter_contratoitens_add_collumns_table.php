<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoitensAddCollumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratoitens', function (Blueprint $table) {
            $table->integer('periodicidade')->nullable();
            $table->date('data_inicio')->nullable();
            $table->decimal('quantidade', 15,5)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratoitens', function (Blueprint $table) {
            $table->dropColumn('periodicidade');
            $table->dropColumn('data_inicio');
        });
    }
}
