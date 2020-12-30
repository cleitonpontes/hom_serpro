<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEmpenhodetalhadoRemoveUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empenhodetalhado', function (Blueprint $table) {
            $table->dropUnique('empenhodetalhado_empenho_id_naturezasubitem_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empenhodetalhado', function (Blueprint $table) {
            $table->unique(['empenho_id','naturezasubitem_id']);
        });
    }
}
