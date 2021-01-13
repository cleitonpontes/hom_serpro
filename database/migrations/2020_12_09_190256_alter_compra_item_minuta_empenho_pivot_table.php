<?php

use App\Models\Codigoitem;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\MinutaEmpenhoRemessa;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompraItemMinutaEmpenhoPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $situacao_andamento = Codigoitem::wherehas('codigo', function ($q) {
            $q->where('descricao', '=', 'Situações Minuta Empenho');
        })
            ->where('descricao', 'EM ANDAMENTO')
            ->first();

        $minutas = CompraItemMinutaEmpenho::select(
            'minutaempenho_id',
            'situacao_id',
            DB::raw('0 as remessa')
        )
            ->join('minutaempenhos','minutaempenhos.id','=','compra_item_minuta_empenho.minutaempenho_id')
            ->where('minutaempenhos.etapa','>',3)
            ->distinct()->get()->toArray();

        MinutaEmpenhoRemessa::insert($minutas);

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
                $remessa_id = MinutaEmpenhoRemessa::select('id')
                    ->where('minutaempenho_id', $cime->minutaempenho_id)->first()->id;
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
            $table->primary(['compra_item_id', 'minutaempenho_id','remessa']);
        });
    }
}
