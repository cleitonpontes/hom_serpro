<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Contrato extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratos';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'numero',
        'fornecedor_id',
        'unidade_id',
        'categoria_id',
        'processo',
        'objeto',
        'info_complementar',
        'fundamento_legal',
        'modalidade',
        'licitacao_numero',
        'data_assinatura',
        'data_publicacao',
        'vigencia_inicio',
        'vigencia_fim',
        'valor_inicial',
        'valor_global',
        'num_parcelas',
        'valor_parcela',
        'valor_acumulado',
        'situacao_siasg',
        'situacao',
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

    public function getCategoria()
    {
        $categoria = Codigoitem::find($this->categoria_id);

        return $categoria->descricao;

    }

    public function getSituacao()
    {
        if($this->situacao == false){
            return "Inativo";
        }else{
            return "Ativo";
        }
    }

    public function formatVlrParcela()
    {
        return 'R$ '.number_format($this->valor_parcela, 2, ',', '.');
    }

    public function formatVlrGlobal()
    {
        return 'R$ '.number_format($this->valor_global, 2, ',', '.');
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

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