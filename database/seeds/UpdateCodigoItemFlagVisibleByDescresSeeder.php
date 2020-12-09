<?php

use App\Models\Codigoitem;
use Illuminate\Database\Seeder;

class UpdateCodigoItemFlagVisibleByDescresSeeder extends Seeder
{
    public function run()
    {
        $codigoitem = Codigoitem::whereRaw('LENGTH(descres) <= 2')
                             ->where('codigo_id', '=', 13)
                             ->orWhere('descres', '=', 'NAOSEAPLIC')
                             ->update(['visivel' => true]);

        $codigoitem = Codigoitem::whereRaw('LENGTH(descres) > 2')
                           ->where('descres', '<>', 'NAOSEAPLIC')
                          ->update(['visivel' => false]);
    

    }
}
