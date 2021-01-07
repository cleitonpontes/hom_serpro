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
        $diarioOficial = new DiarioOficialClass();
        
        $data = Carbon::createFromFormat('Y-m-d',$this->argument('dtpublicacao'));

        $status_publicacao_id = $this->retornaIdCodigoItem('Situacao Publicacao', 'A PUBLICAR');

        $arr_contrato_publicacao = ContratoPublicacoes::where('status', 'Pendente')
            ->where('status_publicacao_id', $status_publicacao_id)
            ->whereNotNull('texto_dou')
            ->where('texto_dou','!=','')
            ->get();

        foreach ($arr_contrato_publicacao as $contrato_publicacao) {

            $contrato_publicacao->data_publicacao = $data->toDateString();
            $contrato_publicacao->save();

            $contrato_historico = Contratohistorico::where('id', $contrato_publicacao->contratohistorico_id)->first();
            $diarioOficial->enviarPublicacaoCommand($contrato_historico, $contrato_publicacao);

        }
        dd('Terminou!!');
    }

}
