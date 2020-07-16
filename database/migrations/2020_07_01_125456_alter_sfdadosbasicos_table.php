<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfdadosbasicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfdadosbasicos', function (Blueprint $table) {
            $table->integer('codugpgto')->nullable()->default(0)->change();
            $table->decimal('vlr',15,2)->nullable()->default(0)->change();
            $table->decimal('vlrtaxacambio',15,2)->nullable()->default(0)->change();
            $table->string('codcredordevedor')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfdadosbasicos', function (Blueprint $table) {
            $table->integer('codugpgto')->nullable();
            $table->decimal('vlr',15,2)->nullable();
            $table->decimal('vlrtaxacambio',15,2)->nullable();
            $table->string('codcredordevedor');
        });
    }
}
