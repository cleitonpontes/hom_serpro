<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 19/01/2019
 * Time: 16:07
 */
return [
    'migracao_contratos' => env('MIGRACAO_CONTRATOS', 'https://conta.agu.gov.br/migracao_contratos/xxxx'),
    'migracao_empenhos' => env('MIGRACAO_EMPENHOS', 'https://conta.agu.gov.br/migracao_empenhos/leitura.php'),
    'api_sta' => env('API_STA_HOST','https://sta.agu.gov.br'),

    // migração tse -> agu
    'tipo_contrato_padrao' => env('TIPO_CONTRATO_PADRAO',60),
    'categoria_padrao' => env('CATEGORIA_PADRAO',55),
    'modalidade_padrao' => env('MODALIDADE_PADRAO',172),
];
