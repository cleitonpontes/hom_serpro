<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlteraFkSfdedIdSfacrescimo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfacrescimo', function (Blueprint $table) {
//            $table->dropForeign('sfacrescimo_sfdeducao_id_foreign');
//            $table->dropColumn('sfdeducao_id');
//            $table->integer('sfded_id')->nullable()->after('tipo');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfacrescimo', function (Blueprint $table) {
//            $table->dropForeign('sfacrescimo_sfded_id_foreign');
        });
    }
}
