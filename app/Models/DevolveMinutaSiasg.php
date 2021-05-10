<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class DevolveMinutaSiasg extends Model
{
    use CrudTrait;
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logName = 'devolve_minuta_siasg';

    protected $table = 'devolve_minuta_siasg';
    protected $fillable = [
        'minutaempenho_id',
        'situacao',
        'alteracao',
        'mensagem_siasg',
        'minutaempenhos_remessa_id'
    ];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getMinutaEmpenho(){
        if (!$this->minutaempenho_id) {
            return '';
        } else {
            return $this->minutaempenho->descricao;
        }
    }
    public function getMinutaEmpenhoRemessa(){
        if (!$this->minutaempenhos_remessa_id) {
            return '';
        } else {
            return $this->minutaempenhos_remessa->remessa;
        }
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function minutaempenho()
    {
        return $this->belongsTo('App\Models\MinutaEmpenho', 'minutaempenho_id');
    }
    public function minutaempenhos_remessa()
    {
        return $this->belongsTo('App\Models\MinutaEmpenhoRemessa', 'minutaempenhos_remessa_id');
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
