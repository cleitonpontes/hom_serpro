<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Planointerno extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;

    protected static $logFillable = true;
    protected static $logName = 'planointerno';


    protected $table = 'planointerno';

    protected $fillable = [
        'codigo',
        'descricao',
        'situacao',
    ];

    public function empenhos()
    {
        return $this->hasMany(Empenho::class,'planointerno_id');
    }
}
