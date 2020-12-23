<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\ImportacaoCrudController;
use App\Http\Traits\Formatador;
use App\Http\Traits\Users;
use App\Models\BackpackUser;
use App\Models\Importacao;
use App\Models\Unidade;
use App\Notifications\PasswordUserNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Spatie\Permission\Models\Role;

class InserirUsuarioEmMassaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Users, Formatador;

    protected $dado;
    protected $dados_importacao;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $dado, Importacao $dados_importacao)
    {
        $this->dado = $dado;
        $this->dados_importacao = $dados_importacao;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $executa = new ImportacaoCrudController();
        $executa->executaInsercaoMassa($this->dado,$this->dados_importacao);
    }


}
