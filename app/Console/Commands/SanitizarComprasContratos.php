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
            $contrato =  $this->consultarContrato();
            $apiSiasg = new ApiSiasg();

            foreach($contrato as $key => $value){
                $dados = $this->listarDadosContratoApiSiasg($apiSiasg, $value);
                    if(!is_null($dados) && $dados->codigoRetorno === 200){
                        $arrParams = $this->separarNumeroContratoPorCategoria($dados->data[0]);
                        $this->atualizarSiasgCompra($arrParams);
                    }
                }

        } catch(Exception $e){
           throw new Exception("Error ao Processar a Requisição", $e->getMessage());
        }

    }

    private function consultarContrato()
    {
       $query =  Contrato::select('licitacao_numero', 'codigoitens.descres', 'unidades.codigo')
                      ->Join('codigoitens', 'codigoitens.id', '=', 'contratos.modalidade_id')
                      ->join('unidades', 'unidades.id' , '=' , 'contratos.unidade_id')
                      ;
        return $query->limit(5000)->get()->toArray();
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

    private function separarNumeroContratoPorCategoria(string $dados)
    {
        $unidade =  Unidade::where('codigosiasg', substr($dados, 0, 6))
            ->first();

        $modalidade = Codigoitem::where('descres', substr($dados, 6, 2))
            ->first();

        return [
            'numero' => substr($dados, 8, 5),
            'ano' => substr($dados, 13, 4),
            'unidade' => $unidade->id,
            'modalidade' => $modalidade->id
        ];
    }

    private function atualizarSiasgCompra($arrParams)
    {
            $siasgCompra = Siasgcompra::updateOrCreate(
                [
                    'ano' => $arrParams['ano'],
                    'numero' => $arrParams['numero'],
                    'unidade_id' => $arrParams['unidade'],
                    'modalidade_id' => $arrParams['modalidade'],
                ],
                [
                    'situacao' => 'Pendente'
                ]
            );
            return $siasgCompra;
    }
}
