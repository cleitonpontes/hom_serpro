<?php

namespace App\Models;

use App\Models\ContratoBase as Model;
use App\Http\Traits\Formatador;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratopreposto extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;
    use Formatador;

    protected static $logFillable = true;
    protected static $logName = 'contratopreposto';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratopreposto';
    protected $fillable = [
        'contrato_id',
        'user_id',
        'cpf',
        'nome',
        'email',
        'telefonefixo',
        'celular',
        'doc_formalizacao',
        'informacao_complementar',
        'data_inicio',
        'data_fim',
        'situacao',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getContrato()
    {
        return $this->getContratoNumero();
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

    public function getMaskedCpfAttribute($value)
    {
        return $this->retornaMascaraCpf($this->cpf);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
