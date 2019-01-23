<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Apropriacaonotaempenho extends Model
{

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
    public function retornaListagemPasso4($apropriacaoId)
    {
        $ug = session('user_ug');
        
        $listagem = $this->where('A.ug', $ug);
        $listagem->where('A.id', $apropriacaoId);
        
        $listagem->leftjoin('apropriacoes_situacao as S', 'S.id', '=', 'apropriacao_situacao_id');
        $listagem->leftjoin('apropriacoes AS A', 'A.id', '=', 'S.apropriacao_id');
        
        $listagem->groupBy(['A.competencia', 'A.ug', 'empenho', 'fonte', 'S.conta', 'S.vpd']);
        
        $listagem->select([
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
        
        return $listagem->get()->toArray();
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
        $sql .= '        WHEN ';
        $sql .= '           S.valor_agrupado <> sum(N.valor_rateado) ';
        $sql .= '        THEN 1 ELSE 0 ';
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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * Exclui registros onde a Situação / VPD foram alterados no Passo 2
     * 
     * @deprecated NÃO UTILIZAR. APAGAR APÓS CONFERÊNCIA
     * @param number $apid
     * @param array $registrosAlterados
     */
    public function excluiValoresRegistrosAlterados_OLD($apid, $registrosAlterados)
    {
        $modeloImportacao = new Apropriacaoimportacao();
        $condicao = $modeloImportacao->retornaCondicaoComplexaSituacaoVpd($apid, $registrosAlterados);
        
        // Join com apropriacoes_situacao
        $dados = $this->leftjoin('apropriacoes_situacao AS S', 'S.id', '=', 'apropriacao_situacao_id');
        
        // Condição complexa
        $dados->whereRaw($condicao);
        
        // Exclui registros encontrados
        // $dados->delete();
    }
    
    /**
     * Retorna listagem para Passo 3, e cria os dados inicias, se for o caso
     * 
     * @deprecated NÃO UTILIZAR. APAGAR APÓS CONFERÊNCIA
     * @param number $apid
     * @return array
     */
    public function retornaListagemPasso3_OLD($apid)
    {
        $dados = $this->retornaListagem($apid);
        
        if (count($dados) == 0) {
            // Os dados ainda não foram inseridos
            $modelo = new Apropriacaoimportacao();
            $importacoes = $modelo->retornaDadosPasso3($apid);
            
            $dados = array();
            $registroNovo = array();
            
            foreach ($importacoes as $registro) {
                $registroNovo['apropriacao_id'] = $apid;
                $registroNovo['conta'] = $registro['conta'];
                $registroNovo['situacao'] = $registro['situacao'];
                $registroNovo['vpd'] = $registro['vpd'];
                $registroNovo['valor_agrupado'] = $registro['total'];
                $registroNovo['empenho'] = null;
                $registroNovo['fonte'] = '000'; // Valor default
                $registroNovo['valor_rateado'] = 0;
                
                $dados[] = $registroNovo;
            }
            
            $this->insert($dados);
            
            // Rebusca os dados conforme $apid, agora com o id dos registros
            $dados = $this->retornaListagem($apid);
        }
        
        return $dados;
    }
    
    /**
     * Retorna dados da apropriação para apresentação
     *
     * @deprecated NÃO UTILIZAR. APAGAR APÓS CONFERÊNCIA
     * @return array
     */
    public function retornaListagem_OLD($apid)
    {
        $listagem = $this->select([
            'id',
            'apropriacao_id',
            DB::raw('left(conta, 6) as natureza'),
            DB::raw('right(conta, 2) as subitem'),
            'conta',
            'situacao',
            'vpd',
            'valor_agrupado',
            'empenho',
            'fonte',
            'valor_rateado'
        ])->where('apropriacao_id', $apid);
        
        return $listagem->get()->toArray();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    /**
     * Retorna dados da apropriação
     * para verificar se pode avançar para o passo 6
     *
     * @return array
     */
    public function getDadosBasicos($id)
    {
        $listagem = DB::table('apropriacoes_nota_empenho AS n')
            ->select([
                'n.empenho',
                'a.observacoes',
                'a.nup',
                'a.ateste',
                'n.conta',
                'a.doc_origem',
                DB::raw('left(n.conta, 6) as natureza'),
                DB::raw('right(n.conta, 2) as subitem'),
                'n.fonte',
                DB::raw('sum(n.valor_rateado) as saldo_necessario'),
                'a.ug',
                DB::raw('left(a.competencia, 4) as ano'),
                DB::raw('right(a.competencia, 2) as mes')
            ])
            ->leftJoin('apropriacoes as a', 'a.id', '=', 'n.apropriacao_id')
            ->where('n.apropriacao_id', (int)$id)
            ->groupBy(['n.empenho', 'a.observacoes', 'a.nup', 'a.doc_origem', 'a.ateste', 'n.conta',
                DB::raw('left(n.conta, 6)'), DB::raw('right(n.conta, 2)'), 'n.fonte',
                'a.ug', DB::raw('left(a.competencia, 4)'), DB::raw('right(a.competencia, 2)')]);

        return $listagem->get()->toArray();
    }

    /**
     * Retorna dados da apropriação
     * para verificar se pode avançar para o passo 6
     *
     * @return array
     */
    public function getEmpenhoPco($id)
    {
        $situacaoPco = config('app.situacao_pco');
        $listagem = DB::table('apropriacoes_nota_empenho AS n')
            ->select(['n.situacao'])
            ->leftJoin('apropriacoes as a', 'a.id', '=', 'n.apropriacao_id')
            ->leftJoin('execsfsituacao as e', 'e.codigo', '=', 'n.situacao')
            ->where([
                ['n.apropriacao_id', (int)$id],
                ['e.aba', '=', $situacaoPco]
            ])
            ->groupBy(['n.situacao']);

        return $listagem->get()->toArray();
    }

    /**
     *
     *
     * REFAZER PARA RECUPERAR OS DADOS DOS PCO ITENS
     *
     *
     *
     * Retorna dados da apropriação
     * para verificar se pode avançar para o passo 6
     *
     * @return array
     */
    public function getEmpenhoPcoItem($id, $situacao)
    {
        $tipoPco = config('app.situacao_pco');
        $listagem = DB::table('apropriacoes_nota_empenho AS n')
            ->select([
                'n.empenho',
                DB::raw('right(n.conta, 2) as subitem'),
                DB::raw('sum(n.valor_rateado) as saldo_necessario'),
                'n.conta',
                'n.situacao',
                'e.id'
            ])
            ->leftJoin('apropriacoes as a', 'a.id', '=', 'n.apropriacao_id')
            ->leftJoin('execsfsituacao as e', 'e.codigo', '=', 'n.situacao')
            ->where([
                ['n.apropriacao_id', (int)$id],
                ['e.aba', '=', $tipoPco],
                ['n.situacao', '=', $situacao]
            ])
            ->groupBy(['n.empenho', 'n.situacao', 'n.conta', DB::raw('right(n.conta, 2)', 'e.id')]);

        return $listagem->get()->toArray();
    }

}
