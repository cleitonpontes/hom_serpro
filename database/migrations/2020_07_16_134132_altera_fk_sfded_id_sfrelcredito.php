<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlteraFkSfdedIdSfrelcredito extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfrelcredito', function (Blueprint $table) {
            $table->dropForeign('sfrelcredito_sfdeducao_id_foreign');
            $table->dropColumn('sfdeducao_id');
            $table->integer('sfded_id')->nullable();
        });

        Schema::table('sfrelcredito', function (Blueprint $table) {
            $table->foreign('sfded_id')->references('id')->on('sfdeducao')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfrelcredito', function (Blueprint $table) {
            $table->dropForeign('sfrelcredito_sfded_id_foreign');
            $table->dropColumn('sfded_id');
            $table->integer('sfdeducao_id')->nullable();
        });
        Schema::table('sfrelcredito', function (Blueprint $table) {
            $table->foreign('sfdeducao_id')->references('id')->on('sfdeducao')->onDelete('cascade');
        });
    }
}
