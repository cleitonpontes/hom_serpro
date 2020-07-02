<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfpredocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfpredoc', function (Blueprint $table) {
            $table->decimal('vlrrctabrutaacum',15,2)->nullable()->default(0)->change();
            $table->decimal('vlrpercentual',15,2)->nullable()->default(0)->change();
            $table->integer('codugtmdrserv')->nullable()->default(0)->change();
            $table->integer('numnf')->nullable()->default(0)->change();
            $table->integer('numsubserienf')->nullable()->default(0)->change();
            $table->integer('codmuninf')->nullable()->default(0)->change();
            $table->decimal('vlrnf',15,2)->nullable()->default(0)->change();
            $table->decimal('numaliqnf',15,2)->nullable()->default(0)->change();
            $table->integer('codugfavorecida')->nullable()->default(0)->change();
            $table->integer('numreferencia')->nullable()->default(0)->change();
            $table->decimal('vlrdocumento',15,2)->nullable()->default(0)->change();
            $table->decimal('vlrdesconto',15,2)->nullable()->default(0)->change();
            $table->decimal('vlroutrdeduc',15,2)->nullable()->default(0)->change();
            $table->integer('codrecolhimento')->nullable()->default(0)->change();
            $table->string('tipo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfpredoc', function (Blueprint $table) {
            $table->decimal('vlrrctabrutaacum',15,2)->nullable();
            $table->decimal('vlrpercentual',15,2)->nullable();
            $table->integer('codugtmdrserv')->nullable();
            $table->integer('numnf')->nullable();
            $table->integer('numsubserienf')->nullable();
            $table->integer('codmuninf')->nullable();
            $table->decimal('vlrnf',15,2)->nullable();
            $table->decimal('numaliqnf',15,2)->nullable();
            $table->integer('codugfavorecida')->nullable();
            $table->integer('numreferencia')->nullable();
            $table->decimal('vlrdocumento',15,2)->nullable();
            $table->decimal('vlrdesconto',15,2)->nullable();
            $table->decimal('vlroutrdeduc',15,2)->nullable();
            $table->integer('codrecolhimento')->nullable();
            $table->string('tipo');
        });
    }
}
