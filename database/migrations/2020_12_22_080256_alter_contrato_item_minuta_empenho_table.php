<?php

use App\Models\Codigoitem;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoItemMinutaEmpenhoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $situacao = Codigoitem::wherehas('codigo', function ($q) {
            $q->where('descricao', '=', 'Operação item empenho');
        })
            ->where('descricao', 'INCLUSAO')
            ->first();

        Schema::table('contrato_item_minuta_empenho', function ($table) use ($situacao) {

            $table->dropPrimary('contrato_item_minuta_empenho_pkey');
            $table->primary(['contrato_item_id', 'minutaempenho_id']);
            $table->integer('operacao_id')->default($situacao->id);
            $table->foreign('operacao_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->timestamps();

        });
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
            $table->dropColumn('operacao_id');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->primary(['contrato_item_id', 'minutaempenho_id']);
        });
    }
}
