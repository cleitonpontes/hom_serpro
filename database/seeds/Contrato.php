<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class ContratoSeeder extends Seeder
{
    public function run()
    {
        DB::table('contratos')->insert(['numero' => '0050/2015', 'tipo_id' => '60', 'fornecedor_id' => '1', 'unidade_id' => '1', 'categoria_id' => '56', 'processo' => '00676.001195/2015-57', 'objeto' => 'DISTRIBUIÇÃO DA DA PUBLICIDADE LEGAL IMPRESSA E/OU ELETRÔNICA DE INTERESSE DA AGU.', 'data_assinatura' => '2015-12-09', 'vigencia_inicio' => '2015-12-09', 'vigencia_fim' => '2020-12-08', 'valor_inicial' => '10000.00', 'valor_global' => '10000.00', 'num_parcelas' => '12', 'valor_parcela' => '833.33', 'valor_acumulado' => '10000.00', 'situacao' => '1']);
        DB::table('contratos')->insert(['numero' => '0059/2014', 'tipo_id' => '60', 'fornecedor_id' => '2', 'unidade_id' => '1', 'categoria_id' => '57', 'processo' => '00405.003456/2014-82', 'objeto' => 'PRESTAÇÃO DOS SERVIÇOS DE PROCESSAMENTO E HOSPEDAGEM DO SISTEMA SICAP NO AMBIENTE TECNOLÓGICO DO SERPRO.', 'data_assinatura' => '2018-09-28', 'vigencia_inicio' => '2018-09-30', 'vigencia_fim' => '2018-10-31', 'valor_inicial' => '332.88', 'valor_global' => '332.88', 'num_parcelas' => '1', 'valor_parcela' => '332.88', 'valor_acumulado' => '19066.50', 'situacao' => '1']);
        DB::table('contratos')->insert(['numero' => '0019/2018', 'tipo_id' => '60', 'fornecedor_id' => '3', 'unidade_id' => '1', 'categoria_id' => '57', 'processo' => '00693.000038/2018-69', 'objeto' => 'O OBJETO DO PRESENTE INSTRUMENTO É A CONTRATAÇÃO DE SERVIÇOS DE ACESSO DEDICADO À INTERNET COM FORNECIMENTO DE INFRAESTRUTURA, IMPLANTAÇÃO, CONFIGURAÇÃO E DISPONIBILIZAÇÃO DE FERRAMENTAS DE GERENCIAMENTO E MANUTENÇÃO DE UMA REDE DE SERVIÇOS DE DADOS PARA ACESSO IP PERMANENTE, DEDICADO E EXCLUSIVO, ENTRE A REDE DA AGU EM BRASÍLIA-DF E A REDE MUNDIAL DE COMPUTADORES INTERNET, ATRAVÉS DE ENLACES COM LARGURA MÁXIMA DE 2 GB COM DUPLA ABORDAGEM E COM PAGAMENTO PROPORCIONAL A BANDA CONTRATADA, QUE SERÃO PRESTADOS NAS CONDIÇÕES ESTABELECIDAS NO PROJETO BÁSICO.', 'data_assinatura' => '2018-06-13', 'vigencia_inicio' => '2018-06-13', 'vigencia_fim' => '2019-06-12', 'valor_inicial' => '18192.29', 'valor_global' => '18192.29', 'num_parcelas' => '12', 'valor_parcela' => '1516.02', 'valor_acumulado' => '18192.29', 'situacao' => '1']);
        DB::table('contratos')->insert(['numero' => '0020/2015', 'tipo_id' => '60', 'fornecedor_id' => '3', 'unidade_id' => '1', 'categoria_id' => '57', 'processo' => '00676.000146/2015-05', 'objeto' => 'CONTRATAÇÃO DE SOLUÇÃO DE TECNOLOGIA DA INFORMAÇÃO PARA PRESTAÇÃO DE SERVIÇOS DE PRODUÇÃO DO SISTEMA INTEGRADO DE ADMINISTRAÇÃO DE SERVIÇOS  SIADS, ABRANGENDO IMPLANTAÇÃO, HOSPEDAGEM DE DADOS, CONSULTORIA E DISPONIBILIZAÇÃO DO ACESSO POR MEIO DE SENHA CONFIÁVEL.', 'data_assinatura' => '2018-07-28', 'vigencia_inicio' => '2018-07-29', 'vigencia_fim' => '2019-07-29', 'valor_inicial' => '3701.86', 'valor_global' => '3701.86', 'num_parcelas' => '12', 'valor_parcela' => '308.49', 'valor_acumulado' => '17301.20', 'situacao' => '1']);

    }
}
