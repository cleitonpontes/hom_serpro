<?php
namespace App\Models;

use Backpack\CRUD\CrudTrait;
use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Apropriacaonotaempenho extends Model
{

    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'apropriacoes_nota_empenho';
    /**
     * Informa que não utilizará os campos create_at e update_at do Laravel
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Identifica o campo chave primária da tabela
     *
     * @var string
     */
    public $primaryKey = 'id';

    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'apropriacoes_nota_empenho';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'apropriacao_situacao_id',
        'empenho',
        'fonte',
        'valor_rateado'
    ];

    /**
     * Relacionamento com a tabela Apropriacoes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function apropriacao()
    {
        return $this->hasMany('App\Models\Apropriacao', 'id');
    }

    /**
     * Retorna dados da dos empenhos para validação do saldo
     *
     * @param number $apropriacaoId
     * @return array
     */
    public function retornaListagemPasso4ComSaldos($apropriacaoId)
    {
        $dados = $this->retornaDadosBase($apropriacaoId);
        
        return $dados;
    }

    /**
     * Faz verificação se pode ou não avançar para o pŕoximo passo 
     * 
     * @param number $apropriacaoId
     * @return boolean
     */
    public function validarPasso4($apropriacaoId)
    {
        $dados = $this->retornaDadosBase($apropriacaoId);
        
        $semSaldos = array_column($dados, 'sem_saldo');
        $qtde = array_sum($semSaldos);
        
        return $qtde == 0;
    }

    /**
     * Retorna os dados básicos para a listagem do Passo 4
     * 
     * @param number $apropriacaoId
     * @return array
     */
    private function retornaDadosBase($apropriacaoId)
    {
        $ug = session('user_ug');

        $dados = $this->where('A.ug', $ug);
        $dados->where('A.id', $apropriacaoId);
        $dados->where('X.categoria_ddp', 1); // 1 = PCO
        $dados->where('valor_rateado', '>', 0);

        $dados->leftjoin('apropriacoes_situacao as S', 'S.id', '=', 'apropriacao_situacao_id');
        $dados->leftjoin('apropriacoes AS A', 'A.id', '=', 'S.apropriacao_id');
        $dados->leftjoin('unidades AS U', 'U.codigo', '=', 'A.ug');
        $dados->leftjoin('empenhos AS E', function ($relacao) {
            $relacao->on('E.numero', '=', 'empenho');
            $relacao->on('E.unidade_id', '=', 'U.id');
        });
        $dados->leftjoin('naturezadespesa AS N', 'N.codigo', '=', DB::raw('left("S"."conta", 6)'));
        $dados->leftjoin('naturezasubitem AS I', function ($relacao) {
            $relacao->on('I.codigo', '=', DB::raw('right("S"."conta", 2)'));
            $relacao->on('I.naturezadespesa_id', '=', 'N.id');
        });
        $dados->leftjoin('empenhodetalhado AS D', function ($relacao) {
            $relacao->on('D.naturezasubitem_id', '=', 'I.id');
            $relacao->on('D.empenho_id', '=', 'E.id');
        });
//        $dados->leftjoin('empenhodetalhado AS D', 'D.naturezasubitem_id', '=', 'I.id');
        $dados->leftjoin('execsfsituacao as X', 'X.codigo', '=', 'S.situacao');

        $dados->groupBy([
            'A.ug',
            'A.competencia',
            'empenho',
            'S.conta',
            'fonte',
            'D.empaliquidar'
        ]);

        $dados->orderBy('A.ug');
        $dados->orderBy('A.competencia');
        $dados->orderBy('empenho');
        $dados->orderBy('S.conta');
        $dados->orderBy('fonte');
        $dados->orderBy('D.empaliquidar');

        $dados->select([
            DB::raw('left("A"."competencia", 4) as ano'),
            DB::raw('right("A"."competencia", 2) as mes'),
            'A.ug',
            'empenho',
            'fonte',
            'S.conta',
            DB::raw('left("S"."conta", 6) as natureza'),
            DB::raw('right("S"."conta", 2) as subitem'),
//            DB::raw('coalesce(sum(valor_rateado), 0) as saldo_necessario'),
            DB::raw('coalesce(sum(valor_rateado), 0) as saldo_necessario'),
            DB::raw('"D"."empaliquidar" as saldo_atual'),
            DB::raw('coalesce(sum(valor_rateado), 0) > "D"."empaliquidar" as sem_saldo')
        ]);

        $sql = $dados->toSql();
//        dd($sql);

        return $dados->get()->toArray();
    }
    
    /**
     * Retorna todos os registros de empenhos por $id
     *
     * @param number $id
     * @return array
     */
    public function retornaEmpenhosPorId($id)
    {
        $registros = $this->where('apropriacao_situacao_id', $id);

        $empenhosPorId = $registros->get()->toArray();

        return $empenhosPorId;
    }

    /**
     * Retorna quantidade de registros inválidos, para permitir ou não o avanço ao próximo passo
     *
     * @param number $apid
     * @return number
     */
    public function retornaQtdeRegistrosInvalidos($apid)
    {
        $dados = $this->retornaDadosValidados($apid);
        $qtde = array_sum(array_column($dados, 'invalido'));

        return $qtde;
    }

    /**
     * Retorna dados da apropriação para identificação / seleção de empenhos
     *
     * @param number $apropriacaoId
     * @return array
     */
    public function retornaListagemPasso4_OLD($apropriacaoId)
    {
        $ug = session('user_ug');

        $dados = $this->where('A.ug', $ug);
        $dados->where('A.id', $apropriacaoId);
        $dados->where('valor_rateado', '>', 0);

        $dados->leftjoin('apropriacoes_situacao as S', 'S.id', '=', 'apropriacao_situacao_id');
        $dados->leftjoin('apropriacoes AS A', 'A.id', '=', 'S.apropriacao_id');

        $dados->groupBy([
            'A.competencia',
            'A.ug',
            'empenho',
            'fonte',
            'S.conta',
            'S.vpd'
        ]);

        $dados->select([
            DB::raw('left("A"."competencia", 4) as ano'),
            DB::raw('right("A"."competencia", 2) as mes'),
            'A.ug',
            'empenho',
            'fonte',
            'S.conta',
            'S.vpd',
            DB::raw('left("S"."conta", 6) as natureza'),
            DB::raw('right("S"."conta", 2) as subitem'),
            DB::raw('sum(valor_rateado) as saldo_necessario'),
            DB::raw('0 as saldo_atual')
        ]);

        $sql = $dados->toSql();
        dd($sql);

        return $dados->get()->toArray();
    }

    /**
     * Retorna dados após validação de preenchimento
     *
     * @param number $apid
     * @return array
     */
    private function retornaDadosValidados($apid)
    {
        $sql = '';
        $sql .= 'SELECT ';
        $sql .= '    CASE ';
        $sql .= '        WHEN  sum(N.valor_rateado) is null THEN ';
        $sql .= '            1 ';
        $sql .= '        WHEN S.valor_agrupado <> sum(N.valor_rateado) THEN ';
        $sql .= '            1 ';
        $sql .= '        ELSE ';
        $sql .= '            0 ';
        $sql .= '    END AS invalido ';
        $sql .= 'FROM ';
        $sql .= '    apropriacoes_situacao S ';
        $sql .= 'LEFT JOIN ';
        $sql .= '    apropriacoes_nota_empenho N ON ';
        $sql .= '        N.apropriacao_situacao_id = S.id ';
        $sql .= 'WHERE ';
        $sql .= '    S.apropriacao_id = ? ';
        $sql .= 'GROUP BY ';
        $sql .= '    S.conta, ';
        $sql .= '    S.situacao, ';
        $sql .= '    S.vpd, ';
        $sql .= '    S.valor_agrupado ';

        $dados = DB::select($sql, [$apid]);

        return $dados;
    }
}
