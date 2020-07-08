<?php

namespace App\Observers;

use App\Models\Siasgcompra;
use App\Models\Siasgcontrato;
use App\XML\ApiSiasg;

class SiasgcompraObserver
{

    public function created(Siasgcompra $siasgcompra)
    {
        $this->importacao($siasgcompra);
    }

    public function updated(Siasgcompra $siasgcompra)
    {
        $this->importacao($siasgcompra);
    }

    private function importacao(Siasgcompra $siasgcompra)
    {
        $tipoconsulta = 'Compra';

        $apiSiasg = new ApiSiasg;
        $dado = [
            'ano' => $siasgcompra->ano,
            'modalidade' => $siasgcompra->modalidade->descres,
            'numero' => $siasgcompra->numero,
            'uasg' => $siasgcompra->unidade->codigosiasg
        ];

        $retorno = $apiSiasg->executaConsulta($tipoconsulta, $dado);

        $compra = $siasgcompra->atualizaJsonMensagemSituacao($siasgcompra->id, $retorno);

        $contratos = $this->atualizaSiasgContratos($compra);

    }

    private function atualizaSiasgContratos(Siasgcompra $compra)
    {
        $contrato = '';

        if($compra->situacao == 'Importado'){
            $json = json_decode($compra->json);
            $dado = [];
            foreach ($json->data as $data){
                $contrato = new Siasgcontrato;
                $tipo_id = $contrato->buscaIdTipo(substr($data,6,2));

                $unidade = substr($data,0,6);
                $numero = substr($data,8,5);
                $ano = substr($data,13,4);
                $unidadesubrrogacao = substr($data,17,6);

                $busca = $contrato->where('unidade', $unidade)
                    ->where('tipo_id', $tipo_id)
                    ->where('numero', $numero)
                    ->where('ano', $ano)
                    ->first();

                if(!isset($busca->id)){
                    $contrato->fill([
                        'compra_id' => $compra->id,
                        'unidade' => $unidade,
                        'tipo_id' => $tipo_id,
                        'numero' =>  $numero,
                        'ano' => $ano,
                        'unidadesubrrogacao' => $unidadesubrrogacao,
                        'situacao' => 'Pendente',
                    ]);
                    $contrato->save();
                }
            }
        }

        return $contrato;
    }


}
