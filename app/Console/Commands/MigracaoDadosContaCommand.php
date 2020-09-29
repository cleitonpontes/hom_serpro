<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\MigracaoSistemaContaController;
use App\Models\AppVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class MigracaoDadosContaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contratos:migracaoconta
                            {--Id= : Informar Id da Migração}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executa Migração de Dados do Sistema Conta e Comprasnet Contratos interno';

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
        if ($this->option('Id')){
            $migracao = new MigracaoSistemaContaController();
            $this->comment('### Migração em andamento!');
            $migracao->index($this->option('Id'));
            $this->line('Jobs de migração criados com sucesso.');
        }

    }
}
