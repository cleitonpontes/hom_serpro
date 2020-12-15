<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoItensValoresQuatroCasasDecimais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratoitens', function (Blueprint $table) {
            $table->decimal('valorunitario',19,4)->default(0)->change();
            $table->decimal('valortotal',19,4)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratoitens', function (Blueprint $table) {
            $table->dropColumn('valorunitario')->change();
            $table->dropColumn('valortotal')->change();
        });
    }
}
