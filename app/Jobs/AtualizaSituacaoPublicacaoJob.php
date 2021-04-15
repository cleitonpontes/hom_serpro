<?php

namespace App\Jobs;


use App\Http\Controllers\Publicacao\DiarioOficialClass;
use App\Http\Traits\Formatador;
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
    use Formatador;

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
        $this->diarioOficial->setSoapClient();
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

            $retorno = $this->diarioOficial->consultaSituacaoOficio($this->publicacao);

            if($retorno->out->validacaoIdOficio == "OK"){

                $status = $retorno->out->acompanhamentoOficio->acompanhamentoMateria->DadosAcompanhamentoMateria->estadoMateria;

                if($status != "PUBLICADA"){
                    $tipoSituacao = 'TRANSFERIDO PARA IMPRENSA';
                    $this->atualizaPublicacao($retorno,$tipoSituacao);
                }else{
                    $tipoSituacao = 'PUBLICADO';
                    $this->atualizaPublicacao($retorno,$tipoSituacao);
                }

            }

            DB::commit();

        } catch (Exception $exc) {
            DB::rollback();
            fail($exc);
        }
    }


    public function atualizaPublicacao($retorno,$tipoSituacao)
    {
        $status = $retorno->out->acompanhamentoOficio->acompanhamentoMateria->DadosAcompanhamentoMateria->estadoMateria;
        $link = $retorno->out->acompanhamentoOficio->acompanhamentoMateria->DadosAcompanhamentoMateria->linkPublicacao;
        $pagina = $retorno->out->acompanhamentoOficio->acompanhamentoMateria->DadosAcompanhamentoMateria->paginaPublicacao;
        $motivo_devolucao = $retorno->out->acompanhamentoOficio->acompanhamentoMateria->DadosAcompanhamentoMateria->motivoDevolucao;
        $status_publicacao_id = $this->retornaIdTipoSituacao($tipoSituacao);

        ContratoPublicacoes::where('id', $this->publicacao->id)
            ->where('contratohistorico_id', $this->publicacao->contratohistorico_id)
            ->update([
                'status_publicacao_id' => $status_publicacao_id,
                'status' => $status,
                'link_publicacao' => $link,
                'pagina_publicacao' => $pagina,
                'motivo_devolucao' => $motivo_devolucao,
                'secao_jornal' => 3
            ]);

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
