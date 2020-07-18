<?php

namespace App\Models;

use App\Http\Traits\Formatador;
use Illuminate\Database\Eloquent\Model;

class ContratoSiasgIntegracao extends Model
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

        $fornecedor = $this->buscaFornecedorCpfCnpjIdgener($json->data->cpfCnpjfornecedor, $json->data->nomefornecedor);

        $contrato = $this->verificaContratoUnidade($siasgcontrato, $fornecedor, $json);

        return $contrato;

    }

    private function buscaFornecedorCpfCnpjIdgener(string $cpfCnpjfornecedor, string $nomefornecedor)
    {
        $fornecedor = Fornecedor::where('cpf_cnpj_idgener', $this->formataCnpjCpf($cpfCnpjfornecedor))
            ->first();

        if (!isset($fornecedor->id)) {
            $dado = [
                'tipo_fornecedor' => $this->retornaTipoFornecedor($cpfCnpjfornecedor),
                'cpf_cnpj_idgener' => $this->formataCnpjCpf($cpfCnpjfornecedor),
                'nome' => trim($nomefornecedor),
            ];

            $fornecedor = $this->fornecedor()->create($dado);
        }

        return $fornecedor;
    }


    private function verificaContratoUnidade(Siasgcontrato $siasgcontrato, Fornecedor $fornecedor, $json)
    {
        $contrato = $this->buscaContratoPorNumeroUgorigemFornecedor($siasgcontrato, $fornecedor, $json);

        if (!isset($contrato->id)) {

            $temContratoAtivo = $this->buscaUnidadeComContratosAtivos($siasgcontrato->unidade_id);

            if ($temContratoAtivo) {
                $count = $this->countContratosPorNumeroFornecedor(
                    $this->formataNumeroContratoLicitacao($json->data->numeroAno),
                    $fornecedor->id,
                    $siasgcontrato->unidade_id
                );

                if ($count == 1) {
                    $contrato = $this->atualizaContratoFromSiasg($siasgcontrato, $fornecedor);
                }

                if ($count == 0) {
                    $contrato = $this->inserirContratoFromSiasg($siasgcontrato, $fornecedor);
                }

            } else {
                $contrato = $this->inserirContratoFromSiasg($siasgcontrato, $fornecedor);
            }
        }

        return $contrato;
    }

    private function atualizaContratoFromSiasg(Siasgcontrato $siasgcontrato, Fornecedor $fornecedor)
    {
        $json = json_decode($siasgcontrato->json);

        $dado = $this->montaArrayContrato($siasgcontrato, $fornecedor, $json);

        unset($dado['categoria_id']);

        $contrato = $this->contrato()->where('numero',$this->formataNumeroContratoLicitacao($json->data->numeroAno))
            ->where('unidade_id', $siasgcontrato->unidade_id)
            ->where('fornecedor_id', $fornecedor->id)
            ->update($dado);

        return $contrato;

    }



    private function inserirContratoFromSiasg(Siasgcontrato $siasgcontrato, Fornecedor $fornecedor)
    {
        $json = json_decode($siasgcontrato->json);

        $dado = $this->montaArrayContrato($siasgcontrato, $fornecedor, $json);

        $contrato = $this->contrato()->create($dado);

        return $contrato;

    }

    private function montaArrayContrato(Siasgcontrato $siasgcontrato, Fornecedor $fornecedor, $json){

        $dado['numero'] = $this->formataNumeroContratoLicitacao($json->data->numeroAno);
        $dado['unidadeorigem_id'] = $siasgcontrato->unidade_id;
        $dado['unidade_id'] = (isset($siasgcontrato->unidadesubrrogacao_id)) ? $siasgcontrato->unidadesubrrogacao_id : $siasgcontrato->unidade_id;
        $dado['tipo_id'] = $siasgcontrato->tipo_id;
        $dado['categoria_id'] = 55;
        $dado['processo'] = $this->retornaNumeroProcessoFormatado($json->data->numeroProcesso);
        $dado['objeto'] = mb_strtoupper(trim($json->data->objeto),'UTF-8');
        $dado['receita_despesa'] = 'D';
        $dado['fundamento_legal'] = mb_strtoupper(trim($json->data->fundamentoLegal),'UTF-8');
        $dado['modalidade_id'] = $siasgcontrato->compra->modalidade_id;
        $dado['licitacao_numero'] = $this->formataNumeroContratoLicitacao($json->data->numLicitacao);
        $dado['situacao'] = true;
        $dado['fornecedor_id'] = $fornecedor->id;
        $dado['data_assinatura'] = $this->formataDataSiasg($json->data->dataAssinatura);
        $dado['data_publicacao'] = $this->formataDataSiasg($json->data->dataPublicacao);
        $dado['vigencia_inicio'] = $this->formataDataSiasg($json->data->dataInicio);
        $dado['vigencia_fim'] = $this->formataDataSiasg($json->data->dataFim);
        $dado['valor_inicial'] = $this->formataDecimalSiasg($json->data->valorTotal);
        $dado['valor_global'] = $this->formataDecimalSiasg($json->data->valorTotal);
        $dado['num_parcelas'] = (isset($json->data->valorParcela) and $json->data->valorParcela != '0.00')? $this->formataIntengerSiasg($this->formataDecimalSiasg($json->data->valorTotal)/$this->formataDecimalSiasg($json->data->valorParcela)) : 1;
        $dado['valor_parcela'] = (isset($json->data->valorParcela) and $json->data->valorParcela != '0.00')?$this->formataDecimalSiasg($json->data->valorParcela):$this->formataDecimalSiasg($json->data->valorTotal);
        $dado['valor_acumulado'] = $this->formataDecimalSiasg($json->data->valorTotal);

        return $dado;
    }

    private function retornaNumeroProcessoFormatado($numero)
    {
        $mask_processo = config('api-siasg.formato_processo_padrao_sisg');

        if (isset($siasgcontrato->unidade->configuracao->padrao_processo_mascara)) {
            $mask_processo = $siasgcontrato->unidade->configuracao->padrao_processo_mascara;
        } else {
            if (isset($siasgcontrato->unidade->orgao->configuracao->padrao_processo_mascara)) {
                $mask_processo = $siasgcontrato->unidade->orgao->configuracao->padrao_processo_mascara;
            }
        }

        $mask_processo = str_replace('9', '#', $mask_processo);
        $countMask = substr_count($mask_processo, '#');

        if(strlen($numero) == $countMask){
            return $this->formataProcesso($mask_processo,$numero);
        }

        return $numero;
    }

    private function buscaContratoPorNumeroUgorigemFornecedor(Siasgcontrato $siasgcontrato, Fornecedor $fornecedor, $json)
    {
        $contrato = $this->contrato()->where('numero', $this->formataNumeroContratoLicitacao($json->data->numeroAno))
            ->where('unidadeorigem_id', $siasgcontrato->unidade_id)
            ->where('fornecedor_id', $fornecedor->id)
            ->first();

        if(isset($contrato->id)){
            $contrato = $this->atualizaContratoFromSiasg($siasgcontrato, $fornecedor);
        }

        return $contrato;
    }

    private function countContratosPorNumeroFornecedor($numero, $fornecedor_id, $unidade_id)
    {
        $count = $this->contrato()->where('numero', $numero)
            ->where('unidade_id', $unidade_id)
            ->where('fornecedor_id', $fornecedor_id)
            ->count();

        return $count;
    }

    private function buscaContratosPorNumeroUgFornecedor($numero, $fornecedor_id, $unidade_id)
    {
        $contrato = $this->contrato()->where('numero', $numero)
            ->where('unidade_id', $unidade_id)
            ->where('fornecedor_id', $fornecedor_id)
            ->first();

        return $contrato;
    }

    private function buscaUnidadeComContratosAtivos(int $id)
    {
        $unidade = Unidade::whereHas('contratos', function ($c) {
            $c->where('situacao', true);
        })
            ->where('id', $id)
            ->orderBy('codigo')
            ->first();

        if (!isset($unidade->id)) {
            return false;
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

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