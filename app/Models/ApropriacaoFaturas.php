<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApropriacaoFaturas extends Model
{
    protected $table = 'apropriacoes_faturas';

    protected $fillable = [
        'valor',
        'fase_id'
    ];

    public function faturas()
    {
        return $this->belongsToMany(
            'App\Models\Contratofatura',
            'apropriacoes_faturas_contratofaturas',
            'apropriacoes_faturas_id',
            'contratofaturas_id'
        );
    }

    public function getNumeroFaturasAttribute()
    {
        return implode(
            ', ',
            $this->faturas()->pluck('numero')->toArray()
        );
    }

    public static function retornaDadosListagem()
    {
        return self::select(
            'apropriacoes_faturas.id',
            // 'AC.fatura_id',
            'C.numero',
            DB::raw("CONCAT(cpf_cnpj_idgener, ' - ', nome) AS fornecedor"),
            'CF.ateste',
            'CF.vencimento',
            'AC.faturas',
            'AC.total',
            'apropriacoes_faturas.valor',
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
}
