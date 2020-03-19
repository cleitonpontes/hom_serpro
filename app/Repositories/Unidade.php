<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class Unidade extends Base
{

    /**
     * Retorna lista de unidades que, conforme perfil, leva em consideração $orgao e $unidade
     *
     * @param string $orgao
     * @param string $unidade
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getUnidadesParaComboPorPerfil($orgao = '', $unidade = '')
    {
        $query = \App\Models\Unidade::select(DB::raw("CONCAT(codigo, ' - ', nomeresumido) AS nome"), 'id');
        $query->orderBy('codigo', 'asc');

        if (backpack_user()->hasRole('Administrador Órgão') && $orgao != 'Todas') {
            $query->where('orgao_id', $orgao);
        }

        if (backpack_user()->hasRole('Administrador Unidade') && $unidade != 'Todas') {
            $query->where('id', $unidade);
        }

        return $query->pluck('nome', 'id')->toArray();
    }

}
