<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Codigo extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;

    const TIPO_UNIDADE = 1;
    const ITENS_SIAFI = 2;
    const TIPO_PREDOC = 3;
    const TIPO_DOMICILIO_BANCARIO = 4;
    const TIPO_REL_ITEM = 5;
    const TIPO_REL_ITEM_VALOR = 6;
    const CATEGORIAS_DOCS_PADROES = 7;
    const TIPO_GARANTIA = 8;
    const TIPO_FORNECEDOR = 9;
    const FUNÇAO_CONTRATO = 10;
    const CATEGORIA_CONTRATO = 11;
    const TIPO_DE_CONTRATO = 12;
    const MODALIDADE_LICITACAO = 13;
    const ESCOLARIDADE = 14;
    const MAO_DE_OBRA = 15;
    const SITUACAO_OCORRENCIA = 16;
    const TIPO_ARQUIVOS_CONTRATO = 17;
    const ABAS_SITUACOES = 18;
    const TIPO_MATERIAL_SERVICO = 19;
    const TIPO_SALDO_ITENS = 20;
    const TIPO_IMPORTACAO = 21;
    const SITUACAO_ARQUIVO = 22;
    const TIPO_DESPESA_ACESSORIA = 23;
    const RECORRENCIA_DESPESA_ACESSORIA = 24;
    const STATUS_PROCESSO = 25;
    const REGIOES_PAIS = 26;
    const AMPARO_LEGAL_RESTRICOES = 27;
    const APROPRIACAO_FATURA_FASES = 28;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected static $logFillable = true;
    protected static $logName = 'codigo';

    protected $table = 'codigos';
    protected $fillable = ['descricao', 'visivel'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getVisivel()
    {
        return ($this->visivel == true) ? 'Sim' : 'Não';
    }

    public function codigoItens($crud = false)
    {
        $button = '<div class="btn-group">
                        <button type="button" title="Mais" class="btn btn-xs btn-default dropdown-toggle dropdown-toggle-split"
                            data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-gears"></i>
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                            <ul class="dropdown-menu" >
                                <li><a href="/admin/codigo/' . $this->id . '/codigoitem">Itens</a></li>
                            </ul>
                    </div>';

//        return '<a class="btn btn-xs btn-default" href="/admin/codigo/'.$this->id.'/codigoitem"
//        data-toggle="tooltip" title="Código Itens"><i class="fa fa-navicon"></i> Itens</a>';

        return $button;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function codigoitem()
    {
        return $this->hasMany(Codigoitem::class, 'codigo_id');
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
