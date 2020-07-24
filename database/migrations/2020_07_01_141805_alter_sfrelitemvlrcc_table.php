<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfrelitemvlrccTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfrelitemvlrcc', function (Blueprint $table) {
            $table->decimal('vlr',15,2)->nullable()->default(0)->change();
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
        Schema::table('sfrelitemvlrcc', function (Blueprint $table) {
            $table->decimal('vlr',15,2)->nullable();
            $table->string('tipo');
        });
    }
}
