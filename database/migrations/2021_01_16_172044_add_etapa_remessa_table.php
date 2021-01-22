<?php

use App\Models\MinutaEmpenhoRemessa;
use App\Models\SfOrcEmpenhoDados;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEtapaRemessaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('minutaempenhos_remessa', function (Blueprint $table) {
            $table->integer('etapa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('minutaempenhos_remessa', function (Blueprint $table) {
            $table->dropColumn('etapa');
        });
    }
}
