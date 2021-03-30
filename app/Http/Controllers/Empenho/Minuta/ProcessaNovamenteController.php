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
        $date_time = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        if($dados){
            foreach ($dados as $dado){
                $updated_at = \DateTime::createFromFormat('Y-m-d H:i:s', $dado->updated_at)->modify('+15 minutes');
                if($date_time > $updated_at){
                    $dado->txtdescricao .= ' ';
                    $dado->situacao = 'EM PROCESSAMENTO';
                    $dado->save();
                }
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
