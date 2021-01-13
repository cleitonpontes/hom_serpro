<?php

use App\Models\Codigoitem;
use App\Models\ContaCorrentePassivoAnterior;
use App\Models\MinutaEmpenhoRemessa;
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

            $table->bigInteger('minutaempenhos_remessa_id')->unsigned()->index()->nullable();
            $table->foreign('minutaempenhos_remessa_id')->references('id')->on('minutaempenhos_remessa')->onDelete('cascade');

        });

        $ccpas = ContaCorrentePassivoAnterior::all();
        foreach ($ccpas as $ccpa) {
            $remessa_id = MinutaEmpenhoRemessa::select('id')
                ->where('minutaempenho_id', $ccpa->minutaempenho_id)->first()->id;
            $ccpa->minutaempenhos_remessa_id = $remessa_id;
            $ccpa->save();

        }
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
