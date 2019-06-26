<?php

namespace App\Observers;

use App\Jobs\ProcessaCatmatseratualizacaoJob;
use App\Models\Catmatseratualizacao;
use App\Models\Catmatsergrupo;
use App\Models\Catmatseritem;
use App\Models\Codigoitem;

class CatmatseratualizacaoObserver
{
    /**
     * Handle the catmatseratualizacao "created" event.
     *
     * @param  \App\Catmatseratualizacao  $catmatseratualizacao
     * @return void
     */
    public function created(Catmatseratualizacao $catmatseratualizacao)
    {
        ProcessaCatmatseratualizacaoJob::dispatch($catmatseratualizacao);
    }

    /**
     * Handle the catmatseratualizacao "updated" event.
     *
     * @param  \App\Catmatseratualizacao  $catmatseratualizacao
     * @return void
     */
    public function updated(Catmatseratualizacao $catmatseratualizacao)
    {
        //
    }

    /**
     * Handle the catmatseratualizacao "deleted" event.
     *
     * @param  \App\Catmatseratualizacao  $catmatseratualizacao
     * @return void
     */
    public function deleted(Catmatseratualizacao $catmatseratualizacao)
    {
        //
    }

    /**
     * Handle the catmatseratualizacao "restored" event.
     *
     * @param  \App\Catmatseratualizacao  $catmatseratualizacao
     * @return void
     */
    public function restored(Catmatseratualizacao $catmatseratualizacao)
    {
        //
    }

    /**
     * Handle the catmatseratualizacao "force deleted" event.
     *
     * @param  \App\Catmatseratualizacao  $catmatseratualizacao
     * @return void
     */
    public function forceDeleted(Catmatseratualizacao $catmatseratualizacao)
    {
        //
    }

    public function lerArquivo(Catmatseratualizacao $catmatseratualizacao)
    {
        $path = env('APP_PATH')."storage/app/";
        $itens = [];

        if(file_exists($path.$catmatseratualizacao->arquivo)) {

            $arquivo = explode('/',$catmatseratualizacao->arquivo);

            if($fh = fopen($path.$catmatseratualizacao->arquivo,'r')){
                while (!feof($fh)){
                    $line = fgets($fh,9999);
                    $dado = explode('~|~', $line);

                    if($dado[0] != 'tipo_descricao'){
                        if(isset($dado[1])){
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

                if($iten['situacao'] == 'Ativo'){
                    $situacao = true;
                }else{
                    $situacao = false;
                }

                $tipo = Codigoitem::whereHas('codigo', function ($query) {
                    $query->where('descricao', '=', 'Tipo CATMAT e CATSER');
                })
                    ->where('descricao', '=', $iten['tipo'])
                    ->first();

                $grupo = Catmatsergrupo::where('descricao','=',$iten['grupo'])
                    ->first();

                if(!$grupo){
                    $grupoarray = [
                        'tipo_id' => $tipo->id,
                        'descricao' => $iten['grupo'],
                    ];
                    $grupo = new Catmatsergrupo();
                    $grupo->fill($grupoarray);
                    $grupo->save();
                }

                $item = Catmatseritem::where('grupo_id','=',$grupo->id)
                    ->where('codigo_siasg','=',$iten['codigo_siasg'])
                    ->first();

                if($item){
                    $item->descricao = $iten['descricao'];
                    $item->situacao = $situacao;
                    $item->save();
                }else{

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

            return true;

        }
    }
}
