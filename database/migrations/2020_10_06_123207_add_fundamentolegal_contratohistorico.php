<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFundamentolegalContratohistorico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratohistorico', function (Blueprint $table) {
            $table->integer('amparo_legal_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratohistorico', function (Blueprint $table) {
            $table->dropColumn('amparo_legal_id');
        });
    }
}
