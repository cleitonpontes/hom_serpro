<?php

namespace App\Console\Commands;

use App\Jobs\VinculaItemCompraItemContratoJob;
use App\Models\Contrato;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SanitizacaoVinculoContratoCompras extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contratos:sanitizacao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vincula os itens do contrato aos itens da compra com base do numero do item';

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
        $dados = Contrato::select(
            'contratos.id',
            DB::raw("replace(licitacao_numero,'/','') as \"numeroAno\""),
            'codigoitens.descres as modalidade',
            'unidades.codigo as uasgUsuario',
            'contratos.unidadeorigem_id as uasgUsuario_id',
            'unidadescompra.codigo as uasgCompra',
            'contratos.unidadecompra_id as uasgCompra_id'
        )
            ->join('unidades', 'contratos.unidadeorigem_id', '=', 'unidades.id')
            ->join(DB::raw('unidades unidadescompra') , 'contratos.unidadecompra_id', '=', 'unidadescompra.id')
            ->join('codigoitens', 'codigoitens.id', '=', 'contratos.modalidade_id')
            ->get()->toArray();

        $this->comment('### Sanitização em andamento!');

        foreach ($dados as $dado) {
            VinculaItemCompraItemContratoJob::dispatch($dado)->onQueue('siasgcompra');
        }

        $this->line('Jobs de Sanitização criados.');

    }
}
