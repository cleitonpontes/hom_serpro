<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SfNonce extends Model
{
    use LogsActivity;

    protected $table = 'sfnonce';

    public $timestamps = false;

    protected $fillable = [
        'sf_id', 'tipo'
    ];
}
