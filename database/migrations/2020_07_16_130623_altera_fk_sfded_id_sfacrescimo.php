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

            $table->integer('sfencargos_id')->nullable()->after('sfded_id');
            $table->integer('sfdadospgto_id')->nullable()->after('sfencargos_id');
            $table->integer('codfontrecur')->nullable()->after('codsubitemempe');
            $table->string('codctgogasto')->nullable()->after('codfontrecur');

            $table->foreign('sfencargos_id')->references('id')->on('sfencargo')->onDelete('cascade');
            $table->foreign('sfdadospgto_id')->references('id')->on('sfdadospgto')->onDelete('cascade');
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
            $table->dropForeign('sfacrescimo_sfencargos_id_foreign');
            $table->dropForeign('sfacrescimo_sfdadospgto_id_foreign');
            $table->dropColumn('sfdadospgto_id');
            $table->dropColumn('sfencargos_id');
            $table->dropColumn('codctgogasto');
            $table->dropColumn('codfontrecur');

        });
    }
}
