<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alter2ExecsfsituacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('execsfsituacao', function (Blueprint $table) {
            $table->boolean('afeta_custo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('execsfsituacao', function ($table) {
            $table->dropColumn('afeta_custo');
        });
    }
}
