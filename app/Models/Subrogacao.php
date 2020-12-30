<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Subrogacao extends Model
{
    use CrudTrait;
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logName = 'subrogacao';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'subrogacoes';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'unidadeorigem_id',
        'contrato_id',
        'unidadedestino_id',
        'data_termo'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getUnidadeOrigem()
    {
        $unidadeorigem = Unidade::find($this->unidadeorigem_id);

        return $unidadeorigem->codigo . ' - ' . $unidadeorigem->nomeresumido;
    }


    public function getContrato()
    {
        $contrato = Contrato::find($this->contrato_id);

        return $contrato->numero . ' | ' . $contrato->fornecedor->cpf_cnpj_idgener . ' - ' . $contrato->fornecedor->nome;
    }


    public function getUnidadeDestino()
    {
        $unidadedestino = Unidade::find($this->unidadedestino_id);

        return $unidadedestino->codigo . ' - ' . $unidadedestino->nomeresumido;
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function unidadeOrigem()
    {

        return $this->belongsTo(Unidade::class, 'unidadeorigem_id');

    }

    public function contrato()
    {

        return $this->belongsTo(Contrato::class, 'contrato_id');

    }

    public function unidadeDestino()
    {

        return $this->belongsTo(Unidade::class, 'unidadedestino_id');

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
