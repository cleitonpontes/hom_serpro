<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Empenhodetalhado extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'empenhodetalhado';
    use SoftDeletes;

    protected $table = 'empenhodetalhado';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'empenho_id',
        'naturezasubitem_id',
//        'empaliquidar',
//        'empemliquidacao',
//        'empaliquidado',
//        'emppago',
//        'empaliqrpnp',
//        'empemliqrpnp',
//        'emprpp',
    ];


    public function empenho()
    {
        return $this->belongsTo(Empenho::class, 'empenho_id');
    }


    public function naturezasubitem()
    {
        return $this->belongsTo(Naturezasubitem::class, 'naturezasubitem_id');
    }



}
