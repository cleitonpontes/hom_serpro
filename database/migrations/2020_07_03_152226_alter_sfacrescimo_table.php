<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlteraSfacrescimoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfacrescimo', function (Blueprint $table) {
            $table->dropForeign('sfacrescimo_sfded_id_foreign');
            $table->integer('sfded_id')->nullable()->change();
            $table->integer('sfencargos_id')->nullable()->after('sfded_id');
            $table->integer('sfdadospgto_id')->nullable()->after('sfencargos_id');
            $table->integer('codfontrecur')->nullable()->after('codsubitemempe');
            $table->string('codctgogasto')->nullable()->after('codfontrecur');
        });

        Schema::table('sfacrescimo', function (Blueprint $table) {
            $table->foreign('sfded_id')->references('id')->on('sfdeducao')->onDelete('cascade');
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
            $table->dropColumn('sfdeducao_id');
            $table->dropColumn('codfontrecur');
            $table->dropColumn('codctgogasto');
        });
    }
}
