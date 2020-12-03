<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contrato;
use App\XML\ApiSiasg;
use App\Models\Siasgcompra;
use App\Models\Unidade;
use App\Models\Codigoitem;

class SanitizarComprasContratos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SanitizarComprasContratos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sanitizar os dados de contratoitens de acordo com a API (ContratoSiasg)';

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
        try{
            $arrContrato =  $this->consultarContrato();
            $apiSiasg = new ApiSiasg();

            /*$arrContratoTeste = [
                $arrContrato[234],
                $arrContrato[934],
                $arrContrato[1647],
                $arrContrato[2042],
                $arrContrato[2059],
                $arrContrato[2584],
                $arrContrato[4953],
                $arrContrato[6907],
                $arrContrato[9602],
                $arrContrato[9621],
                $arrContrato[9817],
                $arrContrato[9929],
                $arrContrato[9953],
                $arrContrato[9976],
            ];*/
            foreach ($arrContrato as $key => $contrato) {
                //foreach($arrContratoTeste as $key => $contrato){
                $dados = $this->listarDadosContratoApiSiasg($apiSiasg, $contrato);
                if (!is_null($dados) && $dados->codigoRetorno === 200) {
                    $arrParams = $this->separarNumeroContrato($dados->data[0]);
                    if ($this->_validarUnidadeModalidade($arrParams['unidade'], $contrato['modalidade_id'])) {
                        $this->cadastrarAtualizarSiasgCompra($arrParams, $contrato['modalidade_id']);
                    }
                }
            }

        } catch(Exception $e){
           throw new Exception("Error ao Processar a Requisição", $e->getMessage());
        }

    }

    private function consultarContrato()
    {
        $query = Contrato::select(
            'licitacao_numero',
            'codigoitens.descres',
            'unidades.codigo',
            'contratos.modalidade_id'
        )
            ->Join('codigoitens', 'codigoitens.id', '=', 'contratos.modalidade_id')
            ->join('unidades', 'unidades.id', '=', 'contratos.unidade_id');
        return $query->limit(10000)->get()->toArray();
    }

    private function listarDadosContratoApiSiasg(ApiSiasg $apiSiasg, array $value)
    {
        $licitacao_numero = explode( "/" ,  $value['licitacao_numero']);

        $dado = [
            'ano' => $licitacao_numero[1],
            'modalidade' => $value['descres'],
            'numero' => $licitacao_numero[0],
            'uasg' => $value['codigo']
        ];
        $dados = json_decode($apiSiasg->executaConsulta('CONTRATOCOMPRA', $dado));

        return is_object($dados) ? $dados : NULL;
    }

    private function separarNumeroContrato(string $dados)
    {
        $unidade =  Unidade::where('codigosiasg', substr($dados, 0, 6))
            ->first();
        return [
            'numero' => substr($dados, 8, 5),
            'ano' => substr($dados, 13, 4),
            'unidade' => $unidade
        ];
    }

    private function cadastrarAtualizarSiasgCompra($arrParams, $modalidade)
    {
            $siasgCompra = Siasgcompra::updateOrCreate(
                [
                    'ano' => $arrParams['ano'],
                    'numero' => $arrParams['numero'],
                    'unidade_id' => $arrParams['unidade']->id,
                    //'modalidade_id' => $arrParams['modalidade']->id,
                    'modalidade_id' => $modalidade,
                ],
                [
                    'situacao' => 'Pendente'
                ]
            );
            return $siasgCompra;
    }

    private function _validarUnidadeModalidade($unidade, $modalidade)
    {
        if ($unidade instanceof Unidade && $modalidade instanceof Codigoitem) {
            return true;
        }
        return false;
    }
}
