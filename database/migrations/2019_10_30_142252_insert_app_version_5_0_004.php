<?php

use App\Models\AppVersion;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertAppVersion50004 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        AppVersion::create([
            'major' => 5,
            'minor' => 0,
            'patch' => 4
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        AppVersion::where([
            'major' => 5,
            'minor' => 0,
            'patch' => 4
        ])->forceDelete();
    }
}
