<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApropriacaoFaturas extends Model
{

    const FASE_EM_ANDAMENTO = 188;
    const FASE_CONCLUIDA = 189;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'apropriacoes_faturas';

    protected $fillable = [
        'valor',
        'fase_id'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function retornaDadosListagem()
    {
        return self::select(
            DB::raw('CONCAT(\'registro_id_\', "apropriacoes_faturas"."id") AS registro_id'),
            'apropriacoes_faturas.id',
            // 'AC.fatura_id',
            'C.numero',
            DB::raw("CONCAT(cpf_cnpj_idgener, ' - ', nome) AS fornecedor"),
            'CF.ateste',
            'CF.vencimento',
            'AC.faturas',
            'AC.total',
            'apropriacoes_faturas.valor',
            'apropriacoes_faturas.fase_id',
            'CI.descricao AS fase'
        )
            ->joinSub(
                ApropriacoesFaturasContratofaturas::retornaFaturasETotais(),
                'AC',
                function ($join) {
                    $join->on('AC.apropriacoes_faturas_id', '=', 'apropriacoes_faturas.id');
                }
            )
            ->join('contratofaturas AS CF', 'CF.id', '=', 'AC.fatura_id')
            ->join('contratos AS C', 'C.id', '=', 'CF.contrato_id')
            ->join('codigoitens AS CI', 'CI.id', '=', 'apropriacoes_faturas.fase_id')
            ->join('fornecedores AS F', 'F.id', '=', 'C.fornecedor_id')
            ->where('C.unidade_id', session()->get('user_ug_id'))
            ->orderBy('apropriacoes_faturas.id', 'desc');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function faturas()
    {
        return $this->belongsToMany(
            'App\Models\Contratofatura',
            'apropriacoes_faturas_contratofaturas',
            'apropriacoes_faturas_id',
            'contratofaturas_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getNumeroFaturasAttribute()
    {
        return implode(
            ', ',
            $this->faturas()->pluck('numero')->toArray()
        );
    }
}
