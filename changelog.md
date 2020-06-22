# Changelog

Todas as alterações do Comprasnet Contratos serão documentadas neste arquivo.

## [5.0.28]- 18/06/2020

### Implementação
- Inclusão do Histórico do Contrato no Extrato PDF (Issue #27);
- API para relacionar contratos Inativos por Órgão e Unidade (Issue #48);
- Consulta Empenhos X Contratos (Issue #14).

### Melhoria
- Melhorias nos Alertas diário e Mensal (issues #24 e #25).

### Bug
- Correção combo filtro do Transparência Tela Inicial (issue #26);

## [5.0.27]- 28/05/2020

### Implementação
- Consulta e Atualização da Situação das Faturas (Issue #18);
- Consulta Contratos Cronograma (Issue #21);
- Despesas Acessórias do Contrato (Issue #34);
- Migração dos Restos a Pagar (Issue #11);
- Inclusão do Novo Campo Total Despesas Acessórias nas Consultas de Contratos.

### Bug
- Alteração de tipo da coluna arquivos em contrato_arquivos (issue #29);
- Correção de erro na request do Contrato e Instrumento Inicial;
- Correção da atualização da Natureza Despesas Job;
- Correção na Migração de Empenhos Registro Duplicado (issue #31);


## [5.0.26]- 14/05/2020

### Implementação
- Correlação de Empenhos sem Contratos na Tela Inicial (Issue #6);
- CRUD de upload para importação de arquivos Terceirizados (falta backend);
- Rotina de Atualização de Natureza de Despesas (Issue #30); 
- Opção "Não se Aplica" para modalidade de Licitação;
- Jobs para envio das notificações do Comunica (Issue #37);
- Novos tipos de Contratos. 

### Bug
- Correção da busca de Fornecedores não case sensitive (Issue #33);
- Correção da busca de Usuários Órgão (Issue #8);
- Correção da busca de Usuários Unidade (Issue #9).

## [5.0.25]- 21/04/2020

### Implementação
- Disponibiliação da funcionalidade de excluir Fatura para os Responáveis de contrtatos;
- Criação de API para retornar Unidades com Contratos Cadastrados.

## [5.0.24]- 14/04/2020

### Bug
- Correção de erro do atributo2 no formulário select2_from_ajax.

## [5.0.23]- 13/04/2020

### Bug
- Correção de erro na edição dos Empenhos vinculados aos contratos.
- Correção dos órgãos no formulário do Comunica. 

## [5.0.23]- 16/04/2020

### Implementação
- Liberação para Botão para Deletar Faturas.
>>>>>>> Stashed changes

## [5.0.22]- 09/04/2020

### Bug
- Correção da visualização do campo Órgão no formulário Comunica. 

## [5.0.21]- 06/04/2020

### Implementação  
- Adequação da Migração de Empenhos;
- Adequação da Atualização dos Saldos de Empenhos;
- Inclusão da Migração de Restos a Pagar com atualização de saldos;
- Inclusão da descrição complementar nos campos de Select com AJAX;
- Criação de API para retornar Órgãos com Contratos Cadastrados;
- Criação do campo rp na tabela de empenhos;
- Criação do campo orgao_id na tabela comunica;
- Incluir campo Órgão na listagem de comunicações;
- Incluir combo Órgão, vísivel mediante perfil;
- Alteração no combo unidades para pesquisa ajax (autocomplete) por melhoria de performance;
- Alteração no ComunicaObserver que passar a enviar mensagens filtrando por órgão, unidade ou perfil informados;
- Documentação PHPDoc nas classes e métodos afetados;
- Minor changes para uso de constantes,

## [5.0.20]- 26/03/2020

### Implementação  
- Inclusão do Nome da Unidade Gestora nos formulários da Sub-Rogação;

### Bug  
- Correção de Resetar Copiados nos Alertas Diários.

## [5.0.19]- 22/03/2020

### Implementação  
- Funcionalidade de Sub-rogação de Contratos;

## [5.0.18]- 20/03/2020

### Implementação  
- Links para consulta de contratos no Dashboard ambiente Transparência;

### Bug  
- Correção de erro no Gráfico Cronograma do Ambiente Transparência.

## [5.0.17]- 18/03/2020

### Implementação  
- Dashboard tela inicial do ambiente Transparência;

### Melhoria
- Melhoria na escrita do código do Painel de Orçamento.

### Bug  
- Correção de erro no JOB de atualização do Saldo de Empenhos.

## [5.0.16]- 14/03/2020

### Implementação  
- Tabela de controle de Migração Sistema Conta;
- Scripts de Migração do Sistema Conta 4.-, integrado com Api-Migra;
- Configuração de Órgão com formato de Processo Administrativo;
- Lista Faturas no Ambiente Transparência;
- Lista Terceirizados no Ambiente Transparência.

### Melhoria
- No campo Processo para busca de formato conforme configuração Órgão.
- Inclsão de Fornecedor no Filtro da Lista Contratos no Ambiente Transparência.
- Desempenho dos combobox de UG Primaria e Secundária para busca via AJAX no cadastro de Usuários.

## [5.0.15]- 26/02/2020

### Implementação  
- Tabela de controle de Saldo de Itens (saldohistoricoitens).

### Bug  
- Correção de erro na API de Contratos ao relacionar Empenhos sem Plano Interno.

## [5.0.14]- 13/02/2020

### Implementação  
- Ambiente público Transparência para disponibilização de Dados do Sistema.
- Consulta de Contratos Pública.

## [5.0.13]- 12/02/2020

### Implementação  
- Importação de Todos os Órgãos Superiores, Órgãos e Unidades.

### Melhoria  
- Disponibilização dos campos "Subcategoria" e "Unidades Requisitantes" na API de Contratos por Unidade e Órgão.

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


