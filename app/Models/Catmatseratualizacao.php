<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Catmatseratualizacao extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'catmatseratualizacao';
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'catmatseratualizacao';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'arquivo',
        'situacao',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getSituacaoCatMatSerAtualizacao()
    {
        if(trim($this->situacao) == 'P'){
            return 'Pendente';
        }

        if(trim($this->situacao) == 'E'){
            return 'Erro';
        }

        if(trim($this->situacao) == 'L'){
            return 'Lido';
        }

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
    public function setArquivoAttribute($value)
    {
        $attribute_name = "arquivo";
        $disk = "local";
        $destination_path = "catmatser";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }
}
