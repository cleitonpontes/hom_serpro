<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class CalendarEvent extends Model
{
    use LogsActivity;
    use SoftDeletes;
    protected static $logFillable = true;
    protected static $logName = 'calendar_events';

    protected $table = 'calendarevents';

    protected $fillable = ['title','start_date','end_date','unidade_id'];

    public function unidade(){

        return $this->belongsTo(Unidade::class, 'unidade_id');

    }
}
