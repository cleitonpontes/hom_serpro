<?php

namespace App\Repositories;

use App\Models\Contratoempenho;
use App\Models\Empenho as Model;
use DB;

class Empenho extends Base
{

    /**
     * Retorna empenhos sem contrato do ano corrente
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function retornaEmpenhosSemContratoAnoAtual()
    {
        $ano = date('Y');

        $contratos = Contratoempenho::select('empenho_id')->get()->toArray();

        $query = Model::select([
            'empenhos.created_at AS criacao',
            'empenhos.id',
            DB::raw('CONCAT(\'linha_\', "empenhos"."id") AS linha_id'),
            'empenhos.numero AS empenho',
            DB::raw('concat("F"."cpf_cnpj_idgener", \' - \', "F"."nome") AS fornecedor'),
            'empenhos.empenhado AS valor',
            'empenhos.fornecedor_id'
        ]);

        $query->join('unidades AS U', 'U.id', '=', 'empenhos.unidade_id');
        $query->join('fornecedores AS F', 'F.id', '=', 'empenhos.fornecedor_id');

        $query->whereNotIn('empenhos.id', $contratos);
        $query->where('empenhos.numero', 'like', $ano . '%');
        $query->where('U.codigo', session('user_ug'));
        $query->where('empenhos.deleted_at', null);

        $query->latest('empenhos.created_at'); // orderBy DESC

        $dados = $query->get()->toArray();

        return $dados;
    }

}
