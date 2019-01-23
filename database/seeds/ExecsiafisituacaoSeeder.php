<?php

use Illuminate\Database\Seeder;

class ExecsiafisituacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('execsfsituacao')->insert(['codigo' => "BPV001", 'descricao' => "PAGAMENTO DE OBRIGAÇÕES LIQUIDADAS FORA DO CPR OU POR OUTRO DOCUMENTO HÁBIL", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "BPV002", 'descricao' => "PAGAMENTO DE RETENÇÕES DA FOLHA", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "BPV003", 'descricao' => "PAGAMENTO DE RETENÇÕES DA FOLHA DE PAGAMENTO - INSCRIÇÃO GENÉRICA", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "BPV004", 'descricao' => "PAGAMENTO DE OBRIGAÇÕES LIQUIDADAS FORA DO CPR(EXEMPLO SIADS) E INSCRITAS EM RPP", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "BPV009", 'descricao' => "PAGAMENTO DE VALORES RESTITUÍVEIS LIQUIDADOS FORA DO CPR OU OUTRO DOC. HÁBIL", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL001", 'descricao' => "DESPESA COM REMUNERACAO A PESSOAL ATIVO CIVIL - RPPS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL002", 'descricao' => "DESPESA COM REMUNERAÇÃO A PESSOAL ATIVO CIVIL - RPPS - UG EXTERIOR", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL003", 'descricao' => "DESPESA COM BENEFICIOS A PESSOAL - CIVIL RPPS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL004", 'descricao' => "DESPESA COM REMUNERAÇÃO A PESSOAL INATIVO CIVIL - RPPS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL005", 'descricao' => "DESPESA COM REMUNERACAO A PESSOAL PENSIONISTA CIVIL - RPPS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL006", 'descricao' => "DESPESA COM CUSTEIO DE FOLHA DE PAGAMENTO - RPPS - UG EXTERIOR", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL011", 'descricao' => "DESPESA COM REMUNERAÇÃO A PESSOAL ATIVO CIVIL - RGPS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL012", 'descricao' => "DESPESA COM REMUNERAÇÃO A PESSOAL ATIVO CIVIL - RGPS - UG EXTERIOR", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL013", 'descricao' => "DESPESA COM BENEFÍCIOS A PESSOAL - CIVIL RGPS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL014", 'descricao' => "DESPESA COM REMUNERAÇÃO A PESSOAL INATIVO CIVIL - RGPS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL015", 'descricao' => "DESPESA COM REMUNERAÇÃO A PESSOAL PENSIONISTA CIVIL - RGPS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL016", 'descricao' => "DESPESA COM CUSTEIO DE FOLHA DE PAGAMENTO - RGPS - UG EXTERIOR ", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL017", 'descricao' => "DESPESA COM REMUNERAÇÃO A PESSOAL ATIVO CIVIL - RGPS - NÃO SUBSTITUIÇÃO SERVIDOR", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL018", 'descricao' => "DESPESA COM ENCARGOS DE BENEFÍCIOS PREVIDENCIÁRIOS DA UNIÃO - RGPS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL020", 'descricao' => "DESPESA COM DÉCIMO TERCEIRO SALÁRIO - FOLHA DE DEZEMBRO - UG EXTERIOR", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL021", 'descricao' => "DESPESA COM REMUNERACAO A PESSOAL ATIVO MILITAR", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL022", 'descricao' => "DESPESA COM REMUNERAÇÃO A PESSOAL ATIVO MILITAR - UG EXTERIOR", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL023", 'descricao' => "DESPESA COM BENEFÍCIOS A PESSOAL - MILITAR", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL024", 'descricao' => "DESPESA COM RESERVA REMUNERADA E REFORMAS - MILITAR", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL025", 'descricao' => "DESPESA COM REMUNERAÇÃO A PESSOAL PENSIONISTA MILITAR", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL026", 'descricao' => "DESPESA COM CUSTEIO DE FOLHA DE PAGAMENTO - MILITAR - UG EXTERIOR", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL030", 'descricao' => "DESPESA COM LINCENÇAS CONCEDIDAS A PESSOAL ", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL031", 'descricao' => "DESPESA COM ADIANTAMENTOS DE 13º SALARIO", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL032", 'descricao' => "DESPESA COM ADIANTAMENTOS DE 1/3 DE FÉRIAS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL033", 'descricao' => "DESPESA COM ADIANTAMENTOS DO SALÁRIO NO PERÍODO DE FÉRIAS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL034", 'descricao' => "DESPESA COM INDENIZAÇÕES E RESTITUIÇÕES TRABALHISTAS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL035", 'descricao' => "RESSARCIMENTO DE DESPESAS DE PESSOAL REQUISITADO DE OUTROS ÓRGÃOS OU ENTES", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL036", 'descricao' => "DESPESA COM BENEFÍCIOS DE PRESTAÇÃO CONTINUADA", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL037", 'descricao' => "DESPESA COM BENEFÍCIOS EVENTUAIS - INSS E FRGPS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL038", 'descricao' => "DESPESA COM OUTROS BENEFÍCIOS PREVIDENCIÁRIOS E ASSISTENCIAIS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL039", 'descricao' => "OUTRAS DESPESAS COM BENEFÍCIOS A PESSOAL", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL040", 'descricao' => "OUTRAS DESPESAS COM PESSOAL INATIVO", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL041", 'descricao' => "OUTRAS DESPESAS COM PESSOAL PENSIONISTA", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL042", 'descricao' => "DESPESA COM COMPENSAÇÃO PREVIDENCIÁRIA ENTRE REGIMES DE PREVIDÊNCIA", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL043", 'descricao' => "DESPESA COM PESSOAL ATIVO, COM INCORPORAÇÃO DE ATIVOS - UG EXTERIOR", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL044", 'descricao' => "DESPESA COM 13º SALÁRIO - FOLHA DE DEZEMBRO", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL045", 'descricao' => "DESPESA COM OUTROS SERVIÇOS DE TERCEIROS - PESSOA FÍSICA", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL046", 'descricao' => "DESPESA COM REMUNERACAO A PESSOAL CEDIDO A OUTROS ÓRGÃOS OU ENTES", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL047", 'descricao' => "DESPESAS COM INDENIZAÇÕES DIVERSAS A PESSOAL", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL048", 'descricao' => "DESPESA COM PESSOAL ATIVO, COM INCORPORAÇÃO DE ATIVOS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL049", 'descricao' => "DESPESAS COM INCENTIVOS A EDUCAÇÃO, CIÊNCIA,CULTURA, ESPORTE E OUTROS ", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL050", 'descricao' => "DESPESA C/OUTROS BENEFÍCIOS PREV.E ASSISTENCIAIS - UG EXTERIOR ", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL061", 'descricao' => "DESPESA COM PESSOAL ATIVO, COM INCORPORAÇÃO DE BENS MÓVEIS - UG EXTERIOR", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL062", 'descricao' => "DESPESA COM REMUNERAÇÃO A PESSOAL ATIVO MILITAR  RPPS  UG EXTERIOR - CONTA CAIXA", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL063", 'descricao' => "ADIANTAMENTOS DE AUXÍLIO-ALIMENTAÇÃO E AUXÍLIO-TRANSPORTE", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL064", 'descricao' => "DESPESA COM OUTROS SERVIÇOS DE TERCEIROS - PESSOA JURÍDICA", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL065", 'descricao' => "DESPESA COM ADIANTAMENTOS DE SALÁRIOS, FERIAS E 13 TERCEIRO - UG EXTERIOR", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL066", 'descricao' => "DESPESA COM INDENIZAÇÕES E RESTITUIÇÕES TRABALHISTAS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL067", 'descricao' => "DESPESA COM OBRIGAÇÕES TRABALHISTAS CONTRA INCORP. PASSIVOS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL068", 'descricao' => "DESPESA C/OUTROS ENCARGOS PATRONAIS DE OBRIGAÇÕES TRABALHISTAS - PAE; ATS; VPNI", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL069", 'descricao' => "DESPESAS COM RESTITUIÇÕES DIVERSAS A PESSOAL", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DFL072", 'descricao' => "DESPESA COM PAGAMENTO DE 1/3 DE FÉRIAS - EMPRESAS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DSP006", 'descricao' => "AQUISIÇÃO DE TERCEIRIZAÇÃO DE MÃO-DE-OBRA", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DSP016", 'descricao' => "AQUISIÇÃO TERCEIRIZAÇÃO DE MÃO-DE-OBRA(CONTRATO TIPO CREDOR + PC OU RC)", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DSP901", 'descricao' => "DESPESAS COM INDENIZAÇÕES DIVERSAS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DSP975", 'descricao' => "DESPESAS COM JUROS/ENCARGOS DE MORA DE OBRIGACOES TRIBUTARIAS", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DSP988", 'descricao' => "DESPESAS COM JUROS/ENCARGOS DE MORA DE OBRIGACOES TRIBUTARIAS - DARF", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PPV005", 'descricao' => "PAGAMENTO DE PASSIVO INSCRITO EM RPP - FONTE 0177 - REALIZAÇÃO POR OB", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PPV006", 'descricao' => "PAGAMENTO DE PASSIVO INSCRITO EM RPNP EM LIQ. - FONTE 0177 - REALIZAÇÃO POR OB", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PPV100", 'descricao' => "APROPRIAÇÃO DO VALOR BRUTO A DEDUZIR - SEM TROCA DE FONTE", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PPV177", 'descricao' => "APROPRIAÇÃO DO VALOR BRUTO A DEDUZIR - FONTE 0177", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PPV190", 'descricao' => "APROPRIAÇÃO DO VALOR BRUTO A DEDUZIR - FONTE 0190", 'aba' => 'PCO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO001", 'descricao' => "RECOLHIMENTO DE VALORES EM TRÂNSITO PARA ESTORNO DE DESPESA", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO002", 'descricao' => "REGULARIZAÇÃO DE ORDENS BANCÁRIAS CANCELADAS (2.1.8.9.1.36.03) - OB E GRU", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO007", 'descricao' => "REGULARIZAÇÃO DE OUTROS DEPÓSITOS, POR CANCELAMENTO DE GFIP (FUGA)", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO021", 'descricao' => "RECOLHIMENTO DE VALORES EM TRÂNSITO COM REALIZAÇÃO POR GPS", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO023", 'descricao' => "PAGAMENTO/DEVOLUÇÃO DE DEPÓSITOS DIVERSOS (CONTAS 2.1.8.8.1.XX.XX - C/C FONTE)", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO025", 'descricao' => "PAGAMENTO DEPOSITO DE TERCEIROS QDO HOUVER NECESSIDADE DE TROCAR CONTA-CORRENTE", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO027", 'descricao' => "OBRIGAÇÕES DÉBITOS DIVERSOS - 211X1.XX.XX - SEM CONT.EMPENHO", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO042", 'descricao' => "PAGAMENTO DEPÓSITOS DIVERSOS (CONTAS 2.1.8.X.X.XX.XX-C/C FTE+CNPJ,CPF,UG,IG,999)", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO048", 'descricao' => "PAGAMENTO DE DEPÓSITOS DE TERCEIROS POR DARF", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO055", 'descricao' => "RECOLH.DE VALORES TRÂNSITO P/ESTORNO DESP EXERC ANTERIORES USANDO DEPOSITO TERCE", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO057", 'descricao' => "BAIXA DE CONSIGNAÇÕES, GERANDO DEPÓSITO DE TERCEIROS", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO064", 'descricao' => "RECOLHIMENTO DE VALORES EM TRÂNSITO PARA ESTORNO DE DESPESA - REALIZ. DARF", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO068", 'descricao' => "PAGAMENTO DE DEPÓSITOS DE RENDIMENTOS DO PIS/PASEP", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO070", 'descricao' => "BAIXA DE OBRIGACOES REFERENTE A PENSAO ALIMENTÍCIA", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO072", 'descricao' => "DESP.C/ PESSOAL EXERC. CORRENTE - REGISTRADA FORA CPR E SEM CONTROLE DE EMPENHO", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO074", 'descricao' => "INSS PATRONAL - ROTINA EXCLUSIVA DA FOLHA SERPRO SEM NE - DARF", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO075", 'descricao' => "RECOLHIMENTO DE INSS ROTINA SERPRO - REALIZ. GPS", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO079", 'descricao' => "RETENCAO EM FOLHA - PLANO DE PREV. E ASSIST MÉD - LIQUIDADAS POR OUTRO DOC S/NE", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO080", 'descricao' => "RETENCAO EM FOLHA - ENTIDADES DE CLASSE - LIQUIDADAS POR OUTRO DOC S/NE", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO081", 'descricao' => "RETENCAO EM FOLHA - OUTROS CONSIG. - LIQUIDADAS P/OUTRO DOC E S/CONTROLE EMPENHO", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO082", 'descricao' => "RETENÇÃO DE IRRF SOBRE FOLHA SEM CONTROLE DE EMPENHO. CONTABILIZA NA REALIZAÇÃO ", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO087", 'descricao' => "PLANO DE SEGURIDADE SOCIAL DO SERVIDOR - PSSS. CONTABILIZA NA REALIZAÇÃO ", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO090", 'descricao' => "RECOLHIMENTO DO TRIBUTO DA MANTENEDORA POR DARF - FIES", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO091", 'descricao' => "RECOLHIMENTO DO TRIBUTO DA MANTENEDORA POR GPS - FIES", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PSO096", 'descricao' => "PAGAMENTO DE DEPÓSITOS DE TERCEIROS POR GPS", 'aba' => 'PSO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DDF001", 'descricao' => "RETENÇÃO DE IMPOSTOS SOBRE CONTRIBUIÇÕES DIVERSAS- IN 1234 SRF, DE 11/1/12.", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DDF002", 'descricao' => "IMPOSTO DE RENDA RETIDO NA FONTE - IRRF", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DDF003", 'descricao' => "IMPOSTO DE RENDA RETIDO NA FONTE - SEM EXECUÇÃO ORÇAMENTÁRIA", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DDF006", 'descricao' => "IMPOSTO DE RENDA RETIDO NA FONTE - IRRF (CONSOLIDÁVEL)", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DDF007", 'descricao' => "CONTRIBUIÇÃO PREVIDENCIÁRIA - INSS (CONSOLIDÁVEL)", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DDF008", 'descricao' => "RETENÇÃO IMPOSTOS S/CONTRIBUIÇÕES DIVERSAS- IN 1234 SRF DE 11/1/12(CONSOLIDAVEL)", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DDF010", 'descricao' => "PLANO DE SEGURIDADE SOCIAL DO SERVIDOR", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DDF012", 'descricao' => "RETENÇÃO DE IMPOSTOS - IN 1234 SRF/2012 - REALIZAÇÃO", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DDF014", 'descricao' => "RETENÇÃO DE IMPOSTOS S/CONTRIBUIÇÕES DIVERSAS- IN 1234 SRF - SEM NE OU PARA SF", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DDF015", 'descricao' => "PLANO DE SEGURIDADE SOCIAL DO SERVIDOR - SEM EXECUÇÃO ORÇAMENTÁRIA", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DDF021", 'descricao' => "RETENÇÃO PREVIDENCIÁRIA RECOLHIDA POR DARF NUMERADO", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DDR001", 'descricao' => "RETENÇÕES DE IMPOSTOS RECOLHÍVEIS POR DAR", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DDR004", 'descricao' => "RETENÇÕES DE IMPOSTOS RECOLHÍVEIS POR DAR - REALIZAÇÃO", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DDR005", 'descricao' => "RETENÇÕES DE IMPOSTOS RECOLHÍVEIS POR DAR SEM EXECUÇÃO ORÇAMENTÁRIA", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DED004", 'descricao' => "OUTROS CONSIGNATARIOS S/ PESSOAL A PAGAR", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DED008", 'descricao' => "DEPOSITOS RETIDOS SOBRE PESSOAL A PAGAR", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DED099", 'descricao' => "PSSS RETIDO SOBRE PESSOAL A PAGAR - FCDF", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGF001", 'descricao' => "FGTS RETIDO NA FATURA", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGP001", 'descricao' => "RETENÇÃO DE INSS", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGP002", 'descricao' => "RETENÇÕES DE IMPOSTOS RECOLHÍVEIS POR GPS - REALIZAÇÃO", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGP003", 'descricao' => "RETENÇÃO DE INSS SEM EMPENHO", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGP004", 'descricao' => "RETENÇÃO DE INSS (CONSOLIDÁVEL)", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGR002", 'descricao' => "RETENÇÃO PARA RESSARCIMENTO DE PESSOAL REQUISITADO ", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGR003", 'descricao' => "RETENÇÃO DE BENEFÍCIOS DO INSS REFERENTES A ENTIDADES REPRESENTATIVAS ", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGR004", 'descricao' => "RETENÇÃO DE CONSIGNAÇÕES DE LOCADORES DE IMÓVEIS ", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGR005", 'descricao' => "RETENÇÃO DE INDENIZAÇÕES E RESTITUIÇÕES ", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGR006", 'descricao' => "RETENÇÃO PARA INSTITUIÇÕES PREVIDENCIÁRIAS ", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGR007", 'descricao' => "APROPRIAÇÃO DAS OBRIGAÇÕES COM TAXAS ", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGR008", 'descricao' => "RETENÇÃO REFERENTE A PLANOS DE PREVIDÊNCIA E ASSISTÊNCIA MÉDICA ", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGR009", 'descricao' => "APROPRIAÇÃO DE CONSIGNAÇÕES LINHA DE CONTRACHEQUE ", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGR010", 'descricao' => "RETENÇÃO OUTROS CONSIGNATÁRIOS - FONTES TESOURO/PRÓPRIA ", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGR012", 'descricao' => "CONSIGNAÇÃO PARA TRANSFERÊNCIA PARA O GDF ", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGR013", 'descricao' => "CONSIGNAÇÃO PARA TRANSFERÊNCIA AO GDF - PSSS", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGR015", 'descricao' => "RETENÇÃO DE OUTROS CONSIGNATÁRIOS - SEM EXECUÇÃO ORÇAMENTÁRIA OU SF", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DGR016", 'descricao' => "RETENÇÃO PSSS DOS MILITARES PARA O CUSTEIO DE PENSÕES ", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DNS001", 'descricao' => "RETENÇÃO DE PLANO SEGURIDADE SOCIAL SERVIDOR - UG EXTERIOR", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DNS002", 'descricao' => "RETENÇÃO DE PENSÃO ALIMENTÍCIA - UG EXTERIOR", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DNS003", 'descricao' => "RETENÇÃO DE PREVIDÊNCIA LOCAL - UG EXTERIOR", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DNS005", 'descricao' => "RETENÇÃO DE IRRF - UG EXTERIOR", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DNS006", 'descricao' => "RETENÇÃO DE INDENIZAÇÕES E RESTITUIÇÕES - UG EXTERIOR", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DNS007", 'descricao' => "RETENÇÃO DE ASSOCIAÇÕES - UG EXTERIOR", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DNS026", 'descricao' => "DEPÓSITOS RETIDOS SOBRE PESSOAL A PAGAR - UG EXTERIOR", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB003", 'descricao' => "DEPÓSITOS RECEBIDOS JUDICIALMENTE (C/C FONTE + CREDOR)", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB005", 'descricao' => "DEDUÇÃO DE OUTROS CONSIGNATARIOS - PAGAMENTO POR OB", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB006", 'descricao' => "RETENÇÃO DE EMPRÉSTIMOS", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB007", 'descricao' => "DESCONTO DA PENSÃO ALIMENTÍCIA", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB008", 'descricao' => "RETENCAO FOLHA REFERENTE A ENTIDADES REPRESENTATIVAS DE CLASSE ", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB009", 'descricao' => "RETENÇÃO PARA PLANOS DE PREVIDÊNCIA E ASSISTÊNCIA MÉDICA", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB010", 'descricao' => "RETENÇÃO CONSIGNAÇÃO LOCADORES IMÓVEIS", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB011", 'descricao' => "RETENÇÃO DE CONSIGNAÇÃO A COOPERATIVAS", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB012", 'descricao' => "RETENÇÃO A FAVOR DE PLANOS DE SEGUROS", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB013", 'descricao' => "RETENÇÃO CONSIGNAÇÃO ASSOCIAÇÕES ", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB014", 'descricao' => "RETENÇÃO PARA RESSARCIMENTO DE PESSOAL REQUISITADO", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB015", 'descricao' => "RETENÇÃO DE VALE-TRANSPORTE - 6% DO SERVIDOR", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB016", 'descricao' => "RETENÇÃO DE EMPRÉSTIMOS (CONSOLIDÁVEL)", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB017", 'descricao' => "RETENCAO FOLHA REFERENTE A ENTIDADES REPRESENTATIVAS DE CLASSE (CONSOLIDÁVEL)", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB018", 'descricao' => "RETENÇÃO PARA CONSIGNAÇÃO A SINDICATOS", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB019", 'descricao' => "RETENÇÃO DE CONSIGNAÇÃO PARA ASSISTÊNCIA À SAÚDE", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB020", 'descricao' => "RETENÇÃO DE VALE-REFEIÇÃO", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB022", 'descricao' => "DEPÓSITOS RETIDOS SOBRE PESSOAL A PAGAR - OB", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB023", 'descricao' => "DEPÓSITOS A EFETUAR POR DETERMINAÇÃO JUDICIAL (C/C FONTE)", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB032", 'descricao' => "RETENÇÃO PARA REGIME PRÓPRIO DE PREVIDÊNCIA - FUNPRESP", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB036", 'descricao' => "RETENCAO EM FOLHA - PLANO DE PREV. E ASSIST MÉD - LIQUIDADAS POR OUTRO DOC S/NE", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DOB037", 'descricao' => "RETENCAO EM FOLHA - ENTIDADES DE CLASSE - LIQUIDADAS POR OUTRO DOC S/NE", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "DPF001", 'descricao' => "DESCONTOS DIVERSOS - FOLHA DE PAGAMENTO", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PDF001", 'descricao' => "PAGAMENTO DE TRIBUTOS RECOLHÍVEIS POR DARF INSCRITOS EM RPP", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PDF011", 'descricao' => "RETENÇÃO DE TRIBUTO EM EMPENHO INSCRITO EM RPP, RECOLHIMENTO POR DARF", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PDR001", 'descricao' => "PAGAMENTO DE TRIBUTOS RECOLHÍVEIS POR DAR INSCRITOS EM RPP", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PDR011", 'descricao' => "RETENÇÃO. DE TRIBUTO SOBRE EMPENHO INSCRITO EM RPP, RECOLHÍVEL POR DAR", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PGF011", 'descricao' => "RET.DE FGTS EM EMPENHO INSCRITO EM RPP, RECOL. POR GFIP", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PGP001", 'descricao' => "PAGAMENTO DE INSS INSCRITO EM RPP", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PGP011", 'descricao' => "RETENÇÃO DE INSS SOBRE EMPENHO INSCRITO EM RPP", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PGR001", 'descricao' => "PAGAMENTO DE RETENÇÕES RECOLHÍVEIS POR GRU INSCRITAS EM RPP", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PGR011", 'descricao' => "RETENÇÕES SOBRE EMPENHO INSCRITO EM RPP, RECOLHÍVEL POR GRU", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PNS001", 'descricao' => "PAGAMENTO DE VALORES RECOLHÍVEIS POR NS INSCRITOS EM RPP", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PNS011", 'descricao' => "RETENÇÃO DE TRIBUTO SOBRE EMPENHO INSCRITO EM RPP, RECOLHÍVEL POR NS ", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "POB001", 'descricao' => "PAGAMENTO DE RETENÇÕES POR OB INSCRITAS EM RPP - FONTE 0177 OU 0190", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "POB011", 'descricao' => "RETENÇÕES SOBRE EMPENHO INSCRITO EM RPP, RECOLHÍVEL POR OB", 'aba' => 'DEDUCAO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "CRT001", 'descricao' => "CRÉDITO TRIBUTÁRIO PSSS", 'aba' => 'CREDITO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "CRT002", 'descricao' => "CRÉDITO TRIBUTÁRIO INSS", 'aba' => 'CREDITO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "CRT003", 'descricao' => "CRÉDITO TRIBUTÁRIO IRRF", 'aba' => 'CREDITO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "CRT004", 'descricao' => "ADIANTAMENTO DE SALÁRIO MATERNIDADE, AUXÍLIOS E SALÁRIO FAMÍLIA", 'aba' => 'CREDITO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "CRT005", 'descricao' => "CRÉDITO ADMINISTRATIVO DE FOLHA DE PAGAMENTO - RETENÇÃO A MAIOR", 'aba' => 'CREDITO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "CRT007", 'descricao' => "CRÉDITO TRIBUTÁRIO DE FORNCEDORES - RETENÇÃO A MAIOR", 'aba' => 'CREDITO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "CRT008", 'descricao' => "PRORROGAÇÃO DA LICENÇA-MATERNIDADE E DA LICENÇA-PATERNIDADE CLT - LEI 11770/2008", 'aba' => 'CREDITO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "CRT009", 'descricao' => "CRÉDITO TRIBUTÁRIO PREVIDENCIÁRIO - PATRONAL E EMPREGADO", 'aba' => 'CREDITO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC001", 'descricao' => "ENCARGOS DE INSS S/ SALÁRIOS E REMUNERACÕES - RECOLHIMENTO POR GPS", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC002", 'descricao' => "ENCARGOS PATRONAIS - FGTS - RECOLHIMENTO POR GFIP", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC003", 'descricao' => "ENCARGOS PATRONAIS COM PIS/PASEP SOBRE FOLHA DE PAGAMENTO", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC004", 'descricao' => "ENCARGOS TRIBUTARIOS COM IRPJ - POR DARF", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC006", 'descricao' => "ENCARGOS SOCIAIS PARA OUTRAS ENTIDADES - SESI/SENAI ATIVO CIVIL ", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC007", 'descricao' => "ENCARGOS SOCIAIS - SALARIO EDUCAÇÃO - POR GPS", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC009", 'descricao' => "ENCARGOS PATRONAIS DE PSSS S/ VENCIMENTOS E VANTAGENS - UG EXTERIOR", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC010", 'descricao' => "ENCARGOS PATRONAIS - RPPS ", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC011", 'descricao' => "ENCARGOS PATRONAIS DE PSSS S/ VENCIMENTOS E VANTAGENS - POR DARF", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC012", 'descricao' => "ENCARGOS PATRONAIS - RGPS - PESSOAL REQUISITADO DE OUTROS ENTES", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC013", 'descricao' => "ENCARGOS PATRONAIS - FGTS  - PAGAMENTO POR OB", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC014", 'descricao' => "ENCARGOS PATRONAIS COM PREVIDÊNCIA PRIVADA E ASSIST. MÉDICO-ODONTOLÓGICA", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC015", 'descricao' => "ENCARGOS PATRONAIS COM PREVIDENCIA COMPLEMENTAR DE REGIME PRÓPRIO - FUNPRESP", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC017", 'descricao' => "ENCARGOS TRIBUTARIOS COM IMPOSTO TERRITORIAL RURAL - ITR", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC019", 'descricao' => "ENCARGOS PATRONAIS DE INSS S/ VENCIMENTOS E VANTAGENS - UG EXTERIOR", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC020", 'descricao' => "ENCARGOS PLANO DE PREVIDÊNCIA LOCAL - UG EXTERIOR", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC024", 'descricao' => "ENCARGO PATRONAIS SOBRE SERVIÇOS DE TERCEIROS - INSS", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC025", 'descricao' => "RECOLHIMENTO DE MULTAS P/FALTA DE CUMPRIMENTO DE OBRIGAÇÕES ACESSÓRIAS - DARF", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC027", 'descricao' => "ENCARGOS PATRONAIS DE TERCEIRIZADOS S/ VENCIMENTOS - UG EXTERIOR", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC029", 'descricao' => "ENCARGO PATRONAIS SOBRE SERVIÇOS DE TERCEIROS COM APROPRIAÇÃO DE BENS IMÓVEIS", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC030", 'descricao' => "ENCARGOS PATRONAIS DE PSSS S/ VENC. DE PESSOAL CEDIDO A OUTROS ÓRGÃOS/ENTES", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC031", 'descricao' => "ENCARGO PATRONAIS SOBRE SERVIÇOS DE TERCEIROS COM APROPRIAÇÃO DE BENS MÓVEIS", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC032", 'descricao' => "ENCARGOS PATRONAIS SOBRE SERVIÇOS DE TERCEIROS COM APROPRIAÇÃO DE ESTOQUE", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC033", 'descricao' => "ENCARGO PATRONAIS - FGTS - COM APROPRIAÇÃO DE BENS IMÓVEIS", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC038", 'descricao' => "OUTROS ENCARGOS SOCIAIS - POR DARF", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC039", 'descricao' => "ENCARGOS PATRONAIS - FUNPRESP S/ VENC. DE PESSOAL CEDIDO A OUTROS ÓRGÃOS/ENTES", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC040", 'descricao' => "ENCARGOS PATRONAIS S/ SALÁRIOS E REMUNERAÇÕES - RGPS - DARF NUMERADO", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ENC041", 'descricao' => "ENCARGOS PATRONAIS S/ SERVIÇOS DE TERCEIROS - RGPS - DARF NUMERADO", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PPV021", 'descricao' => "PAGAMENTO DE ENCARGO LIQUIDADO POR OUTRO DH E INSCRITO EM RPP - REALIZ. POR DARF", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PPV023", 'descricao' => "PAGAMENTO DE ENCARGO LIQUIDADO POR OUTRO DH E INSCRITO EM RPP - REALIZ. POR GPS", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PPV025", 'descricao' => "PAGAMENTO DE ENCARGO LIQUIDADO POR OUTRO DH E INSCRITO EM RPP - REALIZ. POR OB", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "PPV027", 'descricao' => "PAGAMENTO DE ENCARGO LIQUIDADO POR OUTRO DH E INSCRITO EM RPP - REALIZ. POR GFIP", 'aba' => 'ENCARGO', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL001", 'descricao' => "ANULAÇÃO DE DESPESA COM REMUNERAÇÃO A PESSOAL ATIVO CIVIL - RPPS", 'execsfsituacao_id' => '6', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL003", 'descricao' => "ANULAÇÃO DE DESPESA COM BENEFÍCIOS A PESSOAL - CIVIL RPPS", 'execsfsituacao_id' => '8', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL004", 'descricao' => "ANULAÇÃO DE DESPESA COM REMUNERAÇÃO A PESSOAL INATIVO CIVIL - RPPS", 'execsfsituacao_id' => '9', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL005", 'descricao' => "ANULAÇÃO DE DESPESA COM REMUNERAÇÃO A PESSOAL PENSIONISTA CIVIL - RPPS", 'execsfsituacao_id' => '10', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL011", 'descricao' => "ANULAÇÃO DE DESPESA COM REMUNERAÇÃO A PESSOAL ATIVO CIVIL - RGPS", 'execsfsituacao_id' => '12', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL013", 'descricao' => "ANULAÇÃO DE DESPESA COM BENEFÍCIOS A PESSOAL - CIVIL RGPS", 'execsfsituacao_id' => '14', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL014", 'descricao' => "ANULAÇÃO DE DESPESA COM REMUNERAÇÃO A PESSOAL INATIVO CIVIL - RGPS", 'execsfsituacao_id' => '15', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL015", 'descricao' => "ANULAÇÃO DE DESPESA COM REMUNERAÇÃO A PESSOAL PENSIONISTA CIVIL - RGPS", 'execsfsituacao_id' => '16', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL016", 'descricao' => "ANULAÇÃO DE DESPESA COM ENCARGOS DE BENEFÍCIOS PREVIDENCIÁRIOS DA UNIÃO - RGPS", 'execsfsituacao_id' => '17', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL017", 'descricao' => "ANULAÇÃO DE DESPESA COM REMUNERAÇÃO A PESSOAL ATIVO CIVIL - RGPS - NÃO SUBST.", 'execsfsituacao_id' => '18', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL021", 'descricao' => "ANULAÇÃO DE DESPESA COM REMUNERAÇÃO A PESSOAL ATIVO MILITAR", 'execsfsituacao_id' => '21', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL023", 'descricao' => "ANULAÇÃO DE DESPESA COM BENEFÍCIOS A PESSOAL - MILITAR", 'execsfsituacao_id' => '23', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL024", 'descricao' => "ANULAÇÃO DE DESPESA COM RESERVA REMUNERADA E REFORMAS - MILITAR", 'execsfsituacao_id' => '24', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL025", 'descricao' => "ANULAÇÃO DE DESPESA COM REMUNERAÇÃO A PESSOAL PENSIONISTA MILITAR", 'execsfsituacao_id' => '25', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL031", 'descricao' => "ANULAÇÃO DE DESPESA COM ADIANTAMENTOS DE 13º SALÁRIO", 'execsfsituacao_id' => '28', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL032", 'descricao' => "ANULAÇÃO DE DESPESA COM ADIANTAMENTOS DE 1/3 DE FÉRIAS", 'execsfsituacao_id' => '29', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL033", 'descricao' => "ANULAÇÃO DE DESPESA COM ADIANTAMENTOS DO SALÁRIO NO PERÍODO DE FÉRIAS", 'execsfsituacao_id' => '30', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL034", 'descricao' => "ANULAÇÃO DE DESPESA COM INDENIZAÇÕES E RESTITUIÇÕES TRABALHISTAS", 'execsfsituacao_id' => '31', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL035", 'descricao' => "ANULAÇÃO DE RESSARCIMENTO DE DESPESAS DE PESSOAL REQUISITADO DE OUTROS ÓRGÃOS", 'execsfsituacao_id' => '32', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL036", 'descricao' => "ANULAÇÃO DE DESPESA COM BENEFÍCIOS DE PRESTAÇÃO CONTINUADA", 'execsfsituacao_id' => '33', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL037", 'descricao' => "ANULAÇÃO DE DESPESA COM BENEFÍCIOS EVENTUAIS - INSS E FRGPS", 'execsfsituacao_id' => '34', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL038", 'descricao' => "ANULAÇÃO DE DESPESA COM OUTROS BENEFÍCIOS PREVIDENCIÁRIOS E ASSISTENCIAIS", 'execsfsituacao_id' => '35', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL039", 'descricao' => "ANULAÇÃO DE OUTRAS DESPESAS COM BENEFÍCIOS A PESSOAL", 'execsfsituacao_id' => '36', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL040", 'descricao' => "ANULAÇÃO DE OUTRAS DESPESAS COM PESSOAL INATIVO", 'execsfsituacao_id' => '37', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL041", 'descricao' => "ANULAÇÃO DE OUTRAS DESPESAS COM PESSOAL PENSIONISTA", 'execsfsituacao_id' => '38', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL042", 'descricao' => "ANULAÇÃO DE DESPESA COM COMPENSAÇÃO PREVIDENCIÁRIA", 'execsfsituacao_id' => '39', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL045", 'descricao' => "ANULAÇÃO DE DESPESA COM OUTROS SERVIÇOS DE TERCEIROS - PESSOA FÍSICA", 'execsfsituacao_id' => '42', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL046", 'descricao' => "ANULAÇÃO DE DESPESA COM REMUNERACAO A PESSOAL CEDIDO A OUTROS ÓRGÃOS OU ENTES", 'execsfsituacao_id' => '43', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL047", 'descricao' => "ANULAÇÃO DE DESPESA COM INDENIZAÇÕES E RESTITUIÇÕES DIVERSAS A PESSOAL", 'execsfsituacao_id' => '44', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL049", 'descricao' => "ANULAÇÃO DE DESPESAS COM INCENTIVOS A EDUCAÇÃO, CIÊNCIA, CULTURA, ESPORTE ETC", 'execsfsituacao_id' => '46', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL064", 'descricao' => "ANULAÇÃO DE DESPESA COM OUTROS SERVIÇOS DE TERCEIROS - PESSOA JURÍDICA", 'execsfsituacao_id' => '51', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL067", 'descricao' => "ANULAÇÃO DE DESPESA COM OBRIGAÇÕES TRABALHISTAS CONTRA INCORP.PASSIVOS", 'execsfsituacao_id' => '54', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "AFL072", 'descricao' => "ANULAÇÃO DE DESPESA COM PAGAMENTO DE 1/3 DE FÉRIAS - EMPRESAS ", 'execsfsituacao_id' => '57', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ANE001", 'descricao' => "ANULAÇÃO DE ENCARGOS DE INSS SOBRE SALÁRIOS E REMUNERACÕES", 'execsfsituacao_id' => '179', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ANE011", 'descricao' => "ANULAÇÃO DE ENCARGOS PATRONAIS DE PSSS SOBRE VENCIMENTOS E VANTAGENS", 'execsfsituacao_id' => '187', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => "ANE030", 'descricao' => "ANULAÇÃO DE ENCARGOS PATRONAIS DE PSSS S/ VENC. DE PESSOAL CEDIDO A OUTRO ÓRGÃO", 'execsfsituacao_id' => '199', 'aba' => 'DESPESA_ANULAR', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'CRD001', 'descricao' => 'BAIXA DO ADIANTAMENTO DE FÉRIAS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'CRD433', 'descricao' => 'BAIXA DO ADIANTAMENTO DE 13 SALÁRIO', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE001', 'descricao' => 'ESTORNO - DESPESA COM REMUNERACAO A PESSOAL ATIVO CIVIL - RPPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE003', 'descricao' => 'ESTORNO - DESPESA COM BENEFÍCIOS A PESSOAL - CIVIL RPPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE004', 'descricao' => 'ESTORNO - DESPESA COM REMUNERAÇÃO A PESSOAL INATIVO CIVIL - RPPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE005', 'descricao' => 'ESTORNO - DESPESA COM REMUNERAÇÃO A PESSOAL PENSIONISTA CIVIL - RPPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE011', 'descricao' => 'ESTORNO - DESPESA COM REMUNERAÇÃO A PESSOAL ATIVO CIVIL - RGPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE013', 'descricao' => 'ESTORNO - DESPESA COM BENEFÍCIOS A PESSOAL - CIVIL RGPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE014', 'descricao' => 'ESTORNO - DESPESA COM REMUNERAÇÃO A PESSOAL INATIVO CIVIL - RGPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE015', 'descricao' => 'ESTORNO - DESPESA COM REMUNERAÇÃO A PESSOAL PENSIONISTA CIVIL - RGPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE017', 'descricao' => 'ESTORNO - DESPESA COM REMUNERAÇÃO A PESSOAL ATIVO CIVIL - RGPS, NÃO SUBST. SERV.', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE021', 'descricao' => 'ESTORNO - DESPESA COM REMUNERAÇÃO A PESSOAL ATIVO MILITAR', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE023', 'descricao' => 'ESTORNO - DESPESA COM BENEFÍCIOS A PESSOAL - MILITAR', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE024', 'descricao' => 'ESTORNO - DESPESA COM RESERVA REMUNERADA E REFORMAS - MILITAR', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE025', 'descricao' => 'ESTORNO - DESPESA COM REMUNERAÇÃO A PESSOAL PENSIONISTA MILITAR', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE031', 'descricao' => 'ESTORNO - DESPESA COM ADIANTAMENTOS DE 13º SALARIO', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE032', 'descricao' => 'ESTORNO - DESPESA COM ADIANTAMENTOS DE 1/3 DE FÉRIAS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE033', 'descricao' => 'ESTORNO - DESPESA COM ADIANTAMENTOS DE SALÁRIO', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE034', 'descricao' => 'ESTORNO - DESPESA COM INDENIZAÇÕES E RESTITUIÇÕES TRABALHISTAS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE035', 'descricao' => 'ESTORNO - RESSARCIMENTO DE DESPESAS DE REQUISITADO DE OUTROS ÓRGÃOS/ENTES', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE036', 'descricao' => 'ESTORNO - DESPESA COM BENEFÍCIOS DE PRESTAÇÃO CONTINUADA', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE037', 'descricao' => 'ESTORNO - DESPESA COM BENEFÍCIOS DE PRESTAÇÃO EVENTUAIS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE038', 'descricao' => 'ESTORNO - DESPESA COM OUTROS BENEFÍCIOS PREVIDENCIÁRIOS E ASSISTENCIAIS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE039', 'descricao' => 'ESTORNO - OUTRAS DESPESAS COM BENEFÍCIOS A PESSOAL ', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE041', 'descricao' => 'ESTORNO - OUTRAS DESPESAS COM PESSOAL PENSIONISTA', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE042', 'descricao' => 'ESTORNO - DESPESA COM COMPENSAÇÃO PREVIDENCIÁRIA ENTRE REGIMES DE PREVIDÊNCIA', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE043', 'descricao' => 'ESTORNO - DESPESA COM PESSOAL ATIVO, COM INCORPORAÇÃO DE ATIVOS - UG EXTERIOR', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE044', 'descricao' => 'ESTORNO - DESPESA COM 13 SALÁRIO - FOLHA DE DEZEMBRO', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE045', 'descricao' => 'ESTORNO - DESPESA COM OUTROS SERVIÇOS DE TERCEIROS - PESSOA FÍSICA', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE046', 'descricao' => 'ESTORNO - DESPESA COM REMUNERACAO A PESSOAL CEDIDO A OUTROS ÓRGÃOS OU ENTES', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE047', 'descricao' => 'ESTORNO - DESPESAS COM INDENIZAÇÕES E RESTITUIÇÕES DIVERSAS A PESSOAL', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE049', 'descricao' => 'ESTORNO - DESPESA COM INCENTIVOS A EDUCAÇÃO, CIÊNCIA, CULTURA, ESPORTE E OUTROS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE064', 'descricao' => 'ESTORNO - DESPESA COM OUTROS SERVIÇOS DE TERCEIROS - PESSOA JURÍDICA', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE067', 'descricao' => 'ESTORNO - DESPESA COM OBRIGAÇÕES TRABALHISTAS A PAGAR', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFE069', 'descricao' => 'ESTORNO - DESPESAS COM RESTITUIÇÕES DIVERSAS A PESSOAL', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN001', 'descricao' => 'NORMAL - DESPESA COM REMUNERACAO A PESSOAL ATIVO CIVIL - RPPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN003', 'descricao' => 'NORMAL - DESPESA COM BENEFÍCIOS A PESSOAL - CIVIL RPPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN004', 'descricao' => 'NORMAL - DESPESA COM REMUNERAÇÃO A PESSOAL INATIVO CIVIL - RPPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN005', 'descricao' => 'NORMAL - DESPESA COM REMUNERAÇÃO A PESSOAL PENSIONISTA CIVIL - RPPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN011', 'descricao' => 'NORMAL - DESPESA COM REMUNERACAO A PESSOAL ATIVO CIVIL - RGPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN013', 'descricao' => 'NORMAL - DESPESA COM BENEFÍCIOS A PESSOAL - CIVIL RGPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN014', 'descricao' => 'NORMAL - DESPESA COM REMUNERAÇÃO A PESSOAL INATIVO CIVIL - RGPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN015', 'descricao' => 'NORMAL - DESPESA COM REMUNERAÇÃO A PESSOAL PENSIONISTA CIVIL - RGPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN017', 'descricao' => 'NORMAL - DESPESA COM REMUNERAÇÃO A PESSOAL ATIVO CIVIL - RGPS - NÃO SUBST.', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN021', 'descricao' => 'NORMAL - DESPESA COM REMUNERACAO A PESSOAL ATIVO MILITAR', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN023', 'descricao' => 'NORMAL - DESPESA COM BENEFÍCIOS A PESSOAL - MILITAR', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN024', 'descricao' => 'NORNAL - DESPESA COM RESERVA REMUNERADA E REFORMAS - MILITAR', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN025', 'descricao' => 'NORMAL - DESPESA COM REMUNERAÇÃO A PESSOAL PENSIONISTA MILITAR', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN031', 'descricao' => 'NORMAL - DESPESA COM ADIANTAMENTOS DE 13º SALÁRIO', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN032', 'descricao' => 'NORMAL - DESPESA COM ADIANTAMENTOS DE 1/3 DE FÉRIAS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN033', 'descricao' => 'NORMAL - DESPESA COM ADIANTAMENTOS DE SALÁRIOS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN034', 'descricao' => 'NORMAL - DESPESA COM INDENIZAÇÕES E RESTITUIÇÕES TRABALHISTAS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN035', 'descricao' => 'NORMAL - RESSARCIMENTO DE DESPESAS DE PESSOAL REQUISITADO DE OUTROS ÓRGÃOS/ENTES', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN036', 'descricao' => 'NORMAL - DESPESA COM BENEFÍCIOS DE PRESTAÇÃO CONTINUADA', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN037', 'descricao' => 'NORMAL - DESPESA COM BENEFÍCIOS DE PRESTAÇÃO EVENTUAIS ', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN038', 'descricao' => 'NORMAL - DESPESA COM OUTROS BENEFÍCIOS PREVIDENCIÁRIOS E ASSISTENCIAIS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN039', 'descricao' => 'NORMAL - OUTRAS DESPESA COM BENEFÍCIOS A PESSOAL ', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN041', 'descricao' => 'NORMAL - OUTRAS DESPESAS COM PESSOAL PENSIONISTA ', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN042', 'descricao' => 'NORMAL - DESPESA COM COMPENSAÇÃO PREVIDENCIÁRIA ENTRE REGIMES DE PREVIDÊNCIA', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN043', 'descricao' => 'NORMAL - DESPESA COM PESSOAL ATIVO, COM INCORPORAÇÃO DE ATIVOS - UG EXTERIOR', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN044', 'descricao' => 'NORMAL - DESPESA COM 13 SALÁRIO - FOLHA DE DEZEMBRO', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN045', 'descricao' => 'NORMAL - DESPESA COM OUTROS SERVIÇOS DE TERCEIROS - PESSOA FÍSICA', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN046', 'descricao' => 'NORMAL - DESPESA COM REMUNERACAO A PESSOAL CEDIDO A OUTROS ÓRGÃOS OU ENTES', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN047', 'descricao' => 'NORMAL - DESPESAS COM INDENIZAÇÕES E RESTITUIÇÕES DIVERSAS A PESSOAL', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN049', 'descricao' => 'NORMAL - DESPESA COM INCENTIVO A EDUCAÇÃO, CIÊNCIA, CULTURA, ESPORTES E OUTROS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN064', 'descricao' => 'NORMAL - DESPESA COM OUTROS SERVIÇOS DE TERCEIROS - PESSOA JURÍDICA', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN067', 'descricao' => 'NORMAL - DESPESA COM OBRIGAÇÕES TRABALHISTAS A PAGAR', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DFN069', 'descricao' => 'NORMAL - DESPESAS COM RESTITUIÇÕES DIVERSAS A PESSOAL', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DSE901', 'descricao' => 'ESTORNO - DESPESAS COM INDENIZAÇÕES E RESTITUIÇÕES DIVERSAS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DSE975', 'descricao' => 'ESTORNO - DESPESAS COM JUROS/ENCARGOS DE MORA DE OBRIGACOES TRIBUTARIAS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DSE983', 'descricao' => 'ESTORNO - DESPESAS COM INDENIZAÇÕES E RESTITUIÇÕES DIVERSAS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DSE998', 'descricao' => 'ESTORNO - REMANEJAMENTO DE PROGRAMAÇÃO FINANCEIRA DE DOCUMENTOS ELETRÔNICOS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DSE999', 'descricao' => 'ESTORNO - RECLASSIFICAÇÃO DE DESPESA COM REMANEJAMENTO DE LIMITE DE SAQUE', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DSN081', 'descricao' => 'NORMAL - DESPESAS COM DIÁRIAS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DSN901', 'descricao' => 'NORMAL - DESPESAS COM INDENIZAÇÕES E RESTITUIÇÕES DIVERSAS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DSN975', 'descricao' => 'NORMAL - DESPESAS COM JUROS/ENCARGOS DE MORA DE OBRIGACOES TRIBUTARIAS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DSN983', 'descricao' => 'NORMAL - DESPESAS COM INDENIZAÇÕES E RESTITUIÇÕES DIVERSAS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DSN998', 'descricao' => 'NORMAL - REMANEJAMENTO DE PROGRAMAÇÃO FINANCEIRA DE DOCUMENTOS ELETRÔNICOS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'DSN999', 'descricao' => 'NORMAL - RECLASSIFICAÇÃO DE DESPESA COM REMANEJAMENTO DE LIMITE DE SAQUE', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENE001', 'descricao' => 'ESTORNO - ENCARGOS DE INSS S/ SALÁRIOS E REMUNERACÕES - RECOLHIMENTO POR GPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENE002', 'descricao' => 'ESTORNO - ENCARGOS PATRONAIS - FGTS - RECOLHIMENTO POR GFIP', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENE003', 'descricao' => 'ESTORNO - ENCARGOS PATRONAIS COM PIS/PASEP SOBRE FOLHA DE PAGAMENTO', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENE006', 'descricao' => 'ESTORNO - ENCARGOS SOCIAIS GERAIS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENE010', 'descricao' => 'ESTORNO - ENCARGOS PATRONAIS - RPPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENE011', 'descricao' => 'ESTORNO - ENCARGOS PATRONAIS DE PSSS S/ VENCIMENTOS E VANTAGENS - POR DARF', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENE014', 'descricao' => 'ESTORNO - ENCARGOS PATRONAIS COM PREVIDÊNCIA PRIV. E ASSIST. MÉDICO-ODONTOLÓGICA', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENE015', 'descricao' => 'ESTORNO - ENCARGOS PATRONAIS COM PREV. COMPLEMENTAR DE REGIME PRÓPRIO - FUNPRESP', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENE021', 'descricao' => 'ESTORNO - ENCARGOS TRIBUTÁRIOS COM A UNIÃO - RECOLHIMENTO POR DARF', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENE024', 'descricao' => 'ESTORNO - ENCARGO PATRONAIS SOBRE SERVIÇOS DE TERCEIROS - INSS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENE025', 'descricao' => 'ESTORNO - MULTAS P/ FALTA DE CUMPRIMENTO DE OBRIGAÇÕES ACESSÓRIAS - DARF', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENE029', 'descricao' => 'ESTORNO- ENCARGOS PATRONAIS S/ SERVIÇOS DE TERCEIROS C/ APROPRIAÇÃO DE BENS IMOV', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENE030', 'descricao' => 'ESTORNO - ENCARGOS PATRONAIS DE PSSS S/ VENC. DE PESSOAL CEDIDO A OUTROS ÓRGÃOS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENE039', 'descricao' => 'ESTORNO - ENCARGOS PATRONAIS FUNPRESP S/ VENC. DE PESSOAL CEDIDO A OUTROS ÓRGÃOS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENN001', 'descricao' => 'NORMAL - ENCARGOS DE INSS S/ SALÁRIOS E REMUNERACÕES - RECOLHIMENTO POR GPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENN002', 'descricao' => 'NORMAL - ENCARGOS PATRONAIS - FGTS - RECOLHIMENTO POR GFIP', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENN003', 'descricao' => 'NORMAL - ENCARGOS PATRONAIS COM PIS/PASEP SOBRE FOLHA DE PAGAMENTO', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENN006', 'descricao' => 'NORMAL - ENCARGOS SOCIAIS GERAIS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENN010', 'descricao' => 'NORMAL - ENCARGOS PATRONAIS - RPPS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENN011', 'descricao' => 'NORMAL - ENCARGOS PATRONAIS DE PSSS S/ VENCIMENTOS E VANTAGENS - POR DARF', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENN014', 'descricao' => 'NORMAL - ENCARGOS PATRONAIS COM PREVIDÊNCIA PRIV. E ASSIST. MÉDICO-ODONTOLÓGICA', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENN015', 'descricao' => 'NORMAL - ENCARGOS PATRONAIS COM PREV. COMPLEMENTAR DE REGIME PRÓPRIO - FUNPRESP', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENN021', 'descricao' => 'NORMAL - ENCARGOS TRIBUTÁRIOS COM A UNIÃO - RECOLHIMENTO POR DARF', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENN024', 'descricao' => 'NORMAL - ENCARGO PATRONAIS SOBRE SERVIÇOS DE TERCEIROS - INSS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENN025', 'descricao' => 'NORMAL - MULTAS P/ FALTA DE CUMPRIMENTO DE OBRIGAÇÕES ACESSÓRIAS - DARF', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENN029', 'descricao' => 'NORMAL - ENCARGOS PATRONAIS S/ SERVIÇOS DE TERCEIROS C/ APROPRIAÇÃO DE BENS IMOV', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENN030', 'descricao' => 'NORMAL - ENCARGOS PATRONAIS DE PSSS S/ VENC. DE PESSOAL CEDIDO A OUTROS ÓRGÃOS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'ENN039', 'descricao' => 'NORMAL - ENCARGOS PATRONAIS FUNPRESP S/ VENC. DE PESSOAL CEDIDO A OUTROS ÓRGÃOS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'EST005', 'descricao' => 'REGULAR. OB CANC.(218913603)-VALOR NÃO DEV. C/ESTORNO DESP.PESSOAL/BENEF/DIÁRIAS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'EST010', 'descricao' => 'REGULARIZAÇÃO OB CANC. (218913603)-VALOR NÃO DEVIDO C/ESTORNO DE OUTRAS DESPESAS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'EST015', 'descricao' => 'REGULAR. OB CANC. (218913603)-VALOR NÃO DEV. C/EST. DE DESP. PESSOAL - SENT. JUD', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'EST016', 'descricao' => 'REGULARIZAÇÃO VPA BRUTA CLASSIF ARRECADADAS P/GPS 49101.01.03 C/ESTORNO DESPESA', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LDV014', 'descricao' => 'CONTROLE DE AUXÍLIO MORADIA', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LDV033', 'descricao' => 'BAIXA DO CONTROLE DE AUXÍLIO MORADIA', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LDV046', 'descricao' => 'AJUSTE DO C/C DA CONTA DE CONTROLE DE AUXÍLIO MORADIA - 89991.07.00', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LPA301', 'descricao' => 'APROPRIAÇÃO DE PESSOAL E ENCARGOS A PAGAR SEM SUPORTE ORCAMENTÁRIO - CURTO PRAZO', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LPA302', 'descricao' => 'APROPRIAÇÃO DE BENEFÍCIOS PREVID. E ASSIST. A PAGAR SEM SUPORTE ORCAMENTÁRIO', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LPA330', 'descricao' => 'APROPRIAÇÃO DE PASSIVO CIRCULANTE - AJUSTES DE EXERCICIOS ANTERIORES ', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LPA332', 'descricao' => 'APROPRIAÇÃO DE PASSIVOS CIRCULANTES, COM ISF "P", C/C 030 - TP + NR TRANSF.', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LPA335', 'descricao' => 'APROPRIAÇÃO PASSIVOS NÃO CIRCULANTES (ISF P) - C/C 002', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LPA349', 'descricao' => 'APROPRIAÇÃO DE PASSIVO NÃO CIRCULANTE - AJUSTES DE EXERCICIOS ANTERIORES ', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LPA351', 'descricao' => 'APROPRIAÇÃO DE PESSOAL E ENCARGOS A PAGAR SEM SUPORTE ORCAMENTÁRIO - LONGO PRAZO', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LPA355', 'descricao' => 'APROPRIAÇÃO DE PASSIVOS NÃO CIRCULANTES (ISF P)', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LPA356', 'descricao' => 'BAIXA DO PASSIVO DE PESSOAL E ENCARGOS A PAGAR SEM SUPORTE ORCAMENTÁRIO', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LPA357', 'descricao' => 'BAIXA DE BENEFÍCIOS PREVID. E ASSIST. A PAGAR SEM SUPORTE ORCAMENTÁRIO', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LPA360', 'descricao' => 'BAIXA DE PASSIVOS CIRCULANTES, COM ISF "P", C/C 030 - TP + NR TRANSF APROP NO EX', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LPA361', 'descricao' => 'APROPRIAÇÃO PASSIVOS CIRCULANTES, C/ISF "P", C/C 030 - TP + NR TRANSF. EXER ANT', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LPA362', 'descricao' => 'BAIXA PASSIVOS CIRCULANTES, C/ISF "P", C/C 030 - TP + NR TRANSF APROP NO EX ANT', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LPA385', 'descricao' => 'REVERSAO VPD DE REMUNERAÇÃO COM BAIXA DE PASSIVO FERIAS A PAGAR', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'LPA386', 'descricao' => 'REVERSAO VPD DE REMUNERACAO COM APROPRIACAO DE ADIANTAMENTO DE FERIAS', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'PPE001', 'descricao' => 'ESTORNO - EMPENHO INSCRITO EM RPP NO EXERCÍCIO DE 2014', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'PPE002', 'descricao' => 'ESTORNO - EMPENHO INSCRITO EM RPNP EM LIQUIDAÇÃO NO EXERCÍCIO DE 2014', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'PPN001', 'descricao' => 'NORMAL - EMPENHO INSCRITO EM RPP NO EXERCÍCIO DE 2014', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'PPN002', 'descricao' => 'NORMAL - EMPENHO INSCRITO EM RPNP EM LIQUIDAÇÃO NO EXERCÍCIO DE 2014', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'PRV001', 'descricao' => 'APROPRIAÇÃO MENSAL DO 13 SALÁRIO A PAGAR - PESSOAL ATIVO', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'PRV002', 'descricao' => 'APROPRIAÇÃO MENSAL DE FÉRIAS A PAGAR - PESSOAL ATIVO', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'PRV003', 'descricao' => 'APROPRIAÇÃO MENSAL DO 13 SALÁRIO A PAGAR - PESSOAL INATIVO E PENSIONISTA', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'PRV005', 'descricao' => 'BAIXA DE ADIANTAMENTOS DA FOLHA DE PAGAMENTO - AJUSTE DE EXERCÍCIOS ANTERIORES', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'PRV006', 'descricao' => 'BAIXA DE 13 SALÁRIO E FÉRIAS A PAGAR -  AJUSTE DE EXERCÍCIOS ANTERIORES', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'PRV014', 'descricao' => 'BAIXA DE PESSOAL A PAGAR (LONGO PRAZO) -  AJUSTE DE EXERCÍCIOS ANTERIORES', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'PRV021', 'descricao' => 'APROPRIAÇÃO DE OUTRAS PROVISÕES CURTO OU LONGO PRAZO - AJUSTE  EXERC ANT - IG ', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'PRV022', 'descricao' => 'REVERSÃO PROVISÕES MATEMÁTICAS PREVIDENCIÁRIAS - AJUSTE DE EXERC ANTERIORES', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
        DB::table('execsfsituacao')->insert(['codigo' => 'PRV023', 'descricao' => 'APROPRIAÇÃO DE PROVISÕES MATEMÁTICAS PREVIDENC LONGO PRAZO - AJUSTE  EXERC ANT', 'aba' => 'OUTROSLANCAMENTOS', 'status' => true]);
    }
}














































































