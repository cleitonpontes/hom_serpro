<?php

return [

    'url' => env('API_SIASG_URL'),

    'token' => env('API_SIASG_TOKEN'),

    'formato_processo_padrao_sisg' => env('FORMATO_PROCESSO_PADRAO_SISG','99999.999999/9999-99'),

    'mensagem_contrato' => [
        '0000' => 'Sucesso',
        '0001' => 'O tamanho padrão do contrato é de %n dígitos.',
        '0002' => 'Contrato Não Encontrado',
        '0003' => 'Contrato Pendente',
    ],

    'tipo_contrato' => [
        '50',
        '51',
        '52',
        '53',
        '54',
    ],

    'situacao' => [
        '1' => 'Vigente',
        '2' => 'Não Vigente',
        '3' => 'Rescindido',
    ],

    'modalidade_licitacao' => [
        '01',
        '02',
        '03',
        '04',
        '05',
        '06',
        '07',
        '20',
        '22',
        '33',
        '44',
    ],

    'tipo_ta' => [
        '1' => 'Valor',
        '2' => 'Vigência',
        '3' => 'Fornecedor',
        '4' => 'Valor e Vigência',
        '5' => 'Valor e Fornecedor',
        '6' => 'Vigência e Fornecedor',
        '7' => 'Valor, Vigêncio e Fornecedor',
        '8' => 'Informativo',
    ],

    'codigo_termo' => [
        '55',
        '56',
    ],

    'tipo_item' => [
        'M' => 'Material',
        'S' => 'Serviço',
    ],


];
