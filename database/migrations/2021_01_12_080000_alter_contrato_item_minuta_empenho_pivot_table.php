<?php

use App\Models\Codigoitem;
use App\Models\CompraItemMinutaEmpenho;
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

        //TODO verificar esta lógica.
        $minutas = ContratoItemMinutaEmpenho::select(
            'minutaempenho_id',
            'situacao_id',
            DB::raw('0 as remessa')
        )
            ->join('minutaempenhos','minutaempenhos.id','=','contrato_item_minuta_empenho.minutaempenho_id')
            ->where('minutaempenhos.etapa','>',3)
            ->distinct()->get()->toArray();

        MinutaEmpenhoRemessa::insert($minutas);

        $situacao_inclusao = Codigoitem::wherehas('codigo', function ($q) {
            $q->where('descricao', '=', 'Operação item empenho');
        })
            ->where('descricao', 'INCLUSAO')
            ->first();

        Schema::table('contrato_item_minuta_empenho', function ($table) {

            $table->dropPrimary('contrato_item_minuta_empenho_pkey');

        });

        Schema::table('contrato_item_minuta_empenho', function ($table) use ($situacao_inclusao) {

            $table->bigIncrements('id');
            $table->bigInteger('minutaempenhos_remessa_id')->nullable()->unsigned()->index();
            $table->foreign('minutaempenhos_remessa_id')->references('id')->on('minutaempenhos_remessa')->onDelete('cascade');
//            $table->integer('operacao_id')->default($situacao_inclusao->id);
//            $table->foreign('operacao_id')->references('id')->on('codigoitens')->onDelete('cascade');
//            $table->timestamps();
            $table->unique(['contrato_item_id', 'minutaempenho_id', 'minutaempenhos_remessa_id']);

        });

        $cimes = ContratoItemMinutaEmpenho::all();
        foreach ($cimes as $cime) {
                $remessa_id = MinutaEmpenhoRemessa::select('id')
                    ->where('minutaempenho_id', $cime->minutaempenho_id)->first()->id;
            $cime->minutaempenhos_remessa_id = $remessa_id;
            $cime->save();

        }
//        dd(1122333);

/*        Schema::table('contrato_item_minuta_empenho', function ($table) {
            $table->primary(['contrato_item_id', 'minutaempenho_id', 'minutaempenhos_remessa_id']);

        });*/
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
            $table->dropColumn('minutaempenhos_remessa_id');
            $table->dropColumn('operacao_id');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->primary(['contrato_item_id', 'minutaempenho_id']);
        });
    }
}
