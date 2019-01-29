<?php
/**
 * Mensagens diversas referentes à Apropriação da Folha
 *
 * @author Basis Tecnologia da Informação
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */

//
// |--------------------------------------------------------------------------
// | MENSAGENS
// |--------------------------------------------------------------------------
//
return [

    // |--------------------------------------------------------------------------
    // | APROPRIAÇÃO - Listagem
    // |--------------------------------------------------------------------------

    // Mensagem de sucesso após criação de nova apropriação
    'apropriacao-novo' => 'Nova apropriação criada a partir da importação.',

    // Mensagem de sucesso após exclusão
    'apropriacao-exclusao' => 'Apropriação excluída.',

    // |--------------------------------------------------------------------------
    // | APROPRIAÇÃO - Relatório
    // |--------------------------------------------------------------------------

    // Mensagem de erro após busca dos dados de identificação
    'apropriacao-relatorio-erro-ident' => 'Relatório da Apropriação não possui dados de indentificação.',

    // Mensagem de erro por ausência de dados do passo 5
    'apropriacao-relatorio-erro-passo-5' => 'Relatório da Apropriação não possui dados complementares.',

    // Mensagem de erro após busca dos dados dos PCOs
    'apropriacao-relatorio-erro-pco' => 'Relatório da Apropriação não possui dados de PCO e/ou PCO Item.',

    // |--------------------------------------------------------------------------
    // | APROPRIAÇÃO - 1 - Importação DDP
    // |--------------------------------------------------------------------------

    // Mensagem na exclusão de apropriação em fase finalizada
    'apropriacao-exclusao-alerta' => 'Apropriação não pode ser excluída por já ter sido finalizada!',

    // Quantidade de arquivos DDP a importar
    'import-ddp-qtde-arquivos' => 'Total de arquivos deve ser entre 1 e 3.',

    // Arquivos com extensão inválida
    'import-ddp-extensao-invalida' => 'Arquivo deve possuir extensão .txt.',

    // Arquivo com cabeçalho inválido
    'import-ddp-cabecalho-invalido' => 'Arquivo com cabeçalho inválido.',

    // Arquivo contém mais de uma competência
    'import-ddp-competencias-multiplas' => 'Arquivo com mais de uma competência.',

    // Quantidade de campos é diferente da esperada
    'import-ddp-qtde-campos-invalida' => 'Quantidade de campos diferente da esperada.',

    // Total do valor bruto não confere no rodapé
    'import-ddp-rodape-bruto-nao-confere' => 'Total do valor bruto não confere.',

    // Total do valor desconto não confere no rodapé
    'import-ddp-rodape-desconto-nao-confere' => 'Total do valor desconto não confere.',

    // Total do valor líquido não confere no rodapé
    'import-ddp-rodape-liquido-nao-confere' => 'Total do valor líquido não confere.',

    // Campo competência inválido, não sendo YYYY-MM
    'import-ddp-linha-campo-competencia' => 'Competência inválida.',

    // Nível fora do conjunto A, B ou E
    'import-ddp-linha-campo-nivel' => 'Nível deve ser A, B, E ou T.',

    // Categoria fora do intervalo de 1 a 6
    'import-ddp-linha-campo-categoria' => 'Categoria deve ser 1, 2 ou 5.',

    // Conta - Natureza de Despesa - não possui 8 dígitos
    'import-ddp-linha-campo-conta' => 'Conta deve possuir 8 dígitos.',

    // Rubrica não possui 5 dígitos
    'import-ddp-linha-campo-rubrica' => 'Rubrica deve possuir 5 dígitos.',

    // Descrição possui mais de 30 caracteres
    'import-ddp-linha-campo-descricao' => 'Descrição deve ter até 30 caracteres.',

    // Valor não é válido
    'import-ddp-linha-campo-valor' => 'Valor inválido.',

    // Mensagem de alerta para existência de pendências
    'import-ddp-pendencia-validacoes' => 'A importação foi cancelada por existência de pendência(s) na validação.',

    // |--------------------------------------------------------------------------
    // | APROPRIAÇÃO - 2 - Identificar Situação
    // |--------------------------------------------------------------------------

    // Mensagem de alerta se houver situações a identificar
    'apropriacao-situacao-pendencias' => 'Ainda há registros que não tiveram sua situação identificada.',

    // |--------------------------------------------------------------------------
    // | APROPRIAÇÃO - 3 - Identificar Empenhos
    // |--------------------------------------------------------------------------

    // Mensagem de alerta se houver empenhos a identificar ou valores a ratear
    'apropriacao-empenho-pendencias' => 'Ainda há registros sem empenho identificado ou seu valor rateado.',

    // |--------------------------------------------------------------------------
    // | APROPRIAÇÃO - 4 - Validar Saldos
    // |--------------------------------------------------------------------------

    // Mensagem de alerta se houver empenhos a identificar ou valores a ratear
    'apropriacao-saldo-pendencias' => 'Ainda há registros que não estão com saldo de empenho atual suficiente.',

    // |--------------------------------------------------------------------------
    // | APROPRIAÇÃO - 5 - Informar dados complementares
    // |--------------------------------------------------------------------------

    // Mensagem na exclusão de apropriação em fase finalizada
    'apropriacao-dados-complementares-salvos' => 'Dados complementares informados com sucesso.',

    // Mensagem de alerta se houver empenhos a identificar ou valores a ratear
    'apropriacao-dados-complementares-pendencias' => 'Todos os campos são obrigatórios.',

    // |--------------------------------------------------------------------------
    // | APROPRIAÇÃO - 6 - Persistir Dados
    // |--------------------------------------------------------------------------

    // Mensagem de alerta se houver empenhos a identificar ou valores a ratear
    'apropriacao-persistir-pendencias' => 'Não foram encontrados dados persistidos para essa apropriação.'
];
