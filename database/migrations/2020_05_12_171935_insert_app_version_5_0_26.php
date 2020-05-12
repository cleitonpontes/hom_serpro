<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertAppVersion5026 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\AppVersion::create([
            'major' => 5,
            'minor' => 0,
            'patch' => 26
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
            'major' => 5,
            'minor' => 0,
            'patch' => 26
        ])->forceDelete();
    }
}
