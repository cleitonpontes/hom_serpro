<?php

use App\Models\Codigoitem;
use App\Models\ContratoItemMinutaEmpenho;
use App\Models\MinutaEmpenhoRemessa;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoItemMinutaEmpenhoPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $minutas = ContratoItemMinutaEmpenho::select(
            'minutaempenho_id',
            'situacao_id',
            DB::raw('0 as remessa')
        )
            ->join('minutaempenhos','minutaempenhos.id','=','contrato_item_minuta_empenho.minutaempenho_id')
            ->where('minutaempenhos.etapa','>=',3)
            ->distinct()->get()->toArray();

        foreach ($minutas as $index => $minuta) {
            $novaRemessa = MinutaEmpenhoRemessa::create($minuta);
            $array_minutas[$minuta['minutaempenho_id']] = $novaRemessa->id;
        }

        Schema::table('contrato_item_minuta_empenho', function ($table) {
            $table->dropPrimary('contrato_item_minuta_empenho_pkey');
        });

        Schema::table('contrato_item_minuta_empenho', function ($table) {

            $table->bigIncrements('id');
            $table->bigInteger('minutaempenhos_remessa_id')->nullable()->unsigned()->index();
            $table->foreign('minutaempenhos_remessa_id')->references('id')->on('minutaempenhos_remessa')->onDelete('cascade');
            $table->unique(['contrato_item_id', 'minutaempenho_id', 'minutaempenhos_remessa_id']);

        });

        $cimes = ContratoItemMinutaEmpenho::all();
        foreach ($cimes as $cime) {
            $remessa_id = $array_minutas[$cime->minutaempenho_id];
            $cime->minutaempenhos_remessa_id = $remessa_id;
            $cime->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contrato_item_minuta_empenho', function (Blueprint $table) {

            $table->dropPrimary('contrato_item_minuta_empenho_pkey');
            $table->dropColumn('id');
            $table->dropColumn('minutaempenhos_remessa_id');
            $table->primary(['contrato_item_id', 'minutaempenho_id']);
        });
    }
}
