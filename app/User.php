<?php

namespace App;

use Backpack\CRUD\CrudTrait; // <------------------------------- this one
use Spatie\Permission\Traits\HasRoles;// <---------------------- and this one
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use CrudTrait; // <----- this
    use HasRoles; // <------ and this
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
