<?php

use Illuminate\Database\Seeder;

class FormaPagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('codigos')->insert([
            'descricao' => 'Forma Pagamento',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $itemCodigo = \App\Models\Codigo::all()->last()->id;

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '149',
            'descricao' => 'Isento',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '68',
            'descricao' => 'Boleto',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '89',
            'descricao' => 'Empenho',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
