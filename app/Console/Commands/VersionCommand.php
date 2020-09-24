<?php

namespace App\Console\Commands;

use App\Models\AppVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class VersionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contratos:versao
                            {--d|data : Exibe apenas a data de disponibilização da versão}
                            {--numero : Exibe apenas o número da versão}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apresenta informações da mais recente versão do sistema';

    /**
     * Guarda informações da versão
     *
     * @var array
     */
    private $app;

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
        $sistemaNome = config('app.name');
        $this->app = AppVersion::all()->last();

        $versao = $this->app->major . '.' . $this->app->minor . '.' . $this->app->patch;
        $data = Carbon::createFromFormat('Y-m-d H:i:s', $this->app->updated_at);

        $this->comment('### Sistema ' . $sistemaNome);

        if (!$this->option('data') && !$this->option('numero')) {
            $this->input->setOption('numero', true);
            $this->input->setOption('data', true);
        }

        if ($this->option('numero')) {
            $this->line('Versão: ' . $versao);
        }

        if ($this->option('data')) {
            $this->line('Disponibilizada: ' . $data->format('d/m/Y H:i:s'));
        }
    }
}
