<?php

namespace App\Console\Commands;

use App\Models\Codigoitem;
use App\Models\Siasgcompra;
use App\Models\Unidade;
use Illuminate\Console\Command;
use PhpParser\Node\Stmt\Break_;

class MigracaoSiasgCommand extends Command
{


    protected $signature = 'importacao:siasg
                            {--tipo= : Tipo da importação: compras|contratos}
                            {--arquivo= : Caminho completo do arquivo .txt, que contenha os Id\'s de Compras ou Contratos}
                            ';

    protected $description = 'Comando para importação das Compras ou Contratos Ativos no SIASG para automatizar a migração de dados';

    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        if (!$this->option('tipo') or !$this->option('arquivo')) {
            $this->line('As Opções "--tipo" e "--arquivo" são obrigatórias para esse Comando.');
        }

        if ($this->option('tipo') == 'compras') {
            if(!file_exists ($this->option('arquivo'))){
                $this->line('Arquivos não encontrado!');
                return false;
            }

            $this->line('Tipo importação: '.$this->option('tipo'));
            $this->line('');
            $this->line('Iniciando leitura do arquivo: '.$this->option('arquivo'));
            $this->line('------------------------------------------------------------');
            $file = $this->buscaArquivo($this->option('arquivo'));
            $compras = new Siasgcompra;
            $dados = [];
            $i = 0;
            while (!feof($file)) {

                $this->line('Lendo linha: '.$i);
                $line = fgets($file);

                $unidade = Unidade::where('codigosiasg', substr($line, 0, 6))
                    ->first();

                $modalidade = Codigoitem::whereHas('codigo', function ($c) {
                    $c->where('descricao', '=', 'Modalidade Licitação');
                })
                    ->where('descres', substr($line, 6, 2))
                    ->first();

                $numero = substr($line, 8, 5);
                $ano = substr($line, 13, 4);

                if(isset($unidade->id)){
                    $busca = $compras->where('unidade_id', $unidade->id)
                        ->where('modalidade_id', $modalidade->id)
                        ->where('ano', $ano)
                        ->where('numero', $numero)
                        ->first();

                    if (!isset($busca->id)) { 
                        $dados = [
                            'unidade_id' => $unidade->id,
                            'modalidade_id' => $modalidade->id,
                            'ano' => $ano,
                            'numero' => $numero,
                            'situacao' => 'Pendente'
                        ];

                        $compranova = new Siasgcompra();
                        $compranova->fill($dados);
                        $compranova->save();

                        $this->line('Compra: '.$line.' cadastrada com sucesso.');

                    }else{
                        $busca->situacao = 'Pendente';
                        $busca->save();
                        $this->line('Compra: '.$line.' já possui cadastro.');
                    }
                }else{
                    $this->line('Unidade: '.substr($line, 0, 6).' não encontrada.');
                }
                $this->line('------------------------------------------------------------');
                $i++;
            }
            fclose($file);
            $this->line('');
            $this->line('Lido '.$i.' linhas!');

        } else {
            $this->line('A Opção "--tipo" deve ser compras ou contratos.');
            $this->line('Ex: "php artisan importacao:siasg --tipo=compras --arquivo=/var/www/sc/storage/app/importacao/compras.txt"');
        }
    }

    private function buscaArquivo($arquivo)
    {
        $file = fopen(env('CARGA_SIASG_PATH').$arquivo, "r");
        return $file;
    }

    private function lerArquivo()
    {
        $file = fopen(env('CARGA_INICIAL_COMPRAS'), "r");
        $compras = new Siasgcompra;
        $dados = [];
        while (!feof($file)) {
            $line = fgets($file);

            $unidade = Unidade::where('codigosiasg', substr($line, 0, 6))
                ->first();

            $modalidade = Codigoitem::whereHas('codigo', function ($c) {
                $c->where('descricao', '=', 'Modalidade Licitação');
            })
                ->where('descres', substr($line, 6, 2))
                ->first();

            $numero = substr($line, 8, 5);
            $ano = substr($line, 13, 4);

            if(isset($unidade->id)){
                $busca = $compras->where('unidade_id', $unidade->id)
                    ->where('modalidade_id', $modalidade->id)
                    ->where('ano', $ano)
                    ->where('numero', $numero)
                    ->first();

                if (!isset($busca->id)) {
                    $dados = [
                        'unidade_id' => $unidade->id,
                        'modalidade_id' => $modalidade->id,
                        'ano' => $ano,
                        'numero' => $numero,
                        'situacao' => 'Pendente'
                    ];

                    $compranova = new Siasgcompra();
                    $compranova->fill($dados);
                    $compranova->save();
                }
            }
        }
        fclose($file);
    }

}
