<?php

namespace App\Jobs;

use App\Models\Catmatseratualizacao;
use App\Models\Catmatsergrupo;
use App\Models\Catmatseritem;
use App\Models\Codigoitem;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessaCatmatseratualizacaoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $catmatseratualizacao;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Catmatseratualizacao $catmatseratualizacao)
    {
        $this->catmatseratualizacao = $catmatseratualizacao;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = env('APP_PATH')."storage/app/";
        $itens = [];

        if(file_exists($path.$this->catmatseratualizacao->arquivo)) {

            $arquivo = explode('/', $this->catmatseratualizacao->arquivo);

            if ($fh = fopen($path . $this->catmatseratualizacao->arquivo, 'r')) {
                while (!feof($fh)) {
                    $line = fgets($fh, 9999);
                    $dado = explode('~|~', $line);

                    if ($dado[0] != 'tipo_descricao') {
                        if (isset($dado[1])) {
                            $itens[] = [
                                'tipo' => trim($dado[0]),
                                'grupo' => strtoupper(trim($dado[1])),
                                'codigo_siasg' => trim($dado[2]),
                                'descricao' => strtoupper(trim($dado[3])),
                                'situacao' => trim($dado[4]),
                            ];
                        }
                    }

                }
                fclose($fh);
            }

            foreach ($itens as $iten) {

                if ($iten['situacao'] == 'Ativo') {
                    $situacao = true;
                } else {
                    $situacao = false;
                }

                $tipo = Codigoitem::whereHas('codigo', function ($query) {
                    $query->where('descricao', '=', 'Tipo CATMAT e CATSER');
                })
                    ->where('descricao', '=', $iten['tipo'])
                    ->first();

                $grupo = Catmatsergrupo::where('descricao', '=', $iten['grupo'])
                    ->first();

                if (!$grupo) {
                    $grupoarray = [
                        'tipo_id' => $tipo->id,
                        'descricao' => $iten['grupo'],
                    ];
                    $grupo = new Catmatsergrupo();
                    $grupo->fill($grupoarray);
                    $grupo->save();
                }

                $item = Catmatseritem::where('grupo_id', '=', $grupo->id)
                    ->where('codigo_siasg', '=', $iten['codigo_siasg'])
                    ->first();

                if ($item) {
                    $item->descricao = $iten['descricao'];
                    $item->situacao = $situacao;
                    $item->save();
                } else {

                    $itemarray = [
                        'grupo_id' => $grupo->id,
                        'codigo_siasg' => $iten['codigo_siasg'],
                        'descricao' => $iten['descricao'],
                        'situacao' => $situacao
                    ];

                    $item = new Catmatseritem();
                    $item->fill($itemarray);
                    $item->save();
                }


            }
        }

        $this->catmatseratualizacao->situacao = 'L';
        $this->catmatseratualizacao->save();

    }
}
