<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApropriacoesFaturasContratofaturas extends Model
{

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

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

    public static function retornaContratosDaApropricao($apropriacaoId)
    {
        return self::select('C.contrato_id')
            ->from('apropriacoes_faturas_contratofaturas as F')
            ->leftJoin('contratofaturas as C', 'C.id', '=', 'F.contratofaturas_id')
            ->where('F.apropriacoes_faturas_id', $apropriacaoId)
            ->distinct()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function apropriacoes()
    {
        return $this->belongsToMany(
            ApropriacaoFaturas::class,
            'apropriacoes_faturas',
            'id',
            'apropriacoes_faturas_id'
        );
    }

    public function faturas()
    {
        return $this->belongsToMany(
            Contratofatura::class,
            'contratofaturas',
            'id',
            'contratofaturas_id'
        );
    }
}
