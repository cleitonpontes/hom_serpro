<?php

namespace App\Http\Controllers;

use App\Models\Codigoitem;
use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\CompraItemUnidade;
use App\Models\Comprasitemunidadecontratoitens;
use App\Models\Contrato;
use App\Models\Unidade;
use App\XML\ApiSiasg;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\CompraTrait;

class TestController extends Controller
{
    use CompraTrait;
    public function contrato()
    {

        $contratos = Contrato::select(
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
            ->take(1000)
            ->get();
//        dd($results->getBindings(),$results->toSql());
        dd($contratos);
        foreach ($contratos as $contrato){

            $compraSiasg = $this->consultaCompraSiasg($contrato);
//            dd($compraSiasg);
            if(isset($compraSiasg->data->compraSispp)) {
                $params = $this->montaParametrosCompra($compraSiasg, $contrato);
                $compra = $this->updateOrCreateCompra($params);

                if ($compraSiasg->data->compraSispp->tipoCompra == 1) {
                    $this->gravaParametroItensdaCompraSISPP($compraSiasg, $compra);
                }

                if ($compraSiasg->data->compraSispp->tipoCompra == 2) {
                    $this->gravaParametroItensdaCompraSISRP($compraSiasg, $compra);
                }

                $this->vincularItemCompraAoItemContrato($contrato,$compra);
            }
        }

        dd('Terminou');

    }

    public function vincularItemCompraAoItemContrato($contrato,$compra)
    {

        foreach ($contrato->itens as $item) {

            $contratoitem_id = $item->id;
            $unidadecompra_id = $contrato->uasgCompra_id;
            $contrato_numero_item_compra = $item->numero_item_compra;

            $itemCompra = $compra->compra_item->where('numero', $contrato_numero_item_compra)->first();

            if (!is_null($itemCompra)) {
                $compra_item_id = $compra->compra_item->where('numero', $contrato_numero_item_compra)->first()->id;
                $compra_item_unidade_id = CompraItemUnidade::where('compra_item_id', $compra_item_id)
                    ->where('unidade_id', $unidadecompra_id)
                    ->select('id')
                    ->first()->id;

                $insert[] = [
                    'compra_item_unidade_id' => $compra_item_unidade_id,
                    'contratoitem_id' => $contratoitem_id
                ];

                $vinculo = Comprasitemunidadecontratoitens::updateOrCreate(
                    [
                        'compra_item_unidade_id' => $compra_item_unidade_id,
                        'contratoitem_id' => $contratoitem_id
                    ],
                    [
                        'compra_item_unidade_id' => $compra_item_unidade_id,
                        'contratoitem_id' => $contratoitem_id
                    ]
                );

            }
        }

    }

    public function consultaCompraSiasg($contrato)
    {
        $apiSiasg = new ApiSiasg();

        $params = [
            'modalidade' => $contrato->modalidade,
            'numeroAno' => $contrato->numeroAno,
            'uasgCompra' => $contrato->uasgUsuario,
            'uasgUsuario' => $contrato->uasgCompra
        ];

        $contrato = json_decode($apiSiasg->executaConsulta('COMPRASISPP', $params));

        return $contrato;
    }


    public function montaParametrosCompra($compraSiasg, $contrato)
    {
        $numero = (substr($contrato->numeroAno,0,5));
        $ano = (substr($contrato->numeroAno,5,4));

        $params = [];
        $unidade_subrogada = $compraSiasg->data->compraSispp->subrogada;

        $params['unidadeorigem_id'] = $contrato->uasgUsuario_id;
        $params['unidade_subrrogada_id'] = ($unidade_subrogada <> '000000') ? (int)$this->buscaIdUnidade($unidade_subrogada) : null;
        $params['modalidade_id'] = $this->buscaModalidade($contrato->modalidade);
        $params['tipo_compra_id'] = $this->buscaTipoCompra($compraSiasg->data->compraSispp->tipoCompra);
        $params['numero_ano'] = $numero."/".$ano;
        $params['inciso'] = $compraSiasg->data->compraSispp->inciso;
        $params['lei'] = $compraSiasg->data->compraSispp->lei;

        return $params;
    }


    public function buscaModalidade($descres)
    {
        $modalidade = Codigoitem::wherehas('codigo', function ($q) {
            $q->where('descricao', '=', 'Modalidade Licitação');
        })
            ->where('descres',$descres)
            ->first();
        return $modalidade->id;
    }

    public function buscaTipoCompra($descres)
    {
        $tipocompra = Codigoitem::wherehas('codigo', function ($q) {
            $q->where('descricao', '=', 'Tipo Compra');
        })
            ->where('descres', '0' . $descres)
            ->first();
        return $tipocompra->id;
    }

    public function updateOrCreateCompra($params)
    {
        $compra = Compra::updateOrCreate(
            [
                'unidade_origem_id' => $params['unidadeorigem_id'],
                'modalidade_id' => (int)$params['modalidade_id'],
                'numero_ano' => $params['numero_ano'],
                'tipo_compra_id' => $params['tipo_compra_id']
            ],
            [
                'unidade_subrrogada_id' => $params['unidade_subrrogada_id'],
                'tipo_compra_id' => $params['tipo_compra_id'],
                'inciso' => $params['inciso'],
                'lei' => $params['lei']
            ]
        );
        return $compra;
    }

    public function buscaIdUnidade($uasg)
    {
        return Unidade::where('codigo', $uasg)->first()->id;
    }

}
