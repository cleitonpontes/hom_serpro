<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratoresponsavel extends ContratoBase
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;

    protected static $logFillable = true;
    protected static $logName = 'responsavel';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratoresponsaveis';
    protected $fillable = [
        'contrato_id',
        'user_id',
        'funcao_id',
        'instalacao_id',
        'portaria',
        'situacao',
        'data_inicio',
        'data_fim',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function inserirContratoresponsavelMigracaoConta(array $dados)
    {
        $this->fill($dados);
        $this->save();

        return $this;
    }

    public function getContrato()
    {
        return $this->getContratoNumero();
    }

    public function getUser()
    {
        $usuarioCpf = $this->user->cpf;
        $usuarioNome = $this->user->name;

        return $usuarioCpf . ' - ' . $usuarioNome;
    }

    public function getFuncao()
    {
        return $this->funcao->descricao;
    }

    public function getInstalacao()
    {
        return ($this->instalacao) ? $instalacao = $this->instalacao->nome : '';
    }

    /**
     * Retorna a Data de Início
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getDataInicio()
    {
        return $this->retornaDataAPartirDeCampo($this->data_inicio);
    }

    /**
     * Retorna a Data de Início
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getDataFim()
    {
        return $this->retornaDataAPartirDeCampo($this->data_fim);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }
    */

    public function user()
    {
        return $this->belongsTo(BackpackUser::class, 'user_id');
    }

    public function funcao()
    {
        return $this->belongsTo(Codigoitem::class, 'funcao_id');
    }

    public function instalacao()
    {
        return $this->belongsTo(Instalacao::class, 'instalacao_id');
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
