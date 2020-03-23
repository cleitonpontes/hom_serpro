<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class OrgaoSuperior extends Base
{

    /**
     * Retorna lista de órgãos, com o nome concatenado com a descrição
     *
     * @param int $orgao
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getOrgaosParaCombo($orgao = null)
    {
        $query = \App\Models\OrgaoSuperior::select(DB::raw("CONCAT(codigo, ' - ', nome) AS nome"), 'id');
        $query->orderBy('codigo', 'asc');

        if (backpack_user()->hasRole('Administrador Órgão') && $orgao != 'Todos') {
            $query->where('id', $orgao);
        }

        return $query->pluck('nome', 'id')->toArray();
    }

}
