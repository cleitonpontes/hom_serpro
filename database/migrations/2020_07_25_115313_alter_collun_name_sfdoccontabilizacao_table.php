<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollunNameSfdoccontabilizacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('sfdoccontabilizacao','numcodcont')){
            Schema::table('sfdoccontabilizacao', function (Blueprint $table) {
                $table->dropColumn('numcodcont');
                $table->string('numdoccont')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfdoccontabilizacao', function (Blueprint $table) {
            $table->dropColumn('numdoccont');
            $table->string('numcodcont')->nullable();
        });
    }
}
