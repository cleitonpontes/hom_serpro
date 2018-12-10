<?php

namespace App\Models;

use App\User;
use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;
use Backpack\CRUD\CrudTrait;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Tightenco\Parental\HasParentModel;

class BackpackUser extends User
{
    use HasParentModel;
    use Notifiable;
    use CrudTrait;
    use HasRoles;
    use LogsActivity;


    protected static $logFillable = true;
    protected static $logName = 'usuario';


    protected $fillable = [
        'cpf',
        'name',
        'email',
        'ugprimaria',
        'password',
        'senhasiafi'
    ];


    protected $table = 'users';

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    public function unidadeprimaria($id)
    {

        $ug = Unidade::find($id);

        return $ug;

    }

    public function getUGPrimaria()
    {
        if($this->ugprimaria){
            $unidade = Unidade::find($this->ugprimaria);
            return $unidade->codigo . ' - ' . $unidade->nomeresumido;
        }else{
            return '-';
        }


    }

    public function unidades()
    {
        return $this->belongsToMany(Unidade::class, 'unidadesusers', 'user_id', 'unidade_id');

    }
}
