# Changelog

Todas as alterações do Comprasnet Contratos serão documentadas neste arquivo.

## [5.0.12]- 29/01/2020

### Adequação Logo 
- Mudança da Logomarca do Sistema para Comprasnet contratos.

## [5.0.11]- 23/01/2020

### Bug  
- Criação da Validação das Data de Assinatura e Publicação do Cadastro do Contrato.

## [5.0.10]- 15/01/2020

### Bug  
- Correção de erro de codificação UTF-8 nas rotinas de Alertas.

## [5.0.9]- 14/01/2020

### Bug  
- Correção de erro ao montar Cronograma Contrato.

### Melhoria  
- Atualização do formato do formato de versionamentos.

## [5.0.008]- 08/01/2020

### Bug  
- Correção de erro na inclusão de Ocorrências do Tipo Conclusivas.

## [5.0.007]- 12/12/2019

### Bug  
- Correção de erro da migração de empenhos Timeout do CURl.

## [5.0.006]- 03/12/2019

### Implementação  
- Implementação do Extrato do Contrato com dados Básicos do mesmo.

### Segurança
- Atualização do código para permitir versão PHP 7.3.

## [5.0.005]- 31/10/2019

### Melhoria  
- Poder Inativar um contrato por meio da Alteração do Instrumento Inicial.

## [5.0.004]- 30/10/2019

### Melhoria  
- Disponibilização do campo "Novo Número de Parcelas" para edição no Apostilamento do Contrato.
- Disponibilização do campo "Data início novo valor" na consulta do Histórico do Contrato.

## [5.0.003]- 24/10/2019

### Bug  
- Correção de erro da migração de empenhos sem informação de Plano Interno.

### Melhoria  
- Simplificação da inserção dos Itens de Contratos, com a retirada do Grupo.

## [5.0.002]- 17/10/2019

### Bug
- Correção de erro do campo Informações Complementares da Fatura, com limitação a 255 caracteres.


## [5.0.001]- 14/10/2019

### Bug
- Correção de falha ao atualizar fornecedor por meio da Rotina de Migração dos Empenhos.
- Correção de falha ao atualizar plano interno por meio da Rotina de Migração dos Empenhos.


## [5.0.000] Lançamento Oficial - 10/10/2019

### Implementação
- Painel Contratos por Órgão;
- CRUD para alteração do Instrumento Inicial para correção de erros digitação;
- CRUD para cadastramento de Prepostos do Contrato;
- CRUD para cadastramento dos Itens do Contrato;
- Data Início e Data Fim para Responsáveis de Contratos;
- Inclusão de Contratos de Receita;
- Inclusão de Empenhos com força de contratos;
- Cronograma de Desembolso Financeiro do Contrato;
- Todas as funcionalidades do Sistema Conta Versão 4.1.11 (Framework Scriptcase);

### Descontinuada
- Módulo TED
- Módulo Planejamento

### Bug
- Correção do cálculo do Valor Acumulado atualizado por meio do Cronograma (bug da versão 4.1.11).

### Removida
- Nenhuma

### Segurança
- Suporte a Guardiankey, sistema de segurança no Login
- Alteração tipo de criptocrafia da senha do usuário
- Novo Framework - Laravel 5.7


