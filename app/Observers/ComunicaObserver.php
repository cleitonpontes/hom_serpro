<?php

namespace App\Observers;

use App\Models\BackpackUser;
use App\Models\Comunica;
use App\Notifications\ComunicaNotification;
use Spatie\Permission\Models\Role;

class ComunicaObserver
{

    /**
     * Handle the comunica "created" event.
     *
     * @param  \App\Models\Comunica $comunica
     * @return void
     */
    public function created(Comunica $comunica)
    {
        $this->disparaNotificacao($comunica);
    }

    /**
     * Handle the comunica "updated" event.
     *
     * @param  \App\Models\Comunica $comunica
     * @return void
     */
    public function updated(Comunica $comunica)
    {
        $this->disparaNotificacao($comunica);
    }

    /**
     * Handle the comunica "deleted" event.
     *
     * @param  \App\Models\Comunica $comunica
     * @return void
     */
    public function deleted(Comunica $comunica)
    {
        //
    }

    /**
     * Handle the comunica "restored" event.
     *
     * @param  \App\Models\Comunica $comunica
     * @return void
     */
    public function restored(Comunica $comunica)
    {
        //
    }

    /**
     * Handle the comunica "force deleted" event.
     *
     * @param  \App\Models\Comunica $comunica
     * @return void
     */
    public function forceDeleted(Comunica $comunica)
    {
        //
    }

    /**
     * Dispara notificação para os usuários correspondentes, conforme $comunica
     *
     * @param Comunica $comunica
     */
    public function disparaNotificacao(Comunica $comunica)
    {

        $situacao = $comunica->situacao;
        $orgao = $comunica->orgao();
        $unidade = $comunica->unidade();

        dd($situacao, $orgao, $unidade);

        if ($comunica->situacao != 'P') {
            return false;
        }














        if ($comunica->situacao == 'P') {

            $users = BackpackUser::all();
            if ($comunica->unidade_id) {
                $ug = $comunica->unidade_id;
                $users->where('ugprimaria', $comunica->unidade_id)
                    ->orWhereHas('unidades', function ($q) use ($ug) {
                        $q->where('id', '=', $ug);
                    })
                    ->get();
            }

            if ($comunica->role_id) {
                $role = Role::find($comunica->role_id);
            }

            foreach ($users as $user) {
                if ($comunica->role_id) {
                    if ($user->hasRole($role->name)) {
                        $user->notify(new ComunicaNotification($comunica,$user));
                    }
                } else {
                    $user->notify(new ComunicaNotification($comunica,$user));
                }
            }
            $comunica->situacao = 'E';
            $comunica->save();
        }
    }

}
