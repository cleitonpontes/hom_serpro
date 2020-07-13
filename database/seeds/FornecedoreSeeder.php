<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class FornecedorSeeder extends Seeder
{
    public function run()
    {
        DB::table('fornecedores')->insert(['tipo_fornecedor' => 'JURIDICA', 'cpf_cnpj_idgener' => '08.823.749/0001-40', 'nome' => 'R. PINHEIRO GRIMM TRANSPORTES EPP']);
        DB::table('fornecedores')->insert(['tipo_fornecedor' => 'UG', 'cpf_cnpj_idgener' => '115406', 'nome' => 'EMPRESA BRASIL DE COMUNICAÇÃO']);
        DB::table('fornecedores')->insert(['tipo_fornecedor' => 'UG', 'cpf_cnpj_idgener' => '803010', 'nome' => 'SERPRO REGIONAL BRASILIA']);

    }
}
