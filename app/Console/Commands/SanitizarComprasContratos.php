<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        /* passo 1
           * Consumir o serviço do (ContratoSiasg) (SiasgcontratoCrudController.php) 
           * -> Sanitizar dados da tabela (contratoitens) de acordo com a API (ContratoSiasg)
        */

        /**
         * passo 2
         * percorrer os contratos 
         * -> tabela (contratos) colunas (modalidade_id,licitacao_numero, numero, unidade_id, unidadeorigem_id )
         *     Select modalidade_id,licitacao_numero, numero, unidade_id, unidadeorigem_id from contratos 
         * -> codigoitens (descres)
         */

         /**
          * passo 3  
          *  Serviço compra sispp / sisrp
          */

          /**
           *  passo 4
           *  Correlaciona na tabela compras_item_unidade_contratoitens as informações dos itens do 
           * contratos com os itens da compras utilizando como chave o número do Item da Compra 
           */

    }
}
