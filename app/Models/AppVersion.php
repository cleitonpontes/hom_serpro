<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AppVersion extends Model
{
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'app_version';

    /**
     * Informa que não utilizará os campos create_at e update_at do Laravel
     *
     * @var boolean
     */
    public $timestamps = true;

    protected $table = 'app_version';

    protected $fillable = [
        'major',
        'minor',
        'patch'
    ];



}
