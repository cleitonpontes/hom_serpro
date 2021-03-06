<?php

use App\Models\Codigoitem;
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

        $situacao = Codigoitem::wherehas('codigo', function ($q) {
            $q->where('descricao', '=', 'Operação item empenho');
        })
            ->where('descricao', 'INCLUSAO')
            ->first();

        Schema::table('compra_item_minuta_empenho', function ($table) use ($situacao) {

            $table->dropPrimary('compra_item_minuta_empenho_pkey');
            $table->integer('remessa')->default(0);
            $table->primary(['compra_item_id', 'minutaempenho_id','remessa']);
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
        Schema::table('compra_item_minuta_empenho', function (Blueprint $table) {

            $table->dropPrimary('compra_item_minuta_empenho_pkey');
            $table->dropColumn('remessa');
            $table->dropColumn('operacao_id');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->primary(['compra_item_id', 'minutaempenho_id']);
        });
    }
}
