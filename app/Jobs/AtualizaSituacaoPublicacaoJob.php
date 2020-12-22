<?php

namespace App\Jobs;


use App\Http\Controllers\Publicacao\DiarioOficialClass;
use App\Models\Codigoitem;
use App\Models\ContratoPublicacoes;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class AtualizaSituacaoPublicacaoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var ContratoPublicacoes
     */
    private $publicacao;
    private $diarioOficial;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ContratoPublicacoes $publicacao)
    {
        $this->publicacao = $publicacao;
        $this->diarioOficial = new DiarioOficialClass();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();
        try {

            $retorno = $this->diarioOficial->consultaSituacaoOficio($this->publicacao->id);

            if($retorno->out->validacaoIdOficio == "OK"){

                $status = $retorno->out->acompanhamentoOficio->acompanhamentoMateria->DadosAcompanhamentoMateria->estadoMateria;

                if($status != "PUBLICADA"){
                    $tipoSituacao = 'TRANSFERIDO PARA IMPRENSA';
                    $this->atualizaPublicacao($status,$tipoSituacao);
                }else{
                    $tipoSituacao = 'PUBLICADO';
                    $this->atualizaPublicacao($status,$tipoSituacao);
                }

            }

            DB::commit();

        } catch (Exception $exc) {
            DB::rollback();
            fail($exc);
        }
    }


    public function atualizaPublicacao($status,$tipoSituacao)
    {
        $this->publicacao->status_publicacao_id = $this->retornaIdTipoSituacao($tipoSituacao);
        $this->publicacao->status = $status;
        $this->publicacao->save();
    }

    public function retornaIdTipoSituacao($tipoSituacao)
    {
        return Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Situacao Publicacao');
        })
            ->where('descricao', '=', $tipoSituacao)
            ->first()->id;
    }

}
