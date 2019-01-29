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
        'naturezadespesa_id'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
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
            return '';
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

        if (count($empenhos) == 0) {
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
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
}
