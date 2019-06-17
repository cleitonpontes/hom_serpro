<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratocronograma extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'contrato_cronograma';
    use SoftDeletes;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratocronograma';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getReceitaDespesa()
    {
        if($this->receita_despesa == 'D'){
            return 'Despesa';
        }
        if($this->receita_despesa == 'R'){
            return 'Receita';
        }

        return '';
    }

    public function getContratoNumero()
    {
        $contrato = Contrato::find($this->contrato_id);
        return $contrato->numero;

    }
    public function getContratoHistorico()
    {
        $contratohistorico = Contratohistorico::find($this->contratohistorico_id);

        return $contratohistorico->tipo->descricao . ' - ' . $contratohistorico->numero;

    }
    public function getMesAnoReferencia()
    {
        return $this->mesref . '/' . $this->anoref;

    }

    public function formatVlr()
    {
        return 'R$ '.number_format($this->valor, 2, ',', '.');
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
