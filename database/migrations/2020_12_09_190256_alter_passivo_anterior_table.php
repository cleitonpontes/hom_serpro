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

            $table->integer('minutaempenhos_remessa_id')->unsigned()->index();
            $table->foreign('minutaempenhos_remessa_id')->references('id')->on('minutaempenhos_remessa')->onDelete('cascade');
            $table->unique(['minutaempenho_id','minutaempenhos_remessa_id']);

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

            $table->dropColumn('minutaempenhos_remessa_id');

        });
    }
}
