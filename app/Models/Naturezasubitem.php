<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Naturezasubitem extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'naturezasubitem';

    protected $table = 'naturezasubitem';

    public function naturezadespesa()
    {
        return $this->belongsTo(Naturezadespesa::class, 'naturezadespesa_id');
    }


}
