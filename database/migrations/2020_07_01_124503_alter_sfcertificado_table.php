<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfcertificadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfcertificado', function (Blueprint $table) {
            $table->string('certificado')->nullable()->change();;
            $table->string('chaveprivada')->nullable()->change();;
            $table->date('vencimento')->nullable()->change();;
            $table->boolean('situacao')->nullable()->change();;
            $table->string('senhacertificado')->nullable()->default('0')->change();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfcertificado', function (Blueprint $table) {
            $table->string('certificado');
            $table->string('chaveprivada');
            $table->date('vencimento');
            $table->boolean('situacao');
            $table->string('senhacertificado');
        });
    }
}
