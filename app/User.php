<?php

namespace App;

use App\Models\Unidade;
use Backpack\CRUD\CrudTrait; // <------------------------------- this one
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;// <---------------------- and this one
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'usuario';

    use CrudTrait; // <----- this
    use HasRoles; // <------ and this
    use Notifiable;
    protected $table = 'users';
    protected $guard_name = 'web';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cpf', 'name', 'email', 'password', 'ugprimaria'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function unidades(){

        return $this->belongsToMany(Unidade::class, 'unidade_user', 'user_id', 'unidade_id');

    }
}
