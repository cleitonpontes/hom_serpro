<?php

namespace App\Observers;

use App\Jobs\AtualizaSiasgCompraJob;
use App\Models\Siasgcompra;
use App\Models\Siasgcontrato;
use App\XML\ApiSiasg;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SiasgcompraObserver
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function created(Siasgcompra $siasgcompra)
    {
        $this->atualizaSiasgContratos($siasgcompra);

    }

    public function updated(Siasgcompra $siasgcompra)
    {
        $this->atualizaSiasgContratos($siasgcompra);
    }

    public function deleted(Siasgcompra $siasgcompra)
    {
        $this->deletarContratos($siasgcompra);
    }

    private function deletarContratos(Siasgcompra $siasgcompra)
    {
        $contratos = Siasgcontrato::where('compra_id',$siasgcompra->id)
            ->delete();

    }

    private function atualizaSiasgContratos(Siasgcompra $compra)
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
                }else{
                    $busca->situacao = 'Pendente';
                    $busca->save();
                }
            }
        }
    }


}
