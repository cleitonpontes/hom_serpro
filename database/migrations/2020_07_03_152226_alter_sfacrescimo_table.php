<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfacrescimoTable extends Migration
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
            $table->dropColumn('sfded_id');
            $table->integer('sfdeducao_id')->nullable()->after('id');
            $table->integer('sfencargos_id')->nullable()->after('sfdeducao_id');
            $table->integer('sfdadospgto_id')->nullable()->after('sfdeducao_id');
            $table->integer('codfontrecur')->nullable()->after('codsubitemempe');
            $table->string('codctgogasto')->nullable()->after('codfontrecur');
            $table->string('tipo')->nullable()->change();
        });

        Schema::table('sfacrescimo', function (Blueprint $table) {
            $table->foreign('sfdeducao_id')->references('id')->on('sfdeducao')->onDelete('cascade');
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
            $table->dropColumn('sfdeducao_id')->nullable()->after('id');
            $table->dropColumn('codfontrecur')->nullable()->after('codsubitemempe');
            $table->dropColumn('codctgogasto')->nullable()->after('codfontrecur');
            $table->string('tipo')->change();
        });
    }
}
