<?php

use App\Models\Codigoitem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateCodigoItemFlagVisibleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $codigoitem = Codigoitem::where('descres', '=', 'SUPRIMENTO')
            ->update(['visivel' => false]);

    }
}
