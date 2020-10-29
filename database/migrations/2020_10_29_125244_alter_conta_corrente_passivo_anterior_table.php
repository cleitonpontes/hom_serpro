<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContaCorrentePassivoAnteriorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conta_corrente_passivo_anterior', function (Blueprint $table) {
            $table->json('conta_corrente_json')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conta_corrente_passivo_anterior', function (Blueprint $table) {
            $table->dropColumn('conta_corrente_json');
        });
    }
}
