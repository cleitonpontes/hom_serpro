<?php
/**
 * |--------------------------------------------------------------------------
 * | IMPORTAÇÃO
 * |--------------------------------------------------------------------------
 */
return [

    /*
     |--------------------------------------------------------------------------
     | Genéricos
     |--------------------------------------------------------------------------
     |
     */
    
    // Extensão válida para arquivos DDP
    'ddp-extensao-arquivo' => 'txt',
    
    // Caracter separador de campos, e/ou seus valores.
    'separador-campos' => ';',
    
    /*
     |--------------------------------------------------------------------------
     | DDP
     |--------------------------------------------------------------------------
     |
     | Cabeçalho padrão dos arquivos .ddp, sendo: Nome de cada campo, sem nenhum acento, convertidos para minúsculas,
     | separados com o separador-campos (acima) e terminando com o último nome de campo.
     |
     */
    
    // 'ddp-cabecalho' => 'competencia;nivel;categoria;conta;rubrica;descricao;valor',
    'ddp-cabecalho' => 'competencia;nivel;categoria;conta;rubrica;descricao;valor',
    
    // Campos; campos previstos no arquivo para conferência dos dados, valores e respectivos totais no rodapé.
    'ddp-campos' => [
        '0' => 'competencia',
        '1' => 'nivel',
        '2' => 'categoria',
        '3' => 'conta',
        '4' => 'rubrica',
        '5' => 'descricao',
        '6' => 'valor'
    ],
    
    // Índice dos campos previstos
    'ddp-campo-competencia' => 0,
    'ddp-campo-nivel' => 1,
    'ddp-campo-categoria' => 2,
    'ddp-campo-conta' => 3,
    'ddp-campo-rubrica' => 4,
    'ddp-campo-descricao' => 5,
    'ddp-campo-valor' => 6,
    
    // Categorias atuais
    'ddp-categorias' => [
        '1' => 'aaa',
        '2' => 'bbb',
        '3' => 'ccc',
        '4' => 'ddd',
        '5' => 'Rodapé'
    ],
    
    // Índice das categorias
    'ddp-categoria-aaa' => 1,
    'ddp-categoria-bbb' => 1,
    'ddp-categoria-ccc' => 1,
    'ddp-categoria-ddd' => 1,
    'ddp-categoria-rodape' => 5,
    
];
