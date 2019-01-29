<?php
use Illuminate\Database\Seeder;

class ExecsiafisituacaoUpdateSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('execsfsituacao')->where('aba', 'PCO')->update(['categoria_ddp' => 1]);
        DB::table('execsfsituacao')->where('aba', 'DESPESA_ANULAR')->update(['categoria_ddp' => 2]);
        DB::table('execsfsituacao')->where('aba', 'DEDUCAO')->update(['categoria_ddp' => 3]);
        DB::table('execsfsituacao')->where('aba', 'ENCARGO')->update(['categoria_ddp' => 6]);
        DB::table('execsfsituacao')->where('aba', 'OUTROSLANCAMENTOS')->update(['categoria_ddp' => 7]);
    }
}