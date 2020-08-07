<?php

namespace App\Models;

use App\User;
use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
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
    use SoftDeletes;

    protected static $logFillable = true;
    protected static $logName = 'usuario';

    protected $fillable = [
        'cpf',
        'name',
        'email',
        'ugprimaria',
        'password',
        'senhasiafi',
        'situacao',
        'acessogov'
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

    public function havePermissionUg($id)
    {

        $ugprimaria = $this->where('ugprimaria', '=', $id)->where('id', '=', $this->id)->first();
        $ugsecundaria = $this->whereHas('unidades', function ($query) use ($id) {
            $query->where('unidade_id', '=', $id);
        })->where('id', '=', $this->id)->first();

        if ($ugprimaria or $ugsecundaria) {
            return true;
        }
        return false;
    }

    public function unidadeprimaria($id)
    {
        $ug = Unidade::find($id);
        return $ug;

    }

    public function getUGPrimaria()
    {
        $retorno = '-';

        if ($this->ugprimaria) {
            $unidade = Unidade::find($this->ugprimaria);
            $retorno = $unidade->codigo . ' - ' . $unidade->nomeresumido;
        }

        return $retorno;
    }

    /**
     * Retorna, segundo relacionamento, único registro da tabela Unidades
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function unidade()
    {
        return $this->hasOne(Unidade::class, 'id', 'ugprimaria');
    }

    /**
     * Retorna, segundo relacionamento, todos os registros em UnidadesUsers
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function unidades()
    {
        return $this->belongsToMany(Unidade::class, 'unidadesusers', 'user_id', 'unidade_id');
    }

    /**
     * Retorna, segundo relacionamento, único registro da tabela Unidades
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @todo Rever nomenclatura deste método. Se for o caso, prefira o método $this->unidade()
     */
    public function ugPrimariaRelation()
    {
        return $this->belongsTo(Unidade::class, 'ugprimaria');
    }

    public function contratoSfPadrao()
    {
        return $this->hasMany(Contratosfpadrao::class, 'user_id');
    }

}
