<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class MinutaEmpenhoRemessa extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected static $logFillable = true;
    protected static $logName = 'minutaempenhos_remessa';

    protected $table = 'minutaempenhos_remessa';

    protected $fillable = [
        'minutaempenho_id',
        'situacao_id',
        'remessa',
        'etapa',
        'sfnonce'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function retornaCompraItemMinutaEmpenho()
    {
        return CompraItemMinutaEmpenho::where('minutaempenho_id', $this->minutaempenho_id)
            ->where('minutaempenhos_remessa_id', $this->id);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function minutaempenho()
    {
        return $this->belongsTo(MinutaEmpenho::class, 'minutaempenho_id');
    }

    public function situacao()
    {
        return $this->belongsTo(Codigoitem::class, 'situacao_id');
    }

    public function contacorrente()
    {
        return $this->hasMany(ContaCorrentePassivoAnterior::class, 'minutaempenhos_remessa_id');
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
