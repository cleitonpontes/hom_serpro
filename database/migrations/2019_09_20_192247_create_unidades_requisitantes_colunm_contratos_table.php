<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnidadesRequisitantesColunmContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->text('unidades_requisitantes')->nullable();
        });

        Schema::table('contratohistorico', function (Blueprint $table) {
            $table->text('unidades_requisitantes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratos', function ($table) {
            $table->dropColumn('unidades_requisitantes');
        });

        Schema::table('contratohistorico', function ($table) {
            $table->dropColumn('unidades_requisitantes');
        });
    }
}
