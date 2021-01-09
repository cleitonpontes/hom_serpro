<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertAppVersion120 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\AppVersion::create([
            'major' => 1,
            'minor' => 2,
            'patch' => 0
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\AppVersion::where([
            'major' => 1,
            'minor' => 2,
            'patch' => 0
        ])->forceDelete();
    }
}
