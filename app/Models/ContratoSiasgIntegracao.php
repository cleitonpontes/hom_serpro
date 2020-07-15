<?php

namespace App\Models;

use App\Http\Traits\Formatador;

class ContratoSiasgIntegracao extends Contrato
{
    use Formatador;


    /*
   |--------------------------------------------------------------------------
   | FUNCTIONS
   |--------------------------------------------------------------------------
   */
    public function executaAtualizacaoContratos(Siasgcontrato $siasgcontrato)
    {
        if ($siasgcontrato->situacao != 'Importado') {
            return '';
        }

        $json = json_decode($siasgcontrato->json);

        dd($json);

//        $fornecedor = $this->buscaFornecedorCpfCnpjIdgener($siasgcontrato);
//        $contrato = $this->buscaContratoPorNumeroUgOrigem($siasgcontrato);

    }

    private function buscaContratoPorNumeroUgOrigem(Siasgcontrato $siasgcontrato)
    {

//        $contrato = $this->contrato()->where('numero);
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */


}
