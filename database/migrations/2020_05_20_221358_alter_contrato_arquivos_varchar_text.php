<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoArquivosVarcharText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrato_arquivos', function (Blueprint $table) {
            $table->text('arquivos')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contrato_arquivos', function ($table) {
            $table->string('arquivos')->change();
        });
    }
}
