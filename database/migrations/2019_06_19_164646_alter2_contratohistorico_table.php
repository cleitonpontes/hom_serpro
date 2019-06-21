<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alter2ContratohistoricoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratohistorico', function (Blueprint $table) {
            $table->decimal('novo_valor_global',17,2)->nullable();
            $table->integer('novo_num_parcelas')->nullable();
            $table->decimal('novo_valor_parcela',17,2)->nullable();
            $table->date('data_inicio_novo_valor')->nullable();
            $table->text('observacao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratohistorico', function ($table) {
            $table->dropColumn('novo_valor_global');
            $table->dropColumn('novo_num_parcelas');
            $table->dropColumn('novo_valor_parcela');
            $table->dropColumn('data_inicio_novo_valor');
            $table->dropColumn('observacao');
        });
    }
}
