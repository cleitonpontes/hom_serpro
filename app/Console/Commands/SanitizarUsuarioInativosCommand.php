<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BackpackUser;

class SanitizarUsuarioInativosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SanitizarUsuarioInativos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Colocar todos usuários inativos com ugPrimaria null, apagar unidades segundárias e retirar todas permissões';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $usersSituacaoInativo = BackpackUser::where('situacao', '=', false)->get()->toArray();
        foreach($usersSituacaoInativo as $userSituacaoInativo) {
            $user = BackpackUser::find($userSituacaoInativo['id']);
            $user->ugprimaria = null;
            $user->roles()->detach();
            $user->unidades()->detach();
            $user->save();
        }
    }
}
