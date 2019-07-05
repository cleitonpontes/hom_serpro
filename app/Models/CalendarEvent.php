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

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'unidade_id'
    ];

    public function insertEvents(array $evento)
    {

        $eventoencontrado = $this->consultaEventos($evento);

        if(!$eventoencontrado){
            $this->fill($evento);
            $this->save();
        }

    }

    public function consultaEventos(array $evento)
    {
        $eventoencontrado = $this->where('title','=',$evento['title'])
            ->where('start_date','=',$evento['start_date'])
            ->where('end_date','=',$evento['end_date'])
            ->where('unidade_id','=',$evento['unidade_id'])
            ->first();

        return $eventoencontrado;
    }

    public function unidade()
    {

        return $this->belongsTo(Unidade::class, 'unidade_id');

    }
}
