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
    protected $description = 'Sanitizar os dados da tabela Siasgcompra de acordo com a (ApiSiasg)';

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
               $this->cadastrarAtualizarSiasgCompra();

               //apiSiasg = new ApiSiasg();
               //buscarDadosSiascompra
               //listarDadosContratoApiSiasg();

        } catch(Exception $e){
           throw new Exception("Error ao Processar a Requisição", $e->getMessage());
        }

    }

    private function consultarContrato()
    {
        $query = Contrato::select(
            'licitacao_numero',
            'codigoitens.id as modalidade_id',
            'unidades.id as unidade_id',
        )
            ->Join('codigoitens', 'codigoitens.id', '=', 'contratos.modalidade_id')
            ->join('unidades', 'unidades.id', '=', 'contratos.unidade_id')
            ->whereNotNull('licitacao_numero');

            
        return $query->limit(10)->get()->toArray();
    }

    private function cadastrarAtualizarSiasgCompra()
    {
        $arrContrato =  $this->consultarContrato();
        foreach ($arrContrato as $key => $contrato) {

         $licitacao_numero = explode( "/" ,  $contrato['licitacao_numero']);
         $siasgCompra = Siasgcompra::updateOrCreate(
                [
                    'ano' =>$licitacao_numero[1],
                    'numero' => $licitacao_numero[0],
                    'unidade_id' => $contrato['unidade_id'],
                    'modalidade_id' =>  $contrato['modalidade_id'],
                ],
                [
                    'situacao' => 'Pendente'
                ]
            ); 
        }      
    }

    private function buscarDadosSiascompra()
    {
       
    }

    private function listarDadosContratoApiSiasg(ApiSiasg $apiSiasg, array $contrato)
    {
        $licitacao_numero = explode( "/" ,  $contrato['licitacao_numero']);

        $dado = [
            'ano' => $licitacao_numero[1],
            'modalidade' => $contrato['descres'],
            'numero' => $licitacao_numero[0],
            'uasg' => $contrato['codigo']
        ];

        $dadosApiSiasg =  $apiSiasg->executaConsulta('CONTRATOCOMPRA', $dado);
        $dado['json'] = $dadosApiSiasg;    
        return $dado;
    }

   
}
