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

        DB::table('codigos')->insert(['descricao' => 'Tipo Fornecedor','visivel' => false,]);
        DB::table('codigoitens')->insert(['codigo_id' => '9','descres' => 'FISICA','descricao' => 'Pessoa Física' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '9','descres' => 'JURIDICA','descricao' => 'Pessoa Jurídica' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '9','descres' => 'UG','descricao' => 'UG Siafi' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '9','descres' => 'IDGENERICO','descricao' => 'ID Genérico' ]);

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

        DB::table('codigos')->insert(['descricao' => 'Tipo de Contrato','visivel' => false,]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '50','descricao' => 'Contrato' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '51','descricao' => 'Credenciamento' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '52','descricao' => 'Comodato' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '53','descricao' => 'Arrendamento' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '54','descricao' => 'Concessão' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '55','descricao' => 'Termo Aditivo' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '56','descricao' => 'Termo de Adesão' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '57','descricao' => 'Convênio' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '12','descres' => '60','descricao' => 'Termo de Apostilamento' ]);

        DB::table('codigos')->insert(['descricao' => 'Modalidade Licitação','visivel' => false,]);
        DB::table('codigoitens')->insert(['codigo_id' => '13','descres' => 'ADESAO','descricao' => 'Adesão' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '13','descres' => 'ATARP','descricao' => 'Ata RP' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '13','descres' => 'CONCORRE','descricao' => 'Concorrência' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '13','descres' => 'CONCURSO','descricao' => 'Concurso' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '13','descres' => 'CONVITE','descricao' => 'Convite' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '13','descres' => 'DISPENSA','descricao' => 'Dispensa' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '13','descres' => 'INEXIGIBI','descricao' => 'Inexigibilidade' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '13','descres' => 'PREGAO','descricao' => 'Pregão' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '13','descres' => 'TOMPRECO','descricao' => 'Tomada de Preços' ]);

        DB::table('codigos')->insert(['descricao' => 'Escolaridade','visivel' => false,]);
        DB::table('codigoitens')->insert(['codigo_id' => '14','descres' => 'S EXIG', 'descricao' => '00 – Sem Exigência']);
        DB::table('codigoitens')->insert(['codigo_id' => '14','descres' => 'N LER', 'descricao' => '01 – Não sabe Ler/Escrever']);
        DB::table('codigoitens')->insert(['codigo_id' => '14','descres' => 'ALFAB', 'descricao' => '02 – Alfabetizado']);
        DB::table('codigoitens')->insert(['codigo_id' => '14','descres' => 'EFI', 'descricao' => '03 – Ensino Fundamental Incompleto']);
        DB::table('codigoitens')->insert(['codigo_id' => '14','descres' => 'EFC', 'descricao' => '04 – Ensino Fundamental Completo']);
        DB::table('codigoitens')->insert(['codigo_id' => '14','descres' => 'EMI', 'descricao' => '05 – Ensino Médio Incompleto']);
        DB::table('codigoitens')->insert(['codigo_id' => '14','descres' => 'EMC', 'descricao' => '06 – Ensino Médio Completo']);
        DB::table('codigoitens')->insert(['codigo_id' => '14','descres' => 'SUPI', 'descricao' => '07 – Superior Incompleto']);
        DB::table('codigoitens')->insert(['codigo_id' => '14','descres' => 'SUPC', 'descricao' => '08 – Superior Completo']);
        DB::table('codigoitens')->insert(['codigo_id' => '14','descres' => 'ESPEC', 'descricao' => '09 – Especialização/Residência']);
        DB::table('codigoitens')->insert(['codigo_id' => '14','descres' => 'CTEC', 'descricao' => '10 – Curso Técnico Completo']);
        DB::table('codigoitens')->insert(['codigo_id' => '14','descres' => 'PGRAD', 'descricao' => '11 – Pós-Graduação']);
        DB::table('codigoitens')->insert(['codigo_id' => '14','descres' => 'MESTRE', 'descricao' => '12 – Mestrado']);
        DB::table('codigoitens')->insert(['codigo_id' => '14','descres' => 'DOUTOR', 'descricao' => '13 – Doutorado']);

        DB::table('codigos')->insert(['descricao' => 'Mão de Obra','visivel' => true,]);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'AUXSERVDIV', 'descricao' => 'Auxiliar de Serviços Diversos']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'AUXSERVREP', 'descricao' => 'Auxiliar de Serviços de Reprografia']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'BRIGADISTA', 'descricao' => 'Brigadista']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'COPEIRA', 'descricao' => 'Copeiragem']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'GARCOM', 'descricao' => 'Garçom']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'LIMPEZA', 'descricao' => 'Limpeza e Conservação']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'RECEPCIONA', 'descricao' => 'Recepcionista']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'TELEFONIST', 'descricao' => 'Telefonista']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'VIGIA', 'descricao' => 'Vigilante']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'MOTORISTA', 'descricao' => 'Motorista']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'PORTEIRO', 'descricao' => 'Porteiro']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'MECANAR', 'descricao' => 'Mecânico de Ar Condicionado e Refrigeração']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'ELETRICIST', 'descricao' => 'Eletricista']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'MANUPREDIA', 'descricao' => 'Manutenção Predial']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'ENCANADOR', 'descricao' => 'Encanador']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'PINTOR', 'descricao' => 'Pintor']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'AUXILIARCO', 'descricao' => 'Auxiliar Mecânico de Ar Condicionado']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'CONTINUO', 'descricao' => 'Contínuo']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'LAVACARRO', 'descricao' => 'Lavador de Carro']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'ALMOXARIFE', 'descricao' => 'Almoxarife']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'CARREGADOR', 'descricao' => 'Carregador de Móveis']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'ENCARREGAD', 'descricao' => 'Encarregado']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'AJUDANTE', 'descricao' => 'Ajudante']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'ARQUITETO', 'descricao' => 'Arquiteto']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'BOMBEIROH', 'descricao' => 'Bombeiro Hidráulico']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'ENGENHEIRO', 'descricao' => 'Engenheiro Civil']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'ENGENHEELE', 'descricao' => 'Engenheiro Elétrico']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'MARCENEIRO', 'descricao' => 'Marceneiro']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'OPPLANTDI', 'descricao' => 'Operador Plantonista Diurno']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'OPPLANTNO', 'descricao' => 'Operador Plantonista Noturno']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'PEDREIRO', 'descricao' => 'Pedreiro']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'SERRALHEI', 'descricao' => 'Serralheiro']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'TAPECEIRO', 'descricao' => 'Tapeceiro']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'VIDRACEIRO', 'descricao' => 'Vidraceiro']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'ASCENSORI', 'descricao' => 'Ascensorista']);
        DB::table('codigoitens')->insert(['codigo_id' => '15', 'descres' => 'AUXADM', 'descricao' => 'Auxiliar Administrativo']);

        DB::table('codigos')->insert(['descricao' => 'Situação Ocorrência','visivel' => false,]);
        DB::table('codigoitens')->insert(['codigo_id' => '16','descres' => 'PENDENTE','descricao' => 'Pendente' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '16','descres' => 'ATENDIDA','descricao' => 'Atendida' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '16','descres' => 'PARCIAL','descricao' => 'Atendida Parcial' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '16','descres' => 'NAOATEND','descricao' => 'Não Atendida' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '16','descres' => 'CONCLUSIVA','descricao' => 'Conclusiva' ]);

        DB::table('codigos')->insert(['descricao' => 'Tipo Arquivos Contrato','visivel' => false,]);
        DB::table('codigoitens')->insert(['codigo_id' => '17','descres' => 'CONTRATO','descricao' => 'Contrato' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '17','descres' => 'ADITIVO','descricao' => 'Termo Aditivo' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '17','descres' => 'APOSTILA','descricao' => 'Termo Apostilamento' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '17','descres' => 'TR','descricao' => 'Termo Referência' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '17','descres' => 'RESCISAO','descricao' => 'Termo Rescisão' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '17','descres' => 'EMPENHO','descricao' => 'Nota de Empenho' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '17','descres' => 'GARANTIA','descricao' => 'Garantia' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '17','descres' => 'PLANILHA','descricao' => 'Planilha Custo' ]);
        DB::table('codigoitens')->insert(['codigo_id' => '17','descres' => 'OUTROS','descricao' => 'Outros Arquivos' ]);

        DB::table('codigos')->insert(['descricao' => 'Abas Situações','visivel' => false,]);
        DB::table('codigoitens')->insert(['codigo_id' => '18','descres' => 'PCO', 'descricao' => 'PCO']);
        DB::table('codigoitens')->insert(['codigo_id' => '18','descres' => 'PCO', 'descricao' => 'PSO']);
        DB::table('codigoitens')->insert(['codigo_id' => '18','descres' => 'DEDUCAO', 'descricao' => 'DEDUCAO']);
        DB::table('codigoitens')->insert(['codigo_id' => '18','descres' => 'OUTROLANC', 'descricao' => 'OUTROSLANCAMENTOS']);
        DB::table('codigoitens')->insert(['codigo_id' => '18','descres' => 'ENCARGO', 'descricao' => 'ENCARGO']);
        DB::table('codigoitens')->insert(['codigo_id' => '18','descres' => 'DESPANULAR', 'descricao' => 'DESPESA_ANULAR']);
        DB::table('codigoitens')->insert(['codigo_id' => '18','descres' => 'CREDITO', 'descricao' => 'CREDITO']);


        DB::table('codigos')->insert(['descricao' => 'Tipo CATMAT e CATSER','visivel' => false,]);
        DB::table('codigoitens')->insert(['codigo_id' => '19','descres' => 'MATERIAL', 'descricao' => 'Material']);
        DB::table('codigoitens')->insert(['codigo_id' => '19','descres' => 'SERVIÇO', 'descricao' => 'Serviço']);

    }
}
