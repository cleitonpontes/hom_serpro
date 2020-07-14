<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class UpdateNumeroContratoeHistoricoSeeder extends Seeder
{
    private $tabela;
    private $campo;
    private $tamanhoCampo;
    private $preenchimento;
    private $relacionamentos;

    public function __construct()
    {   
        $this->tabela = "contratos";
        $this->campo = "numero";
        $this->tamanhoCampo = 10;
        $this->preenchimento = "0";
        //Criado em forma de array, para que possiveis futuras atualizações sejam realizadas na mesma transação
        $this->relacionamentos = ['contratohistorico'];
    }

    public function run()
    {
        DB::transaction(function () {
            $this->completarMascara($this->tabela,$this->campo,$this->tamanhoCampo, $this->preenchimento);
            foreach ($this->relacionamentos as $key => $tabelaRelacionada) {
                $this->completarMascara($tabelaRelacionada,$this->campo,$this->tamanhoCampo, $this->preenchimento);
            }
        });
    }

    private function completarMascara($tabela, $campo, $tamanhoCampo, $preenchimento){
        //Atualizar a tabela X, onde o campo P seja menor que T. 
        //Formate o campo utilizando a função LPAD, preenchendo-o com Z até o tamanho T
        $contAtualizados = DB::table($tabela)
        ->whereRaw("LENGTH(".$campo.") < ?", $tamanhoCampo)
        ->update(
            [$campo => DB::raw("lpad(".$campo.",".$tamanhoCampo.",'".$preenchimento."')")]
        );
        var_dump($contAtualizados);
        //$this->contaErrados($tabela,$campo,$tamanhoCampo);
    }
/*
    private function contaErrados($tabela, $campo, $tamanhoCampo){
        $errados = DB::table($tabela)->whereRaw("LENGTH(".$campo.") > ?", $tamanhoCampo)->get();
        var_dump($errados->count());
    }
    */
}
