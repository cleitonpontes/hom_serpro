<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class JustificativafaturaSeeder extends Seeder
{
    public function run()
    {
        DB::table('justificativafatura')->insert([ 'nome' => 'Art. 5º §1º inciso I', 'descricao' => 'grave perturbação da ordem, situação de emergência ou calamidade pública', 'situacao' => 1]);
        DB::table('justificativafatura')->insert([ 'nome' => 'Art. 5º §1º inciso II', 'descricao' => 'pagamento a microempresa, empresa de pequeno porte e demais beneficiários do Decreto nº 8.538, de 6 de outubro de 2015, desde que demonstrado o risco de descontinuidade do cumprimento do objeto do contrato.', 'situacao' => 1]);
        DB::table('justificativafatura')->insert([ 'nome' => 'Art. 5º §1º inciso III', 'descricao' => 'pagamento de serviços necessários ao funcionamento dos sistemas estruturantes do Governo Federal, desde que demonstrado o risco de descontinuidade do cumprimento do objeto do contrato.', 'situacao' => 1]);
        DB::table('justificativafatura')->insert([ 'nome' => 'Art. 5º §1º inciso IV', 'descricao' => 'pagamento de direitos oriundos de contratos em caso de falência, recuperação judicial ou dissolução da empresa contratada.', 'situacao' => 1]);
        DB::table('justificativafatura')->insert([ 'nome' => 'Art. 5º §1º inciso V', 'descricao' => 'pagamento de contrato cujo objeto seja imprescindível para assegurar a integridade do patrimônio público ou para manter o funcionamento das atividades finalísticas do órgão ou entidade, quando demonstrado o risco de descontinuidade da prestação de um serviço público de relevância ou o cumprimento da missão institucional.', 'situacao' => 1]);
        DB::table('justificativafatura')->insert([ 'nome' => 'Pendências Cadastrais', 'descricao' => 'Pendências Cadastrais tais como Pendências Certidões, Pebdência SICAF, Pendências Judiciais e outras.', 'situacao' => 1]);
        DB::table('justificativafatura')->insert([ 'nome' => 'Ordem Lista', 'descricao' => 'Seguindo a ordem cronológica da lista.', 'situacao' => 1]);
    }
}
