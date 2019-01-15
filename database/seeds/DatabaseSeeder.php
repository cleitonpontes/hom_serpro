<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(OrgaoUnidadeSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CodigoItemSeeder::class);
        $this->call(FornecedorSeeder::class);
        $this->call(ContratoSeeder::class);
        $this->call(InstalacaoSeeder::class);
        $this->call(JustificativafaturaSeeder::class);
        $this->call(TipolistafaturaSeeder::class);
        $this->call(NaturezadespesaSeeder::class);
        $this->call(NaturezasubitemSeeder::class);
        $this->call(PlanointernoSeeder::class);
        $this->call(EmpenhosSeeder::class);
    }
}
