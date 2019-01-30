<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Naturezadespesa extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'naturezadespesa';
    use SoftDeletes;

    protected $table = 'naturezadespesa';

    public function naturezasubitem(){

        return $this->hasMany(Naturezasubitem::class, 'naturezasubitem_id');

    }


}
