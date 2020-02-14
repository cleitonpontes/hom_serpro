<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Saldohistoricoitem extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'saldohistoricoitens';
    use SoftDeletes;


    protected $table = 'saldohistoricoitens';


    protected $fillable = [
        'contratoitem_id',
        'tiposaldo_id',
        'quantidade',
        'valorunitario',
        'valortotal'
    ];

    public function saldoable()
    {
        return $this->morphTo();
    }
}
