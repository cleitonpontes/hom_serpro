<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfrelitemdespanularTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfrelitemdespanular', function (Blueprint $table) {
            $table->decimal('vlr', 17, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfrelitemdespanular', function (Blueprint $table) {
            $table->decimal('vlr', 8, 2)->nullable()->change();
        });
    }
}
