<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLancamentosCreateSalarioAtual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lancamentos', function (Blueprint $table) {
            $table->decimal('salario_atual',15,2)->default(0)->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lancamentos', function (Blueprint $table) {
            $table->dropColumn('salario_atual');
        });
    }
}
