<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\ContratoPublicacoes;
use App\Models\Contratohistorico;
use App\Http\Controllers\Publicacao\DiarioOficialClass;
use App\Http\Traits\BuscaCodigoItens;

class EnviarPublicacao extends Command
{
    use BuscaCodigoItens;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'command:name';
    protected $signature = 'publicacao:enviar-publicacao {dtpublicacao=: Data de publicacao}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seta a data de publicacao de contratohistorico e contrato e envia para publicacao';

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
        $this->validarDataCommand($this->argument('dtpublicacao'));
        $status_publicacao_id = $this->retornaIdCodigoItem('Situacao Publicacao', 'A PUBLICAR');

        $arr_contrato_publicacao = ContratoPublicacoes::where('status', 'Pendente')
            ->where('status_publicacao_id', $status_publicacao_id)
            ->get();

        $diarioOficial = new DiarioOficialClass();
        foreach ($arr_contrato_publicacao as $contrato_publicacao) {
            //altera a data de publicacao para a data informada no command
            $contrato_publicacao->data_publicacao = $this->argument('dtpublicacao');
            $contrato_publicacao->save();
            //envia publicacao
            $contrato_historico = Contratohistorico::where('id', $contrato_publicacao->contratohistorico_id)->get()->toArray();
            $diarioOficial->enviarPublicacaoCommand($contrato_publicacao, $contrato_historico);
        }
    }

    private function validarDataCommand($data)
    {
        Carbon::createFromFormat('Y-m-d', $data);
    }
}
