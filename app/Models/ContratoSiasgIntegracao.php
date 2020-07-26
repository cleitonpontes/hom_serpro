<?php

namespace App\Models;

use App\Http\Traits\Formatador;
use App\Observers\ContratoObserve;
use Illuminate\Database\Eloquent\Model;
use function foo\func;

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
        $fornecedor = $this->buscaFornecedorCpfCnpjIdgener($json->data->cpfCnpjfornecedor, $json->data->nomefornecedor, $siasgcontrato);

        $contrato = $this->verificaContratoUnidade($siasgcontrato, $fornecedor, $json);

        if (isset($contrato->id)) {
            if (isset($json->data->termosAditivos) and $json->data->termosAditivos != null) {
                $termoaditivo = self::verificaAditivos($json->data->termosAditivos, $contrato);
            }
            if (isset($json->data->itens) and $json->data->itens != null) {
                $itens = $this->verificaItensContrato($json->data->itens, $contrato);
            }
        }

        return $contrato;

    }

    private function verificaAditivos($aditivos, Contrato $contrato)
    {

    }

    private function verificaItensContrato($itens, Contrato $contrato)
    {
        $contrato->itens()->delete();

        foreach ($itens as $item) {
            $catmatseritem = $this->buscaItensCatmatCatser($item);

            if (isset($catmatseritem->id)) {
                $contratoitem = $this->inserirContratoItem($catmatseritem, $contrato, $item);
            }
        }

        return $contratoitem;

    }

    private function inserirContratoItem(Catmatseritem $catmatseritem, Contrato $contrato, $item)
    {
        $tipo_id = $catmatseritem->catmatsergrupo->tipo_id;
        $grupo_id = $catmatseritem->grupo_id;

        $contratoitem = Contratoitem::create([
            'contrato_id' => $contrato->id,
            'tipo_id' => $catmatseritem->catmatsergrupo->tipo_id,
            'grupo_id' => $catmatseritem->grupo_id,
            'catmatseritem_id' => $catmatseritem->id,
            'quantidade' => intval($item->quantidade),
            'valorunitario' => number_format($item->valorUnitario, 2, '.', ''),
            'valortotal' => number_format(intval($item->quantidade) * number_format($item->valorUnitario, 2, '.', ''), 2, '.', '')
        ]);

        return $contratoitem;

    }

    private function buscaItensCatmatCatser($item)
    {
        $tipo = '';
        $grupo_nome = '';

        if ($item->tipo == 'S') {
            $tipo = 'SERVIÇO';
            $grupo_nome = 'GRUPO GENERICO SERVICO';
        } else {
            $tipo = 'MATERIAL';
            $grupo_nome = 'GRUPO GENERICO MATERIAIS';
        }

        $catmatser = Catmatseritem::whereHas('catmatsergrupo', function ($g) use ($tipo) {
            $g->whereHas('tipo', function ($t) use ($tipo) {
                $t->where('descres', $tipo);
            });
        })
            ->where('codigo_siasg', intval($item->código))
            ->first();

        if (!$catmatser) {
            $grupo = Catmatsergrupo::where('descricao', $grupo_nome)
                ->first();
            $catmatser = Catmatseritem::create([
                'grupo_id' => $grupo->id,
                'codigo_siasg' => intval($item->código),
                'descricao' => mb_strtoupper(trim($item->descricao), 'UTF-8')
            ]);
        }

        return $catmatser;
    }

    private function buscaFornecedorCpfCnpjIdgener(string $cpfCnpjfornecedor, string $nomefornecedor, Siasgcontrato $siasgcontrato)
    {
        $cpf_cnpj_idgener = $this->formataCnpjCpf($cpfCnpjfornecedor);
        $tipo = $this->retornaTipoFornecedor($cpfCnpjfornecedor);
        $nome = trim($nomefornecedor);
        $fornecedor = null;

        if ($cpfCnpjfornecedor == 'ESTRANGEIRO') {


            $fornecedor_id = $this->returnaFornecedorIdPorEmpenhos($siasgcontrato);

            if ($fornecedor_id != null) {
                $fornecedor = Fornecedor::find($fornecedor_id);
            } else {
                $fornecedor = Fornecedor::where('nome', 'ilike', '%' . trim($nomefornecedor) . '%')
                    ->first();

                if (!$fornecedor) {
                    $cpf_cnpj_idgener = $cpfCnpjfornecedor;
                    $tipo = 'IDGENERICO';
                    $nome = 'Alterar para ID Genérico SIAFI';
                    $fornecedor = Fornecedor::where('cpf_cnpj_idgener', $cpf_cnpj_idgener)
                        ->first();
                }
            }

        } else {
            $fornecedor = Fornecedor::where('cpf_cnpj_idgener', $cpf_cnpj_idgener)
                ->first();
        }

        if (!$fornecedor) {
            $dado = [
                'tipo_fornecedor' => $tipo,
                'cpf_cnpj_idgener' => $cpf_cnpj_idgener,
                'nome' => $nome,
            ];

            $fornecedor = Fornecedor::create($dado);
        }

        return $fornecedor;
    }

    private function returnaFornecedorIdPorEmpenhos(Siasgcontrato $siasgcontrato)
    {
        $json = json_decode($siasgcontrato->json);
        if (!isset($json->empenhos) or $json->empenhos == null) {
            return null;
        }
        $empenhos_json = $json->empenhos;
        $unidade_id = isset($siasgcontrato->unidadesubrrogacao_id) ? $siasgcontrato->unidadesubrrogacao_id : $siasgcontrato->unidade_id;
        $array = [];
        foreach ($empenhos_json as $empenho) {
            $array[] = $empenho->nrEmpenho;
        }

        $empenho_busca = Empenho::whereIn('numero', $array)
            ->where('unidade_id', $unidade_id)
            ->orderBy('numero')
            ->first();

        return $empenho_busca->fornecedor_id;
    }

    private function verificaContratoUnidade(Siasgcontrato $siasgcontrato, Fornecedor $fornecedor, $json)
    {
        $contrato = $this->buscaContratoPorNumeroUgorigemFornecedor($siasgcontrato, $fornecedor, $json);

        if (!$contrato) {
            if ($siasgcontrato->unidadesubrrogacao_id != null) {
                $temContratoAtivo = $this->buscaUnidadeComContratosAtivos($siasgcontrato->unidadesubrrogacao_id);
            } else {
                $temContratoAtivo = $this->buscaUnidadeComContratosAtivos($siasgcontrato->unidade_id);
            }

            if ($temContratoAtivo) {
                if ($siasgcontrato->unidadesubrrogacao_id != null) {
                    $contratos = $this->buscaContratosPorNumeroUgFornecedor(
                        $this->formataNumeroContratoLicitacao($json->data->numeroAno),
                        $fornecedor->id,
                        $siasgcontrato->unidadesubrrogacao_id
                    );
                } else {
                    $contratos = $this->buscaContratosPorNumeroUgFornecedor(
                        $this->formataNumeroContratoLicitacao($json->data->numeroAno),
                        $fornecedor->id,
                        $siasgcontrato->unidade_id
                    );
                }

                if ($contratos->count() == 1) {
                    $contrato = $this->atualizaContratoFromSiasg($siasgcontrato, $fornecedor, $contratos->first());
                }

                if ($contratos->count() == 0) {
                    $contrato = $this->inserirContratoFromSiasg($siasgcontrato, $fornecedor);
                }

            } else {
                $contrato = $this->inserirContratoFromSiasg($siasgcontrato, $fornecedor);
            }
        }
        return $contrato;
    }

    private function atualizaContratoFromSiasg(Siasgcontrato $siasgcontrato, Fornecedor $fornecedor, Contrato $contrato_alteracao)
    {
        $json = json_decode($siasgcontrato->json);

        $dado = $this->montaArrayContrato($siasgcontrato, $fornecedor, $json);

        unset($dado['categoria_id']);

        $contrato = $contrato_alteracao->update($dado);

        return $contrato;

    }


    private function inserirContratoFromSiasg(Siasgcontrato $siasgcontrato, Fornecedor $fornecedor)
    {
        $json = json_decode($siasgcontrato->json);

        $dado = $this->montaArrayContrato($siasgcontrato, $fornecedor, $json);

        $contrato = Contrato::create($dado);

        return $contrato;

    }

    private function montaArrayContrato(Siasgcontrato $siasgcontrato, Fornecedor $fornecedor, $json)
    {
        if (!isset($siasgcontrato->compra->modalidade_id)) {
            if ($json->data->modLicitacao != '') {
                $coditem = Codigoitem::whereHas('codigo', function ($q) {
                    $q->where('descricao', 'Modalidade Licitação');
                })
                    ->where('descres', $json->data->modLicitacao)
                    ->first();
                $modalidade_id = $coditem->id;
            } else {
                $coditem = Codigoitem::whereHas('codigo', function ($q) {
                    $q->where('descricao', 'Modalidade Licitação');
                })
                    ->where('descricao', 'Não se Aplica')
                    ->first();
                $modalidade_id = $coditem->id;
            }
        } else {
            $modalidade_id = $siasgcontrato->compra->modalidade_id;
        }

        $dado['numero'] = $this->formataNumeroContratoLicitacao($json->data->numeroAno);
        $dado['unidadeorigem_id'] = $siasgcontrato->unidade_id;
        $dado['unidade_id'] = ($siasgcontrato->unidadesubrrogacao_id != null) ? $siasgcontrato->unidadesubrrogacao_id : $siasgcontrato->unidade_id;
        $dado['tipo_id'] = $siasgcontrato->tipo_id;
        $dado['categoria_id'] = 55;
        $dado['processo'] = $this->retornaNumeroProcessoFormatado($json->data->numeroProcesso);
        $dado['objeto'] = mb_strtoupper(trim($json->data->objeto), 'UTF-8');
        $dado['receita_despesa'] = 'D';
        $dado['fundamento_legal'] = mb_strtoupper(trim($json->data->fundamentoLegal), 'UTF-8');
        $dado['modalidade_id'] = $modalidade_id;
        $dado['licitacao_numero'] = ($json->data->numLicitacao != '') ? $this->formataNumeroContratoLicitacao($json->data->numLicitacao) : $json->data->numLicitacao;
        $dado['situacao'] = true;
        $dado['fornecedor_id'] = $fornecedor->id;
        $dado['data_assinatura'] = $this->formataDataSiasg($json->data->dataAssinatura);
        $dado['data_publicacao'] = $this->formataDataSiasg($json->data->dataPublicacao);
        $dado['vigencia_inicio'] = $this->formataDataSiasg($json->data->dataInicio);
        $dado['vigencia_fim'] = $this->formataDataSiasg($json->data->dataFim);
        $dado['valor_inicial'] = $this->formataDecimalSiasg($json->data->valorTotal);
        $dado['valor_global'] = $this->formataDecimalSiasg($json->data->valorTotal);
        $dado['num_parcelas'] = (isset($json->data->valorParcela) and $json->data->valorParcela != '0.00') ? $this->formataIntengerSiasg($this->formataDecimalSiasg($json->data->valorTotal) / $this->formataDecimalSiasg($json->data->valorParcela)) : 1;
        $dado['valor_parcela'] = (isset($json->data->valorParcela) and $json->data->valorParcela != '0.00') ? $this->formataDecimalSiasg($json->data->valorParcela) : $this->formataDecimalSiasg($json->data->valorTotal);
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

        if (strlen($numero) == $countMask) {
            return $this->formataProcesso($mask_processo, $numero);
        }

        return $numero;
    }

    private function buscaContratoPorNumeroUgorigemFornecedor(Siasgcontrato $siasgcontrato, Fornecedor $fornecedor, $json)
    {
        $contrato = null;

        if ($siasgcontrato->contrato_id != null) {
            $contrato_busca = Contrato::find($siasgcontrato->contrato_id);
        } else {
            $contrato_busca = Contrato::where('numero', $this->formataNumeroContratoLicitacao($json->data->numeroAno))
                ->where('unidadeorigem_id', $siasgcontrato->unidade_id)
                ->where('fornecedor_id', $fornecedor->id)
                ->first();
        }

        if (isset($contrato_busca->id)) {
            $contrato = $this->atualizaContratoFromSiasg($siasgcontrato, $fornecedor, $contrato_busca);
        }

        return $contrato;
    }

    private function countContratosPorNumeroFornecedor($numero, $fornecedor_id, $unidade_id)
    {
        $count = Contrato::where('numero', $numero)
            ->where('unidade_id', $unidade_id)
            ->where('fornecedor_id', $fornecedor_id)
            ->count();

        return $count;
    }

    private function buscaContratosPorNumeroUgFornecedor($numero, $fornecedor_id, $unidade_id)
    {
        $contrato = Contrato::where('numero', $numero)
            ->where('unidade_id', $unidade_id)
            ->where('fornecedor_id', $fornecedor_id);

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
