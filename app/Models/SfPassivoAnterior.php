<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class SfPassivoAnterior extends Model
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
    protected static $logName = 'sfpassivoanterior';

    protected $table = 'sfpassivoanterior';

    protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'sforcempenhodado_id',
        'codcontacontabil'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function sforcempenhodados()
    {
        return $this->belongsTo(SfOrcEmpenhoDados::class, 'sforcempenhodado_id');
    }

    public function passivos_permanentes()
    {
        return $this->hasMany(SfPassivoPermanente::class, 'sfpassivoanterior_id');
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
