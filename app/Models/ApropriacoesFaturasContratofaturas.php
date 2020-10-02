<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApropriacoesFaturasContratofaturas extends Model
{
    public static function retornaFaturasETotais()
    {
        return self::from(
            'apropriacoes_faturas_contratofaturas as A'
        )
            ->leftJoin('contratofaturas AS C', 'C.id', '=', 'A.contratofaturas_id')
            ->select(
                'A.apropriacoes_faturas_id',
                DB::raw('max("A"."contratofaturas_id") as fatura_id'),
                DB::raw('string_agg("C"."numero", ' . "', '" . ') as faturas'),
                DB::raw('sum("C"."valorliquido") as total')
            )
            ->groupBy('A.apropriacoes_faturas_id');
    }
}
