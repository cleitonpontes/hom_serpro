<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class ApropriacaoFaturas extends Model
{
    use LogsActivity;

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
            ->leftJoin('contratofaturas AS CF', 'CF.id', '=', 'AC.fatura_id')
            ->leftJoin('contratos AS C', 'C.id', '=', 'CF.contrato_id')
            ->leftJoin('codigoitens AS CI', 'CI.id', '=', 'apropriacoes_faturas.fase_id')
            ->leftJoin('fornecedores AS F', 'F.id', '=', 'C.fornecedor_id')
            ->where('C.unidade_id', session()->get('user_ug_id'))
            ->orderBy('apropriacoes_faturas.id', 'desc');
    }

    public function retornaDadosIdentificacao($id)
    {
        return $this->from('apropriacoes_faturas AS A')
            ->join('apropriacoes_faturas_contratofaturas AS X', 'X.apropriacoes_faturas_id', '=', 'A.id')
            ->join('contratofaturas AS F', 'F.id', '=', 'X.contratofaturas_id')
            ->join('contratos AS C', 'C.id', '=', 'F.contrato_id')
            ->join('sfpadrao AS S', 'S.fk', '=', 'F.contrato_id')
            ->join('unidades AS U', 'U.id', '=', 'C.unidade_id')
            ->join('orgaos AS O', 'O.id', '=', 'U.orgao_id')
            ->where('A.id', $id)
            ->select(
                DB::raw('CONCAT("O"."codigo", \' - \', "O"."nome") AS orgao'),
                DB::raw('CONCAT("U"."codigo", \' - \', "U"."nomeresumido", \' - \', "U"."nome") AS unidade'),
                'C.processo',
                'F.ateste',
                DB::raw("' - ' AS doc_origem"),
                'S.decricaopadrao AS observacoes',
                DB::raw("' - ' AS centro_custo"),
                'F.valorliquido AS valor_bruto',
                DB::raw("0 as valor_desconto"),
                'F.valorliquido AS valor_liquido'
            );
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
