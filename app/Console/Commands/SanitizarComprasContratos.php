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
            $arrRespostaSiasg = [];

            foreach($contrato as $key => $value){

                $dados = $this->listarDadosContratoApiSiasg($apiSiasg, $value);
                     if(is_object($dados)){
                        if($dados->codigoRetorno === 200){
                            var_dump($this->separarNumeroContratoPorCategoria($dados->data[0]));
                          }
                     }
                }

        } catch(Exception $e){
           throw new Exception("Error Processing Request", $e->getMessage());         
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

    private function listarDadosContratoApiSiasg($apiSiasg, $value)
    {
        $licitacao_numero = explode( "/" ,  $value['licitacao_numero']);

        $dado = [
            'ano' => $licitacao_numero[1],
            'modalidade' => $value['descres'],
            'numero' => $licitacao_numero[0],
            'uasg' => $value['codigo']
        ];

       return json_decode($apiSiasg->executaConsulta('CONTRATOCOMPRA', $dado));
    }

    private function separarNumeroContratoPorCategoria(string $dados)
    {
        return  [
            'numero' => substr($dados, 8, 5),
            'ano' => substr($dados, 13, 4),
            'unidade' => substr($dados, 0, 6),
            'modalidade' => substr($dados, 6, 2)
        ];
    } 
}
