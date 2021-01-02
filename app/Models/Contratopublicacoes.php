<?php

namespace App\Models;

use App\Http\Traits\Formatador;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class ContratoPublicacoes extends ContratoBase
{
    use CrudTrait;
    use LogsActivity;
    use Formatador;

    protected static $logFillable = true;
    protected static $logName = 'contratopublicacoes';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratopublicacoes';
    protected $fillable = [
        'contratohistorico_id',
        'data_publicacao',
        'empenho',
        'hash',
        'link_publicacao',
        'log',
        'materia_id',
        'motivo_devolucao',
        'motivo_isencao_id',
        'oficio_id',
        'pagina_publicacao',
        'secao_jornal',
        'situacao',
        'status',
        'status_publicacao_id',
        'texto_dou',
        'texto_rtf',
        'tipo_pagamento_id',
        'transacao_id',
        'cpf'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function retornaPublicacoesEnviadas()
    {

        $status_id = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Situacao Publicacao');
        })
            ->where('descres', '=', '01')
            ->first()->id;

        return $this->whereNotNull('oficio_id')->where('status_publicacao_id', $status_id)->get();
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function contratohistorico()
    {
        return $this->belongsTo(Contratohistorico::class, 'contratohistorico_id');
    }

    public function status_publicacao()
    {
        return $this->belongsTo(Codigoitem::class,'status_publicacao_id');
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

    public function getStatusPublicacaoAttribute()
    {
        return $this->status_publicacao()->first()->descricao;
    }

    public function getTipoPublicacaoAttribute()
    {
        return $this->contratohistorico->tipo->descricao;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

}
