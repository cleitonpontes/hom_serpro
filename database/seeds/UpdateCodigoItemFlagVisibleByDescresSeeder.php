<?php

use App\Models\Codigoitem;
use Illuminate\Database\Seeder;

class UpdateCodigoItemFlagVisibleByDescresSeeder extends Seeder
{
    public function run()
    {
        Codigoitem::whereRaw('LENGTH(descres) > 2')
                             ->where('codigo_id', '=', 13)
                             ->update(['visivel' => false]);

        Codigoitem::where('codigo_id', '=', 13)
                           ->where('descres', '=', 'NAOSEAPLIC')
                          ->update(['visivel' => true]);
    }
}
