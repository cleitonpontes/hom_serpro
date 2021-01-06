<?php

namespace App\Http\Traits;

use App\Http\Controllers\Publicacao\DiarioOficialClass;
use App\Models\Codigoitem;
use App\Models\ContratoPublicacoes;

trait DiarioOficial
{
    use BuscaCodigoItens;

    public function criaRetificacao($contratohistorico,$sisg,$cpf)
    {
        $texto_dou = @DiarioOficialClass::retornaTextoretificacao($contratohistorico);

        if(!is_null($texto_dou)) {
            $novaPublicacao = ContratoPublicacoes::Create(
                [
                    'contratohistorico_id' => $contratohistorico->id,
                    'status_publicacao_id' => $this->retornaIdCodigoItem('Situacao Publicacao', 'A PUBLICAR'),
                    'data_publicacao' => $this->verificaDataDiaUtil($contratohistorico->data_publicacao),
                    'texto_dou' => ($texto_dou != '') ? $texto_dou : '',
                    'cpf' => $cpf,
                    'status' => ($sisg) ? 'Pendente' : 'informado',
                    'tipo_pagamento_id' => $this->retornaIdCodigoItem('Forma Pagamento', 'Isento'),
                    'motivo_isencao' => 0
                ]
            );

            $this->enviarPublicacao($contratohistorico,$novaPublicacao,$texto_dou,$cpf);
        }

    }


    public function criaNovaPublicacao($contratohistorico,$cpf,$create = false)
    {
        $texto_dou = @DiarioOficialClass::retornaTextoModelo($contratohistorico);

        $sisg = (isset($contratohistorico->unidade->sisg)) ? $contratohistorico->unidade->sisg : '';
        $situacao = $this->getSituacao($sisg, $contratohistorico->data_publicacao, $create);
        if (!is_null($texto_dou)){
            $novaPublicacao = ContratoPublicacoes::create([
                'contratohistorico_id' => $contratohistorico->id,
                'data_publicacao' => $this->verificaDataDiaUtil($contratohistorico->data_publicacao),
                'status' => ($sisg) ? 'Pendente' : 'informado',
                'status_publicacao_id' => $situacao->id,
                'cpf' => $cpf,
                'texto_dou' => ($texto_dou != '') ? $texto_dou : '',
                'tipo_pagamento_id' => $this->retornaIdCodigoItem('Forma Pagamento', 'Isento'),
                'motivo_isencao' => 0
            ]);

            $this->enviarPublicacao($contratohistorico, $novaPublicacao, null, $cpf);
        }
    }


    private function enviarPublicacao($contratohistorico,$publicacao,$texto_dou,$cpf)
    {

        if ($publicacao->status_publicacao_id == $this->retornaIdCodigoItem('Situacao Publicacao', 'A PUBLICAR')) {
            $diarioOficial = new DiarioOficialClass();
            $diarioOficial->setSoapClient();
            $diarioOficial->enviaPublicacao($contratohistorico, $publicacao,$texto_dou,$cpf);
            return true;
        }
    }

    private function getArrayCamposPublicados($tipo_id)
    {
        $arrTipoContrato = [
            $this->retornaIdCodigoItem('Tipo de Contrato', 'Acordo de Cooperação Técnica (ACT)'),
            $this->retornaIdCodigoItem('Tipo de Contrato', 'Arrendamento'),
            $this->retornaIdCodigoItem('Tipo de Contrato', 'Comodato'),
            $this->retornaIdCodigoItem('Tipo de Contrato', 'Concessão'),
            $this->retornaIdCodigoItem('Tipo de Contrato', 'Contrato'),
            $this->retornaIdCodigoItem('Tipo de Contrato', 'Convênio'),
            $this->retornaIdCodigoItem('Tipo de Contrato', 'Credenciamento'),
            $this->retornaIdCodigoItem('Tipo de Contrato', 'Termo de Adesão'),
            $this->retornaIdCodigoItem('Tipo de Contrato', 'Termo de Compromisso'),
            $this->retornaIdCodigoItem('Tipo de Contrato', 'Termo de Execução Descentralizada (TED)'),
        ];
        $tipo_instrumento = in_array((int)$tipo_id, $arrTipoContrato) ? 'InstInicial' : $this->retornaDescCodigoItem($tipo_id);
        switch ($tipo_instrumento) {
            case 'InstInicial':
                return $arrCamposPublicados = [
                    'fornecedor_id',
                    'objeto',
                    'processo',
                    'vigencia_inicio',
                    'vigencia_fim',
                    //'valor_global',
                ];
            case 'Termo Aditivo':
                return $arrCamposPublicados = [
                    'numero',
                    'observacao',
                    'fornecedor_id',
                    'vigencia_inicio',
                    'vigencia_fim',
                    //'valor_global'
                ];
            case 'Termo de Apostilamento':
                return $arrCamposPublicados = [
                    'numero',
                    'observacao',
                ];
            case 'Termo de Rescisão':
                return $arrCamposPublicados = [
                    'objeto',
                    'processo',
                    'vigencia_fim',
                ];
            default: return [];
        }
    }

    private function booCampoPublicacaoAlterado($contratohistorico)
    {
        $arrContratoOriginal = $contratohistorico->getOriginal();
        $arrContratoChanges = $contratohistorico->getChanges();
        foreach ($arrContratoChanges as $key => $contratoChange) {
            if (in_array($key, $this->getArrayCamposPublicados($contratohistorico->tipo_id))) {
                if ($arrContratoChanges[$key] !== $arrContratoOriginal[$key]) {
                    return true;
                }
            }
        }
        return false;
    }

}
