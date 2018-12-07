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
        $this->call(UserSeeder::class);
        $this->call(CodigoItemSeeder::class);
        $this->call(FornecedorSeeder::class);
        $this->call(ContratoSeeder::class);
    }
}
