<?php

namespace App\Jobs;

use App\Http\Traits\CompraTrait;
use App\Models\Codigoitem;
use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\CompraItemUnidade;
use App\Models\Comprasitemunidadecontratoitens;
use App\Models\Contrato;
use App\Models\Unidade;
use App\XML\ApiSiasg;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class VinculaItemCompraItemContratoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use CompraTrait;

    /**
     * @var Contrato
     */
    private $contrato;

    private $dados;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dados)
    {
        $this->contrato = Contrato::find($dados['id']);
        $this->dados = $dados;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $compraSiasg = $this->consultaCompraSiasg($this->dados);

        DB::beginTransaction();
        try {
        if(isset($compraSiasg->data->compraSispp)) {
                $params = $this->montaParametrosCompra($compraSiasg, $this->dados);

                $compra = $this->updateOrCreateCompra($params);

                if ($compraSiasg->data->compraSispp->tipoCompra == 1) {
                    $this->gravaParametroItensdaCompraSISPPCommand($compraSiasg, $compra);
                }

                if ($compraSiasg->data->compraSispp->tipoCompra == 2) {
                    $this->gravaParametroItensdaCompraSISRPCommand($compraSiasg, $compra);
                }

                $this->vincularItemCompraAoItemContrato($this->contrato,$this->dados,$compra);
            }
            DB::commit();

        } catch (Exception $exc) {
            dd($exc);
            DB::rollback();
        }
    }



    public function vincularItemCompraAoItemContrato($contrato,$dados,$compra)
    {

        foreach ($contrato->itens as $item) {

            $contratoitem_id = $item->id;
            $unidadecompra_id = $dados['uasgCompra_id'];
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

    public function consultaCompraSiasg($dados)
    {
        $apiSiasg = new ApiSiasg();

        $params = [
            'modalidade' => $dados['modalidade'],
            'numeroAno' => $dados ['numeroAno'],
            'uasgCompra' => $dados ['uasgUsuario'],
            'uasgUsuario' => $dados ['uasgCompra']
        ];

        $compra = json_decode($apiSiasg->executaConsulta('COMPRASISPP', $params));

        return $compra;
    }


    public function montaParametrosCompra($compraSiasg, $dados)
    {
        $numero = (substr($dados['numeroAno'],0,5));
        $ano = (substr($dados['numeroAno'],5,4));

        $params = [];
        $unidade_subrogada = $compraSiasg->data->compraSispp->subrogada;

        $params['unidadeorigem_id'] = $dados['uasgUsuario_id'];
        $params['unidade_subrrogada_id'] = ($unidade_subrogada <> '000000') ? (int)$this->buscaIdUnidade($unidade_subrogada) : null;
        $params['modalidade_id'] = $this->buscaModalidade($dados['modalidade']);
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
