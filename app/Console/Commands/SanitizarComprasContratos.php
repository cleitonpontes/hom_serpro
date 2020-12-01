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
       
       $contrato =  Contrato::select('licitacao_numero', 'codigoitens.descres', 'unidades.codigo')
                           ->Join('codigoitens', 'codigoitens.id', '=', 'contratos.modalidade_id')
                           ->join('unidades', 'unidades.id' , '=' , 'contratos.unidade_id')
                           ->limit(300)->get()->toArray();

      $apiSiasg = new ApiSiasg();
      $arrRespostaSiasg = [];

      foreach($contrato as $key => $value){
        $licitacao_numero = explode( "/" ,  $value['licitacao_numero']);

        $dado = [
            'ano' => $licitacao_numero[1],
            'modalidade' => $value['descres'],
            'numero' => $licitacao_numero[0],
            'uasg' => $value['codigo']
        ];

        $dados = json_decode($apiSiasg->executaConsulta('CONTRATOCOMPRA', $dado));
        
        if($dados->codigoRetorno === 200){
            $data = $dados->data[0];
            $numero = substr($data, 8, 5);
            $ano = substr($data, 13, 4);
            $unidade = substr($data, 0, 6);
            $modalidade = substr($data, 6, 2);
            // $unidade = Unidade::where('codigosiasg', substr($data, 0, 6))
            //     ->first();
            // $modalidade = Codigoitem::where('descres', substr($data, 6, 2))->get()->toArray();
        }
    }
    }

  
}
