<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterImportacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('importacoes', function (Blueprint $table) {
            $table->integer('contrato_id')->nullable()->change();
            $table->integer('role_id')->nullable();
            $table->text('arquivos')->change();
            $table->string('mensagem')->nullable();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('importacoes', function (Blueprint $table) {
            $table->integer('contrato_id')->nullable(false)->change();
            $table->string('arquivos')->change();
            $table->dropColumn('role_id')->change();
            $table->dropColumn('mensagem')->change();
        });
    }
}
