<?php

use App\Models\Codigoitem;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\MinutaEmpenhoRemessa;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterCompraItemMinutaEmpenhoPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $minutas = CompraItemMinutaEmpenho::select(
            'minutaempenho_id',
            'situacao_id',
            DB::raw('0 as remessa')
        )
            ->join('minutaempenhos', 'minutaempenhos.id', '=', 'compra_item_minuta_empenho.minutaempenho_id')
            ->where('minutaempenhos.etapa', '>=', 3)
            ->distinct()->get()->toArray();

        foreach ($minutas as $index => $minuta) {
            $novaRemessa = MinutaEmpenhoRemessa::create($minuta);
            $array_minutas[$minuta['minutaempenho_id']] = $novaRemessa->id;
        }

        Schema::table('compra_item_minuta_empenho', function ($table) {
            $table->dropPrimary('compra_item_minuta_empenho_pkey');
        });

        Schema::table('compra_item_minuta_empenho', function ($table) {

            $table->bigIncrements('id');
            $table->bigInteger('minutaempenhos_remessa_id')->nullable()->unsigned()->index();
            $table->foreign('minutaempenhos_remessa_id')->references('id')->on('minutaempenhos_remessa')->onDelete('cascade');
            $table->dropColumn('remessa');
            $table->unique(['compra_item_id', 'minutaempenho_id', 'minutaempenhos_remessa_id']);

        });

        $cimes = CompraItemMinutaEmpenho::all();

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
        Schema::table('compra_item_minuta_empenho', function (Blueprint $table) {

            $table->dropPrimary('compra_item_minuta_empenho_pkey');
            $table->dropColumn('minutaempenhos_remessa_id');
            $table->dropColumn('id');
            $table->integer('remessa')->default(0);
            $table->primary(['compra_item_id', 'minutaempenho_id', 'remessa']);
        });
    }
}
