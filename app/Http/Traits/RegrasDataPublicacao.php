<?php

namespace App\Http\Traits;

use App\Models\Codigoitem;
use App\Rules\NaoAceitarFeriado;
use App\Rules\NaoAceitarFimDeSemana;

trait RegrasDataPublicacao
{

    public function ruleDataPublicacao ($tipo_id = null, $id = null)
    {
        $data_atual = date('Y-m-d');
        $arrCodigoItens = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
            ->where('descricao', '<>', 'Outros')
            ->where('descricao', '<>', 'Empenho')
            ->orderBy('descricao')
            ->pluck('id')
            ->toArray();

        $retorno = [
            'required',
            'date'
        ];

        if (in_array($tipo_id, $arrCodigoItens)) {
            $retorno = [
                'required',
                'date',
                "after_or_equal:data_assinatura",
                new NaoAceitarFeriado(),
                new NaoAceitarFimDeSemana()
            ];

            if (!$id) {
                $retorno[] = "after:{$data_atual}";
            }
        }
        return $retorno;
    }

}
