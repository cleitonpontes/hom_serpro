<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApropriacaofasesSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fases
        DB::table('apropriacoes_fases')->insert(['id' => 0, 'fase' => 'Não iniciada']);
        DB::table('apropriacoes_fases')->insert(['id' => 1, 'fase' => 'Importar DDP']);
        DB::table('apropriacoes_fases')->insert(['id' => 2, 'fase' => 'Identificar Situação']);
        DB::table('apropriacoes_fases')->insert(['id' => 3, 'fase' => 'Identificar Empenhos']);
        DB::table('apropriacoes_fases')->insert(['id' => 4, 'fase' => 'Validar Saldos']);
        DB::table('apropriacoes_fases')->insert(['id' => 5, 'fase' => 'Informar Dados Complementares']);
        DB::table('apropriacoes_fases')->insert(['id' => 6, 'fase' => 'Persistir Dados']);
        DB::table('apropriacoes_fases')->insert(['id' => 7, 'fase' => 'Gerar XML']);
        DB::table('apropriacoes_fases')->insert(['id' => 8, 'fase' => 'Finalizada']);
    }
}
