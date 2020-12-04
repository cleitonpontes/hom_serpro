<?php

use App\Models\Codigoitem;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPassivoAnteriorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('conta_corrente_passivo_anterior', function ($table) {

            $table->integer('remessa')->default(0);

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

            $table->dropColumn('remessa');

        });
    }
}
