<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class Empenho extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'empenho';
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'empenhos';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'numero',
        'unidade_id',
        'fornecedor_id',
        'planointerno_id',
        'naturezadespesa_id',
        'empenhado',
        'aliquidar',
        'liquidado',
        'pago',
        'rpinscrito',
        'rpaliquidar',
        'rpliquidado',
        'rppago',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function buscaEmpenhosPorAnoUg(int $ano, int $unidade)
    {
        $empenhos = Empenho::whereHas('unidade', function ($q) use ($unidade){
            $q->where('codigo',$unidade);
        })
            ->where('numero', 'LIKE', $ano . 'NE%')
            ->get();

        return $empenhos;
    }

    public function buscaEmpenhosPorUg(int $unidade)
    {
        $empenhos = Empenho::whereHas('unidade', function ($q) use ($unidade){
            $q->where('codigo',$unidade);
        })
            ->get();

        return $empenhos;
    }

    public function getFornecedor()
    {
        $fornecedor = Fornecedor::find($this->fornecedor_id);
        return $fornecedor->cpf_cnpj_idgener . ' - ' . $fornecedor->nome;

    }

    public function getUnidade()
    {
        $unidade = Unidade::find($this->unidade_id);
        return $unidade->codigo . ' - ' . $unidade->nomeresumido;

    }

    public function getNatureza()
    {
        if ($this->naturezadespesa_id) {

            $naturezadespesa = Naturezadespesa::find($this->naturezadespesa_id);
            return $naturezadespesa->codigo . ' - ' . $naturezadespesa->descricao;

        } else {
            return '';
        }
    }

    public function getPi()
    {
        if ($this->planointerno_id) {
            $planointerno = Planointerno::find($this->planointerno_id);
            return $planointerno->codigo . ' - ' . $planointerno->descricao;
        } else {
            return '-';
        }
    }

    public function formatVlrEmpenhado()
    {
        return 'R$ ' . number_format($this->empenhado, 2, ',', '.');
    }

    public function formatVlraLiquidar()
    {
        return 'R$ ' . number_format($this->aliquidar, 2, ',', '.');
    }

    public function formatVlrLiquidado()
    {
        return 'R$ ' . number_format($this->liquidado, 2, ',', '.');
    }

    public function formatVlrPago()
    {
        return 'R$ ' . number_format($this->pago, 2, ',', '.');
    }

    public function formatVlrRpInscrito()
    {
        return 'R$ ' . number_format($this->rpinscrito, 2, ',', '.');
    }

    public function formatVlrRpaLiquidar()
    {
        return 'R$ ' . number_format($this->rpaliquidar, 2, ',', '.');
    }

    public function formatVlrRpLiquidado()
    {
        return 'R$ ' . number_format($this->rpliquidado, 2, ',', '.');
    }

    public function formatVlrRpPago()
    {
        return 'R$ ' . number_format($this->rppago, 2, ',', '.');
    }

    /**
     * Retorna Empenhos e Fontes conforme $conta informada
     *
     * @param string $conta
     * @return array
     */
    public function retornaEmpenhoFontePorConta($conta)
    {
        // Dados de todos os empenhos - em memória
        $empenhos = session('empenho.fonte.conta');
        $pkCount = (is_array($empenhos) ? count($empenhos) : 0);
        if ($pkCount == 0) {
            // Se não houver dados na session, busca os dados no banco
            $empenhos = $this->retornaEmpenhosFonteConta($conta);
            session(['empenho.fonte.conta' => $empenhos]);
        }


        $registrosEncontrados = array_filter($empenhos, function ($empenho) use ($conta) {

            return ($empenho->nd == $conta);
        });


        return $registrosEncontrados;
    }

    /**
     * Retorna conjunto de Empenhos, fonte e conta (nd + subitem) por $ug
     *
     * @return array
     */
    public function retornaEmpenhosFonteConta()
    {
        $ug = session('user_ug_id');

        $sql = '';
        $sql .= 'SELECT ';
        $sql .= '	E.numero AS ne, ';
        $sql .= "	'000' AS fonte, ";
        $sql .= '	N.codigo || I.codigo AS nd ';
        $sql .= 'FROM';
        $sql .= '	empenhos AS E ';
        $sql .= 'LEFT JOIN ';
        $sql .= '	empenhodetalhado AS D on ';
        $sql .= '	D.empenho_id = E.id ';
        $sql .= 'LEFT JOIN ';
        $sql .= '	naturezasubitem AS I on ';
        $sql .= '	I.id = D.naturezasubitem_id ';
        $sql .= 'LEFT JOIN ';
        $sql .= '	naturezadespesa AS N on ';
        $sql .= '	N.id = I.naturezadespesa_id ';
        $sql .= 'WHERE ';
        $sql .= '	E.unidade_id = ?';
        $sql .= 'ORDER BY ';
        $sql .= '    nd, ';
        $sql .= '    ne ';

        $dados = DB::select($sql, [$ug]);

        return $dados;
    }

    public function retornaDadosEmpenhosGroupUgArray()
    {

        $unidade = Unidade::find(session()->get('user_ug_id'));

        $valores_empenhos = Empenho::whereHas('unidade', function ($q) use ($unidade) {
            $q->whereHas('orgao', function ($o) use ($unidade){
               $o->where('id',$unidade->orgao_id);
            });
            $q->where('situacao', '=', true);
        });
        $valores_empenhos->whereHas('naturezadespesa', function ($q) {
            $q->where('codigo', 'LIKE', '33%');
        });
        $valores_empenhos->leftjoin('unidades', 'empenhos.unidade_id', '=', 'unidades.id');
        $valores_empenhos->orderBy('nome');
        $valores_empenhos->groupBy('unidades.codigo');
        $valores_empenhos->groupBy('unidades.nomeresumido');
        $valores_empenhos->select([
            DB::raw("unidades.codigo ||' - '||unidades.nomeresumido as nome"),
            DB::raw('sum(empenhos.empenhado) as empenhado'),
            DB::raw("sum(empenhos.aliquidar) as aliquidar"),
            DB::raw("sum(empenhos.liquidado) as liquidado"),
            DB::raw("sum(empenhos.pago) as pago")
        ]);

        return $valores_empenhos->get()->toArray();

    }

    public function retornaDadosEmpenhosSumArray()
    {

        $valores_empenhos = Empenho::whereHas('unidade', function ($q) {
            $q->where('situacao', '=', true);
        });
        $valores_empenhos->whereHas('naturezadespesa', function ($q) {
            $q->where('codigo', 'LIKE', '33%');
        });
        $valores_empenhos->leftjoin('unidades', 'empenhos.unidade_id', '=', 'unidades.id');
        $valores_empenhos->select([
//            DB::raw("unidades.codigo ||' - '||unidades.nomeresumido as nome"),
            DB::raw('sum(empenhos.empenhado) as empenhado'),
            DB::raw("sum(empenhos.aliquidar) as aliquidar"),
            DB::raw("sum(empenhos.liquidado) as liquidado"),
            DB::raw("sum(empenhos.pago) as pago")
        ]);

        return $valores_empenhos->get()->toArray();

    }



    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }

    public function naturezadespesa()
    {
        return $this->belongsTo(Naturezadespesa::class, 'naturezadespesa_id');
    }

    public function planointerno()
    {
        return $this->belongsTo(Planointerno::class, 'planointerno_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function getNumeroAliquidarAttribute()
    {
        return $this->numero . ' - ' . $this->aliquidar;
    }

}
