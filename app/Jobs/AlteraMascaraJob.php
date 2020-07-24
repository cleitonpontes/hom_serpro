<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class AlteraMascaraJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $tabela;
    private $campo;
    private $tamanhoCampo;
    private $preenchimento;
    private $relacionamentos;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tabela,$campo,$tamanhoCampo,$preenchimento,$relacionamentos)
    {
        $this->tabela = $tabela;
        $this->campo = $campo;
        $this->tamanhoCampo = $tamanhoCampo;
        $this->preenchimento = $preenchimento;
        //Criado em forma de array, para que possiveis futuras atualizações sejam realizadas na mesma transação
        $this->relacionamentos = $relacionamentos;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
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
    }
    
}
