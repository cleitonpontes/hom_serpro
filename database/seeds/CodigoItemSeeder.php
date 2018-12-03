<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class CodigoItemSeeder extends Seeder
{
    public function run()
    {
        DB::table('codigos')->insert([
            'descricao' => 'Tipo Unidade',
            'visivel' => false,
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => '1',
            'descres' => 'E',
            'descricao' => 'Executora',
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => '1',
            'descres' => 'C',
            'descricao' => 'Setorial Contábil',
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => '1',
            'descres' => 'F',
            'descricao' => 'Setorial Financeira',
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => '1',
            'descres' => 'B',
            'descricao' => 'Beneficiada / Controle',
        ]);

        DB::table('codigos')->insert(['descricao' => 'Itens SIAFI','visivel' => false,]);
        DB::table('codigoitens')->insert(['codigo_id' => '2','descres' => 'DADOBASICO', 'descricao' => 'Dados Básicos']);
        DB::table('codigoitens')->insert(['codigo_id' => '2','descres' => 'PCO', 'descricao' => 'Principal com Orçamento']);
        DB::table('codigoitens')->insert(['codigo_id' => '2','descres' => 'DEDUCAO', 'descricao' => 'Dedução']);
        DB::table('codigoitens')->insert(['codigo_id' => '2','descres' => 'OUTROLANC', 'descricao' => 'Outros Lançamentos']);
        DB::table('codigoitens')->insert(['codigo_id' => '2','descres' => 'ENCARGO', 'descricao' => 'Encargos']);
        DB::table('codigoitens')->insert(['codigo_id' => '2','descres' => 'CCUSTO', 'descricao' => 'Centro de Custo']);
        DB::table('codigoitens')->insert(['codigo_id' => '2','descres' => 'DESPANULAR', 'descricao' => 'Despesa a Anular']);
        DB::table('codigoitens')->insert(['codigo_id' => '2','descres' => 'PCOITEM', 'descricao' => 'PCO Item']);
        DB::table('codigoitens')->insert(['codigo_id' => '2','descres' => 'DADOSPGTO', 'descricao' => 'Dados Pagamento']);


        DB::table('codigos')->insert(['descricao' => 'Tipo PREDOCS','visivel' => false,]);
        DB::table('codigoitens')->insert(['codigo_id' => '3','descres' => 'PREDOCDARF','descricao' => 'PreDOC DARF']);
        DB::table('codigoitens')->insert(['codigo_id' => '3','descres' => 'PREDOCDAR','descricao' => 'PreDOC DAR']);
        DB::table('codigoitens')->insert(['codigo_id' => '3','descres' => 'PREDOCOB','descricao' => 'PreDOC OB']);
        DB::table('codigoitens')->insert(['codigo_id' => '3','descres' => 'PREDOCGPS','descricao' => 'PreDOC GPS']);
        DB::table('codigoitens')->insert(['codigo_id' => '3','descres' => 'PREDOCGRU','descricao' => 'PreDOC GRU']);


        DB::table('codigos')->insert(['descricao' => 'Tipo Dom. Bancario','visivel' => false,]);
        DB::table('codigoitens')->insert(['codigo_id' => '4','descres' => 'DOMBANCFAV','descricao' => 'Domicílio Bancário Favorecido']);
        DB::table('codigoitens')->insert(['codigo_id' => '4','descres' => 'DOMBANCPAG','descricao' => 'Domicílio Bancário Pagamento']);

        DB::table('codigos')->insert(['descricao' => 'Tipo Rel Item','visivel' => false,]);
        DB::table('codigoitens')->insert(['codigo_id' => '5','descres' => 'RELPCOITEM','descricao' => 'Rel. PCO Item']);
        DB::table('codigoitens')->insert(['codigo_id' => '5','descres' => 'RELPSOITEM','descricao' => 'Rel. PSO Item']);
        DB::table('codigoitens')->insert(['codigo_id' => '5','descres' => 'RELCREDITO','descricao' => 'Rel. Crédito']);

        DB::table('codigos')->insert(['descricao' => 'Tipo Rel Item Valor','visivel' => false,]);
        DB::table('codigoitens')->insert(['codigo_id' => '6','descres' => 'RELPCOITEM','descricao' => 'Rel. PCO Item Valor']);
        DB::table('codigoitens')->insert(['codigo_id' => '6','descres' => 'RELPSOITEM','descricao' => 'Rel. PSO Item Valor']);
        DB::table('codigoitens')->insert(['codigo_id' => '6','descres' => 'RELENCARGO','descricao' => 'Rel. Encargo Valor']);
        DB::table('codigoitens')->insert(['codigo_id' => '6','descres' => 'RELOULAN'  ,'descricao' => 'Rel. Outros Lançamentos Item Valor']);
        DB::table('codigoitens')->insert(['codigo_id' => '6','descres' => 'REOULACRPA','descricao' => 'Rel. Outros Lan. Cron. Patrimonial Valor']);
        DB::table('codigoitens')->insert(['codigo_id' => '6','descres' => 'RELACREDED','descricao' => 'Rel. Acres. Dedução Item Valor']);
        DB::table('codigoitens')->insert(['codigo_id' => '6','descres' => 'RELACREENC','descricao' => 'Rel. Acres. Encargo Item Valor']);
        DB::table('codigoitens')->insert(['codigo_id' => '6','descres' => 'RELACREPGT','descricao' => 'Rel. Acres. Dados Pgto Item Valor']);
        DB::table('codigoitens')->insert(['codigo_id' => '6','descres' => 'RELDESPANT','descricao' => 'Rel. Despesa Antecipada Item Valor']);
        DB::table('codigoitens')->insert(['codigo_id' => '6','descres' => 'RELDESPANU','descricao' => 'Rel. despesa Anular Item Valor']);

        DB::table('codigos')->insert(['descricao' => 'Categorias Docs Padrões','visivel' => false,]);
        DB::table('codigoitens')->insert(['codigo_id' => '7','descres' => 'EXECEXCEL','descricao' => 'Execução Excel' ]);

        DB::table('codigos')->insert(['descricao' => 'Tipo Garantia','visivel' => true,]);
        DB::table('codigoitens')->insert(['codigo_id' => '8','descres' => 'DEPCAUCAO','descricao' => 'Depósito Caução' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '8','descres' => 'FIANCABANC','descricao' => 'Fiança Bancária' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '8','descres' => 'SEGGARANTI','descricao' => 'Seguro Garantia' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '8','descres' => 'TITDIVPUB','descricao' => 'Título da Dívida Pública' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '8','descres' => 'NENHUMA','descricao' => 'Nenhuma' ]);

        DB::table('codigos')->insert(['descricao' => 'Tipo Fornecedor','visivel' => false,]);
        DB::table('codigoitens')->insert(['codigo_id' => '9','descres' => 'FISICA','descricao' => 'Pessoa Física' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '9','descres' => 'JURIDICA','descricao' => 'Pessoa Jurídica' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '9','descres' => 'IDGENERICO','descricao' => 'ID Genérico / UG Siafi' ]);

        DB::table('codigos')->insert(['descricao' => 'Função Contrato','visivel' => true,]);
        DB::table('codigoitens')->insert(['codigo_id' => '10','descres' => 'GESTOR','descricao' => 'Gestor' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '10','descres' => 'FSCREQ','descricao' => 'Fiscal Requisitante' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '10','descres' => 'FSCTEC','descricao' => 'Fiscal Técnico' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '10','descres' => 'FSCADM','descricao' => 'Fiscal Administrativo' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '10','descres' => 'FSCTIT','descricao' => 'Fiscal Titular' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '10','descres' => 'FSCSUB','descricao' => 'Fiscal Substituto' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '10','descres' => 'GESTORSUB','descricao' => 'Gestor Substituto' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '10','descres' => 'FSCREQSUB','descricao' => 'Fiscal Requisitante Substituto' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '10','descres' => 'FSCTECSUB','descricao' => 'Fiscal Técnico Substituto' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '10','descres' => 'FSCADMSUB','descricao' => 'Fiscal Administrativo Substituto' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '10','descres' => 'FSCSET','descricao' => 'Fiscal Setorial' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '10','descres' => 'FSCSETSUB','descricao' => 'Fiscal Setorial Substituto' ]);

        DB::table('codigos')->insert(['descricao' => 'Categoria Contrato','visivel' => true,]);
        DB::table('codigoitens')->insert(['codigo_id' => '11','descres' => '60','descricao' => 'Comum' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '11','descres' => '48','descricao' => 'Informática' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '11','descres' => '999','descricao' => 'Locação Imóveis' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '11','descres' => '48','descricao' => 'Locação Equipamentos' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '11','descres' => '999','descricao' => 'Internacional' ]);

        DB::table('codigos')->insert(['descricao' => 'Tipo de Contrato SIASG','visivel' => false,]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '50','descricao' => 'Contrato' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '51','descricao' => 'Credenciamento' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '52','descricao' => 'Comodato' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '53','descricao' => 'Arrendamento' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '54','descricao' => 'Concessão' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '55','descricao' => 'Termo Aditivo' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '56','descricao' => 'Termo de Adesão' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '57','descricao' => 'Convênio' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '60','descricao' => 'Termo de Apostilamento' ]);

    }
}
