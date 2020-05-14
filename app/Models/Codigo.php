<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Codigo extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;

    protected static $logFillable = true;
    protected static $logName = 'codigo';

    const CODIGO_TIPO_UNIDADE = 1;
    const CODIGO_ITENS_SIAFI = 2;
    const CODIGO_TIPO_PREDOC = 3;
    const CODIGO_TIPO_DOM_BANCARIO = 4;
    const CODIGO_TIPO_REL_ITEM = 5;
    const CODIGO_TIPO_REL_ITEM_VALOR = 6;
    const CODIGO_CATEGORIAS_DOCS = 7;
    const CODIGO_TIPO_GARANTIA = 8;
    const CODIGO_TIPO_FORNECEDOR = 9;
    const CODIGO_FUNÇAO_CONTRATO = 10;
    const CODIGO_CATEGORIA_CONTRATO = 11;
    const CODIGO_TIPO_DE_CONTRATO = 12;
    const CODIGO_MODALIDADE_LICITACAO = 13;
    const CODIGO_ESCOLARIDADE = 14;
    const CODIGO_MAO_DE_OBRA = 15;
    const CODIGO_SITUACAO_OCORRENCIA = 16;
    const CODIGO_TIPO_ARQUIVOS_CONTRATO = 17;
    const CODIGO_ABAS_SITUACOES = 18;
    const CODIGO_TIPO_MATERIAL_SERVICO = 19;
    const CODIGO_TIPO_SALDO_ITENS = 20;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'codigos';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['descricao', 'visivel'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getVisivel()
    {
        if ($this->visivel == true) {
            $visivel = 'Sim';
        } else {
            $visivel = 'Não';
        }

        return $visivel;
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
