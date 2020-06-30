<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratoterceirizado extends ContratoBase
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;

    protected static $logFillable = true;
    protected static $logName = 'terceirizado';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratoterceirizados';
    protected $fillable = [
        'contrato_id',
        'cpf',
        'nome',
        'funcao_id',
        'descricao_complementar',
        'jornada',
        'unidade',
        'salario',
        'custo',
        'escolaridade_id',
        'data_inicio',
        'data_fim',
        'situacao'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function inserirContratoterceirizadoMigracaoConta(array $dados)
    {
        $this->fill($dados);
        $this->save();

        return $this;
    }

    public function getContrato()
    {
        if($this->contrato_id){
            $contrato = Contrato::find($this->contrato_id);
            return $contrato->numero;
        }else{
            return '';
        }
    }
    public function getFuncao()
    {
        if($this->funcao_id){
            $funcao = Codigoitem::find($this->funcao_id);
            return $funcao->descricao;
        }else{
            return '';
        }
    }

    public function getOrgao()
    {
        $orgao = Orgao::whereHas('unidades', function ($query) {
            $query->where('id', '=', $this->contrato->unidade_id);
        })->first();

        return $orgao->codigo . ' - ' . $orgao->nome;
    }

    public function getUnidade()
    {
        $unidade = Unidade::find($this->contrato->unidade_id);

        return $unidade->codigo . ' - ' . $unidade->nomeresumido;
    }

    public function getFornecedor()
    {
        $fornecedor = Fornecedor::find($this->contrato->fornecedor_id);

        return $fornecedor->cpf_cnpj_idgener . ' - ' . $fornecedor->nome;
    }

    public function getCpf()
    {
        $cpf = '***' . substr($this->cpf,3,9).'**';
        return $cpf;
    }

    public function getNome()
    {
        $nome = $this->nome;
        return $nome;
    }

    public function getEscolaridade()
    {
        if($this->escolaridade_id){
            $escolaridade = Codigoitem::find($this->escolaridade_id);
            return $escolaridade->descricao;
        }else{
            return '';
        }
    }

    public function formatVlrSalario()
    {
        return 'R$ '.number_format($this->salario, 2, ',', '.');
    }

    public function formatVlrCusto()
    {
        return 'R$ '.number_format($this->custo, 2, ',', '.');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    public function funcao()
    {
        return $this->belongsTo(Codigoitem::class, 'funcao_id');
    }

    public function escolaridade()
    {
        return $this->belongsTo(Codigoitem::class, 'escolaridade_id');
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
