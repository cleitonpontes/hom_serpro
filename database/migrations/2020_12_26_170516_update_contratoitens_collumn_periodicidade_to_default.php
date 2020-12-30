<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateContratoitensCollumnPeriodicidadeToDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('contratoitens')
            ->where('periodicidade', '=', null)
            ->update(['periodicidade' => 1]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('contratoitens')
            ->where('periodicidade', '=',1)
            ->update(['periodicidade' => null]);
    }
}
