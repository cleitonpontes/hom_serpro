<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateCodigoSiasgUnidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unidades = \App\Models\Unidade::where('codigosiasg', '=', '')
            ->update(['codigosiasg' => DB::raw("codigo")]);

    }
}
