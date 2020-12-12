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
            DB::raw($situacao_andamento->id . ' as situacao_id'),
            DB::raw('0 as remessa')
        )
            ->distinct()->get()->toArray();

        MinutaEmpenhoRemessa::insert($minutas);

        $situacao_inclusao = Codigoitem::wherehas('codigo', function ($q) {
            $q->where('descricao', '=', 'Operação item empenho');
        })
            ->where('descricao', 'INCLUSAO')
            ->first();

        Schema::table('compra_item_minuta_empenho', function ($table) {

            $table->dropPrimary('compra_item_minuta_empenho_pkey');

        });

        Schema::table('compra_item_minuta_empenho', function ($table) use ($situacao_inclusao) {

            $table->bigIncrements('id');
            $table->bigInteger('minutaempenhos_remessa_id')->nullable()->unsigned()->index();
            $table->foreign('minutaempenhos_remessa_id')->references('id')->on('minutaempenhos_remessa')->onDelete('cascade');
            $table->integer('operacao_id')->default($situacao_inclusao->id);
            $table->foreign('operacao_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['compra_item_id', 'minutaempenho_id', 'minutaempenhos_remessa_id']);

        });

        $cimes = CompraItemMinutaEmpenho::all();
        foreach ($cimes as $cime) {
                $remessa_id = MinutaEmpenhoRemessa::select('id')
                    ->where('minutaempenho_id', $cime->minutaempenho_id)->first()->id;
            $cime->minutaempenhos_remessa_id = $remessa_id;
            $cime->save();

        }
//        dd(1122333);

/*        Schema::table('compra_item_minuta_empenho', function ($table) {
            $table->primary(['compra_item_id', 'minutaempenho_id', 'minutaempenhos_remessa_id']);

        });*/
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
            $table->dropColumn('operacao_id');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->primary(['compra_item_id', 'minutaempenho_id']);
        });
    }
}
