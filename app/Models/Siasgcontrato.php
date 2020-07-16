<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Siasgcontrato extends Model
{
    use CrudTrait;
    use LogsActivity;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected static $logFillable = true;
    protected static $logName = 'siasgcontratos';

    protected $table = 'siasgcontratos';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'compra_id',
        'unidade_id',
        'tipo_id',
        'numero',
        'ano',
        'codigo_interno',
        'unidadesubrrogacao_id',
        'mensagem',
        'situacao',
        'json',
        'sisg',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function atualizaJsonMensagemSituacao(int $id, string $json)
    {
        $json_var = json_decode($json);
        $situacao = ($json_var->messagem != 'Sucesso') ? 'Erro' : 'Importado';

        $siasgcontrato = $this->find($id);
        $siasgcontrato->json = $json;
        $siasgcontrato->mensagem = $json_var->messagem;
        $siasgcontrato->situacao = $situacao;
        $siasgcontrato->save();

        return $siasgcontrato;
    }

    public function buscaContratosPendentes()
    {
        return $this->where('situacao', 'Pendente')->get();
    }


    public function buscaIdUnidade(string $codigo)
    {
        $unidade = Unidade::where('codigosiasg', $codigo)
            ->first();

        if (!isset($unidade->id)) {
            if ($codigo == '000000') {
                return 'sem';
            }
            return null;
        }

        return $unidade->id;
    }


    public function buscaIdTipo(string $tipo)
    {
        $codigoitem = Codigoitem::whereHas('codigo', function ($c) {
            $c->where('descricao', 'Tipo de Contrato');
        })
            ->where('descres', $tipo)
            ->first();

        return $codigoitem->id;
    }

    public function getUnidade()
    {
        if (!$this->unidade_id) {
            return '';
        }

        return $this->unidade->codigosiasg . ' - ' . $this->unidade->nomeresumido;
    }

    public function getContratoVinculado()
    {
        if (!$this->contrato_id) {
            return '';
        }

        return $this->contrato->numero . ' | ' . $this->contrato->fornecedor->cpf_cnpj_idgener . ' - ' . $this->contrato->fornecedor->nome;
    }

    public function getUnidadeSubrrogada()
    {
        if (!$this->unidadesubrrogacao_id) {
            return '';
        }

        return $this->unidadesubrrogacao->codigosiasg . ' - ' . $this->unidadesubrrogacao->nomeresumido;
    }

    public function getTipo()
    {
        return $this->tipo->descres . ' - ' . $this->tipo->descricao;
    }

    public function getCompra()
    {
        if (!$this->compra_id) {
            return '';
        }

        return $this->compra->unidade->codigosiasg . ' | ' . $this->compra->numero . '/' . $this->compra->ano;
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function compra()
    {
        return $this->belongsTo(Siasgcompra::class, 'compra_id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
    }

    public function unidadesubrrogacao()
    {
        return $this->belongsTo(Unidade::class, 'unidadesubrrogacao_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_id');
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
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
