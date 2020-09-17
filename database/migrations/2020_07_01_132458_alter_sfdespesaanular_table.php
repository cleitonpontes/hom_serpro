<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfdespesaanularTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfdespesaanular', function (Blueprint $table) {
            $table->bigInteger('numseqitem')->nullable()->default(0)->change();
            $table->integer('codugempe')->nullable()->default(0)->change();
            $table->integer('numclassd')->nullable()->default(0)->change();
            $table->integer('numclasse')->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfdespesaanular', function (Blueprint $table) {
            $table->bigInteger('numseqitem')->nullable();
            $table->integer('codugempe')->nullable();
            $table->integer('numclassd')->nullable();
            $table->integer('numclasse')->nullable();
        });
    }
}
