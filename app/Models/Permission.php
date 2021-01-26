<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission as OriginalPermission;

class Permission extends OriginalPermission
{
    use CrudTrait;
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logName = 'permission';

    protected $fillable = ['name', 'guard_name', 'updated_at', 'created_at'];
}
