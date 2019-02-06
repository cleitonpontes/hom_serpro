<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Sfcertificado extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'sfcertificado';
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sfcertificado';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'certificado',
        'chaveprivada',
        'vencimento',
        'situacao'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'certificado' => 'array',
        'chaveprivada' => 'array'
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
    public function setCertificadoAttribute($value)
    {
        $attribute_name = "certificado";
        $disk = "local";
        $destination_path = "sfcertificado/";

//        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setChaveprivadaAttribute($value)
    {
        $attribute_name = "chaveprivada";
        $disk = "local";
        $destination_path = "sfcertificado/";

//        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }
}
