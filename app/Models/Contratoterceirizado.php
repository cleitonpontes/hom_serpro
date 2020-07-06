<?php

namespace App\Models;

use App\Http\Traits\Formatador;
use Backpack\CRUD\CrudTrait;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratoterceirizado extends ContratoBase
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;
    use Formatador;

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

    public function getFuncao()
    {
        return $this->funcao->descricao;
    }

    public function getCpf()
    {
        $cpf = '***' . substr($this->cpf,3,9) . '**';

        return $cpf;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getEscolaridade()
    {
        return $this->escolaridade->descricao;
    }

    public function formatVlrSalario()
    {
        return $this->retornaCampoFormatadoComoNumero($this->salario, true);
    }

    public function formatVlrCusto()
    {
        return $this->retornaCampoFormatadoComoNumero($this->custo, true);
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

    public function escolaridade()
    {
        return $this->belongsTo(Codigoitem::class, 'escolaridade_id');
    }

    public function funcao()
    {
        return $this->belongsTo(Codigoitem::class, 'funcao_id');
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
