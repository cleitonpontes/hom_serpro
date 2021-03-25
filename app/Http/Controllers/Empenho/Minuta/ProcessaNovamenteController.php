<?php

namespace App\Http\Controllers\Empenho\Minuta;

use App\Models\SfOrcEmpenhoDados;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProcessaNovamenteController extends Controller
{
    public function index()
    {
        $dados = $this->buscaSforcempenhodadosEmProcessamento();

        if($dados){
            foreach ($dados as $dado){
                $dado->txtdescricao .= ' ';
                $dado->situacao = 'EM PROCESSAMENTO';
                $dado->save();
            }
        }
    }

    private function buscaSforcempenhodadosEmProcessamento()
    {
        $sforcempenhodados = SfOrcEmpenhoDados::where('situacao', 'EM PROCESSAMENTO')
            ->whereNotNull('sfnonce')
            ->orderBy('created_at', 'ASC')
            ->get();

        return $sforcempenhodados;
    }
}
