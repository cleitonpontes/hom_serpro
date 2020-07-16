<?php

namespace App\Jobs;

use App\Models\Siasgcompra;
use App\Models\Siasgcontrato;
use App\XML\ApiSiasg;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AtualizaSiasgCompraJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $siasgcompra;
    protected $tipoconsulta;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Siasgcompra $siasgcompra)
    {
        $this->siasgcompra = $siasgcompra;
        $this->tipoconsulta = 'Compra';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $apiSiasg = new ApiSiasg;
        $dado = [
            'ano' => $this->siasgcompra->ano,
            'modalidade' => $this->siasgcompra->modalidade->descres,
            'numero' => $this->siasgcompra->numero,
            'uasg' => $this->siasgcompra->unidade->codigosiasg
        ];

        $retorno = $apiSiasg->executaConsulta($this->tipoconsulta, $dado);

        $compra = $this->siasgcompra->atualizaJsonMensagemSituacao($this->siasgcompra->id, $retorno);

        $contratos = $this->inserirSiasgContratos($compra);

    }

    private function inserirSiasgContratos(Siasgcompra $compra)
    {
        $contrato = '';

        if ($compra->situacao == 'Importado') {
            $json = json_decode($compra->json);
            $dado = [];
            foreach ($json->data as $data) {
                $contrato = new Siasgcontrato;
                $unidade_id = $contrato->buscaIdUnidade(substr($data, 0, 6));
                $tipo_id = $contrato->buscaIdTipo(substr($data, 6, 2));
                $unidadesubrrogacao_id = $contrato->buscaIdUnidade(substr($data, 17, 6));

                $numero = substr($data, 8, 5);
                $ano = substr($data, 13, 4);

                $busca = $contrato->where('unidade_id', $unidade_id)
                    ->where('tipo_id', $tipo_id)
                    ->where('numero', $numero)
                    ->where('ano', $ano)
                    ->first();

                $mensagem = '';
                if($unidade_id == null){
                    $mensagem = 'Unidade '.substr($data, 0, 6).' Não Cadastrada';
                }

                if($unidadesubrrogacao_id == null){
                    $mensagem .= ' | Unidade Subrrogação '.substr($data, 17, 6).' Não Cadastrada';
                }

                if($unidadesubrrogacao_id == 'sem'){
                    $unidadesubrrogacao_id = null;
                }

                if (!isset($busca->id)) {
                    $contrato->fill([
                        'compra_id' => $compra->id,
                        'unidade_id' => $unidade_id,
                        'tipo_id' => $tipo_id,
                        'numero' => $numero,
                        'ano' => $ano,
                        'mensagem' => $mensagem,
                        'unidadesubrrogacao_id' => $unidadesubrrogacao_id,
                        'situacao' => ($mensagem != '') ? 'Erro' : 'Pendente',
                    ]);
                    $contrato->save();
                }
            }
        }

        return $contrato;
    }
}
