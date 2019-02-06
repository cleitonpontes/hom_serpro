<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Empenhos extends Model
{

    /**
     * Retorna Empenhos e Fontes conforme $ano e $conta informada
     *
     * @param number $ano
     * @param string $conta
     * @return array
     */
    public function retornaEmpenhoFontePorAnoConta($ano, $conta)
    {
        // Dados de todos os empenhos - em memória
        $empenhos = $this->retornaEmpenhosPorAno($ano);

        $registrosEncontrados = array_filter($empenhos, function ($empenho) use ($ano, $conta) {
            return ($empenho->ano == $ano && $empenho->nd == $conta);
        });

        return $registrosEncontrados;
    }

    /**
     * Retorna empenhos por $ano
     *
     * @param number $ano
     * @return array
     */
    public function retornaEmpenhosPorAno($ano)
    {
        // Dados de todos os empenhos - em memória
        $empenhos = session('empenho.fonte.conta');

        if (count($empenhos) == 0) {
            // Se não houver dados na session, busca os dados no banco
            $empenhos = $this->retornaEmpenhosFonteAno($ano);
            session(['empenho.fonte.conta' => $empenhos]);
        }

        return $empenhos;
    }

    /**
     * Retorna conjunto de Empenhos, fonte e conta (nd + subitem) por $ug
     *
     * @param number $ano
     * @return array
     */
    public function retornaEmpenhosFonteAno($ano)
    {
        $ug = session('user_ug_id');

        $sql = '';
        $sql .= 'SELECT ';
        $sql .= '	E.numero AS ne, ';
        $sql .= '   left(E.numero, 4)  AS ano, ';
        $sql .= "	'000' AS fonte, ";
        $sql .= '	N.codigo || I.codigo AS nd ';
        $sql .= 'FROM';
        $sql .= '	empenhos AS E ';
        $sql .= 'LEFT JOIN ';
        $sql .= '	empenhodetalhado AS D on ';
        $sql .= '	D.empenho_id = E.id ';
        $sql .= 'LEFT JOIN ';
        $sql .= '	naturezasubitem AS I on ';
        $sql .= '	I.id = D.naturezasubitem_id ';
        $sql .= 'LEFT JOIN ';
        $sql .= '	naturezadespesa AS N on ';
        $sql .= '	N.id = I.naturezadespesa_id ';
        $sql .= 'WHERE ';
        $sql .= '    left(E.numero, 4) = ? and ';
        $sql .= '	E.unidade_id = ?';
        $sql .= 'ORDER BY ';
        $sql .= '    nd, ';
        $sql .= '    ne ';

        $dados = DB::select($sql, [
            $ano,
            $ug
        ]);

        return $dados;
    }
}
