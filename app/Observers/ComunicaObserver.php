<?php

namespace App\Observers;

use App\Jobs\NotificaUsuarioComunicaJob;
use App\Models\BackpackUser;
use App\Models\Comunica;
use App\Notifications\ComunicaNotification;
use Illuminate\Database\Eloquent\Builder;
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

        if ($comunica->situacao == 'P') {
            $usuarios = BackpackUser::with('unidade');

            $orgao = $comunica->orgao_id;
            if (!is_null($orgao) && $orgao != '') {
                $usuarios->whereHas('unidade', function (Builder $query) use ($orgao) {
                    $query->where('orgao_id', $orgao);
                });
            }

            $unidade = $comunica->unidade_id;
            if (!is_null($unidade) && $unidade != '') {
                $usuarios->where('ugprimaria', $unidade);
            }

            $users = $usuarios->get();

            foreach ($users as $user) {
                $notifica = $this->deveNotificarUsuario($user, $comunica->role_id);

                if ($notifica) {
                    NotificaUsuarioComunicaJob::dispatch($comunica, $user);
//                    $user->notify(new ComunicaNotification($comunica, $user));
                }
            }

            $comunica->situacao = 'E';
            $comunica->save();
        }
    }

    /**
     * Efetua validação para notificar ou não dado usuário
     *
     * @param BackpackUser $user
     * @param int $perfil
     * @return bool
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function deveNotificarUsuario(BackpackUser $user, $perfil = null)
    {
        $notifica = true;

        if ($perfil) {
            $role = Role::find($perfil);

            if (!$user->hasRole($role->name)) {
                $notifica = false;
            }
        }

        return $notifica;
    }

}
