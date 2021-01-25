<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as OriginalRole;

class Role extends OriginalRole
{
    use CrudTrait;
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logName = 'roles';

    protected $fillable = ['name', 'guard_name', 'updated_at', 'created_at'];
}
