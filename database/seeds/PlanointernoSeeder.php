<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class PlanointernoSeeder extends Seeder
{
    public function run()
    {
        DB::table('planointerno')->insert(['codigo' => 'AGU0001', 'descricao' => 'SISTEMAS DE INFORMATICA', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0002', 'descricao' => 'SERVICOS DE JARDINAGEM', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0003', 'descricao' => 'CAPACITACAO RECURSOS HUMANOS - JURIDICA', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0004', 'descricao' => 'SERV DE DEDETIZACAO,DESRATIZACAO E DESCUPINIZ', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0005', 'descricao' => 'DESPESAS PROCESSUAIS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0006', 'descricao' => 'ASSISTENCIA MEDICA E ODONTOLOGICA', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0007', 'descricao' => 'ASSISTENCIA PRE-ESCOLAR', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0008', 'descricao' => 'AUXILIO-TRANSPORTE', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0009', 'descricao' => 'AUXILIO-ALIMENTACAO', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0010', 'descricao' => 'REMUNERACAO DE PESSOAL ATIVO', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0011', 'descricao' => 'ENCARGOS COM INATIVOS E PENSIONISTAS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0012', 'descricao' => 'BENEFICIOS ASSISTENCIAIS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0013', 'descricao' => 'EQUIPAMENTOS, SERV. E SOFTWARE DE INFRA DE TI', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0014', 'descricao' => 'MANUTENCAO E SUPORTE DE EQUIPAMENTOS DE TI', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0015', 'descricao' => 'AQUISICAO, SUBSCRICAO E ATUALIZAC DE SOFTWARE', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0016', 'descricao' => 'EQUIPAMENTOS E ACESSORIOS DE MICRO-INFORMATIC', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0017', 'descricao' => 'EXPEDICAO DE CORRESPONDENCIAS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0018', 'descricao' => 'AMPLIACAO E CONSERVACAO DO ACERVO BIBLIOGRAFI', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0019', 'descricao' => 'ASSINATURAS DE PERIODICOS E ANUIDADES', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0020', 'descricao' => 'AMPLIACAO DA FROTA DE VEICULOS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0021', 'descricao' => 'AQUISICAO DE MOBILIARIO', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0022', 'descricao' => 'LOCACAO DE MATERIAIS E ATIVOS DE TI', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0023', 'descricao' => 'MANUTENCAO DE EQUIPAMENTOS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0024', 'descricao' => 'LOCACAO DE EQUIP.DE REPROGRAFIA E OUTSOURCING', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0025', 'descricao' => 'MANUTENCAO DE MOBILIARIO', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0026', 'descricao' => 'AQUISICAO DE MATERIAL PERMANENTE', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0027', 'descricao' => 'LOCACAO DE IMOVEIS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0028', 'descricao' => 'REFORMA DE PREDIOS E INSTALACOES', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0029', 'descricao' => 'DESPESAS COM CONDOMINIOS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0030', 'descricao' => 'MATERIAL DE CONSUMO E EXPEDIENTE', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0031', 'descricao' => 'MANUTENCAO DE AR-CONDICIONADO', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0032', 'descricao' => 'SERVICOS DE VIGILANCIA', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0033', 'descricao' => 'SERVICOS DE AGUA E COLETA DE ESGOTO', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0034', 'descricao' => 'PUBLICIDADE LEGAL E INSTITUCIONAL', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0035', 'descricao' => 'COMBUSTIVEIS E LUBRIFICANTES', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0036', 'descricao' => 'SUPRIMENTO DE FUNDOS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0037', 'descricao' => 'PASSAGENS E DESPESAS COM LOCOMOCAO', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0038', 'descricao' => 'DIARIAS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0039', 'descricao' => 'OUTRAS DESPESAS DE TERCEIROS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0040', 'descricao' => 'SERVICOS DE LIMPEZA', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0041', 'descricao' => 'SERVICOS DE TELECOMUNICACOES', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0042', 'descricao' => 'MANUTENCAO E CONSERVACAO DE VEICULOS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0043', 'descricao' => 'SERVICO DE COMUNICACAO DE DADOS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0044', 'descricao' => 'SERVICOS DE GARCOM', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0045', 'descricao' => 'MANUTENCAO DE ELEVADORES', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0046', 'descricao' => 'PROGRAMA DE ESTAGIO PROFISSIONAL', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0047', 'descricao' => 'SERVICOS DE PORTARIA', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0048', 'descricao' => 'SERVICOS DE RECEPCAO', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0049', 'descricao' => 'DEFESA DA UNIAO NO EXTERIOR', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0050', 'descricao' => 'SERVICOS DE OPERADOR DE REPROGRAFIA', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0051', 'descricao' => 'CAPACITACAO RECURSOS HUMANOS - ADMINISTRATIVO', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0052', 'descricao' => 'SERVICOS DE ENERGIA ELETRICA', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0053', 'descricao' => 'APOIO AS ACOES DE INFORMATICA', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0054', 'descricao' => 'DESENV. E MANUT. DE SIST.E PORTAIS PROPRIOS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0055', 'descricao' => 'IMPOSTOS E TAXAS EM GERAL', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0056', 'descricao' => 'REDE LOGICA ESTRUTURADA', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0057', 'descricao' => 'SERVICOS DE AUXILIAR DE SERVICOS DIVERSOS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0058', 'descricao' => 'EVENTOS DE TREINAMENTO', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0059', 'descricao' => 'MANUTENCAO E CONSERVACAO PREDIAL', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU005C', 'descricao' => 'DESPESAS PROCESSUAIS - CUSTAS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU005H', 'descricao' => 'DESPESAS PROCESSUAIS - HONORARIOS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU005M', 'descricao' => 'DESPESAS PROCESSUAIS - MULTAS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0060', 'descricao' => 'SERVICOS DE BRIGADAS DE INCENDIO', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0061', 'descricao' => 'LOCACAO DE VEICULOS E/OU COOPERATIVAS DE TAXI', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0062', 'descricao' => 'SERVICO DE COPEIRAGEM', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0063', 'descricao' => 'SERVICOS DE TRANSPORTADORA', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0064', 'descricao' => 'SEGUROS DE VEICULOS OFICIAIS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0065', 'descricao' => 'SEGUROS DE IMOVEIS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0066', 'descricao' => 'CONFECCAO E INSTALACAO DE PERSIANAS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0067', 'descricao' => 'RESSARCIMENTO DE AUXILIO-MORADIA', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0068', 'descricao' => 'AJUDA DE CUSTO', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0069', 'descricao' => 'BOLSA AUXILIO', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0070', 'descricao' => 'CENTRAL TELEFONICA', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0071', 'descricao' => 'SERVICOS DE CHAVEIRO', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0072', 'descricao' => 'INSTALACAO DE DIVISORIAS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0073', 'descricao' => 'SERVICOS DE TELEFONISTA', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0074', 'descricao' => 'GRATIFICACAO P/ ENCARGO CURSO/CONCURSO', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0075', 'descricao' => 'AQUISICAO DE PAPEL - PES', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0076', 'descricao' => 'RATEIO DE DESPESAS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0077', 'descricao' => 'DESPESAS SEM COBERTURA CONTRATUAL', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0078', 'descricao' => 'DESPESAS COM REALIZACAO DE CONCURSO PUBLICO', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0079', 'descricao' => 'SERVICOS DE CONSULTORIA E ASSESSORIA TECNICA', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0080', 'descricao' => 'HOSPEDAGEM E ACESSO AOS SISTEMAS EXTERNOS', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0081', 'descricao' => 'SUPORTE A INFRAESTRUTURA E USUARIOS DE TI', 'situacao' => 1]);
        DB::table('planointerno')->insert(['codigo' => 'AGU0082', 'descricao' => 'CONSULTORIA, TREINAMENTO E APOIO EM TI', 'situacao' => 1]);

    }
}
