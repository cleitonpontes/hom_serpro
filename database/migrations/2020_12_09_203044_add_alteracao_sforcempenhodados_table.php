<?php

use App\Models\MinutaEmpenhoRemessa;
use App\Models\SfOrcEmpenhoDados;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlteracaoSforcempenhodadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sforcempenhodados', function (Blueprint $table) {
            $table->boolean('alteracao')->default(false);

            $table->bigInteger('minutaempenhos_remessa_id')->unsigned()->index()->nullable();
            $table->foreign('minutaempenhos_remessa_id')->references('id')->on('minutaempenhos_remessa')->onDelete('cascade');
        });

        $registros =  SfOrcEmpenhoDados::all();
        foreach ($registros as $registro) {

            $remessa_id = MinutaEmpenhoRemessa::select('id')
                ->where('minutaempenho_id', $registro->minutaempenho_id)
                ->first()->id;
            $registro->minutaempenhos_remessa_id = $remessa_id;
            $registro->save();

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sforcempenhodados', function (Blueprint $table) {
            $table->dropColumn('alteracao');
            $table->dropColumn('minutaempenhos_remessa_id');
        });
    }
}
