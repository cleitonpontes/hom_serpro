<?php
namespace App\Models;

use DB;
// use App\Models\Apropriacaoimportacao;
use Illuminate\Database\Eloquent\Model;

class Apropriacaosituacao extends Model
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
    protected $table = 'apropriacoes_situacao';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'apropriacao_id',
        'conta',
        'situacao',
        'vpd',
        'valor_agrupado'
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
     * Retorna listagem para Passo 3, e cria os dados inicias, se for o caso
     *
     * @param number $apid
     * @return array
     */
    public function retornaListagemPasso3($apid)
    {
        $qtde = $this->retornaQtdeDadosPasso3($apid);
        
        $modeloImportacao = new Apropriacaoimportacao();
        
        if ($qtde == 0) {
            $importacoes = $modeloImportacao->retornaDadosPasso3($apid);
            
            // Grava novos dados conforme agrupamento dos valores importados
            $this->gravaNovosDadosPasso3($apid, $importacoes);
        } else {
            // Verifica se houve mudança de Situações / VPDs
            $this->avaliaMudancaSituacaoVpd($apid);
        }
        
        // Iguala os campos, para não conterem mais distinções
        $modeloImportacao->igualaCamposOriginais($apid);
        
        // Rebusca os dados conforme $apid, agora com o id dos registros
        $dados = $this->retornaListagem($apid);
        // dd($dados);
        
        return $dados;
    }
    
    /**
     * Retorna a quantidade de registros conforme Apropriação ($apid)
     * 
     * @param number $apid
     * @return number
     */
    private function retornaQtdeDadosPasso3($apid)
    {
        $consulta = $this->where('apropriacao_id', $apid);
        return $consulta->count();
    }
    
    /**
     * Grava registros iniciais por Apropriação ($apid), para uso posterior no Passo 3
     * 
     * @param number $apid
     * @param array $importacoes
     */
    private function gravaNovosDadosPasso3($apid, $importacoes)
    {
        $situacao = array();
        $empenho = array();
        $empenhos = array();
        
        $modeloEmpenhos = new Empenho();
        
        foreach ($importacoes as $registroSituacao) {
            $situacao['apropriacao_id'] = $apid;
            $situacao['conta'] = $registroSituacao['conta'];
            $situacao['situacao'] = $registroSituacao['situacao'];
            $situacao['vpd'] = $registroSituacao['vpd'];
            $situacao['valor_agrupado'] = $registroSituacao['total'];
            
            $retorno = $this->create($situacao);
            $situacaoId = $retorno->id;
            
            $registrosEncontradosEmpenhoFonte = $modeloEmpenhos->retornaEmpenhoFontePorConta($registroSituacao['conta']);
            $qtdeRegistro = count($registrosEncontradosEmpenhoFonte);
            
            foreach ($registrosEncontradosEmpenhoFonte as $registroEmpenho) {
                $empenho['apropriacao_situacao_id'] = $situacaoId;
                $empenho['empenho'] = $registroEmpenho->ne;
                $empenho['fonte'] = $registroEmpenho->fonte;
                $empenho['valor_rateado'] = ($qtdeRegistro == 1) ? $registroSituacao['total'] : 0;
                
                $empenhos[] = $empenho;
            }
        }
        
        $modeloNotaEmpenho = new Apropriacaonotaempenho();
        $modeloNotaEmpenho->insert($empenhos);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * Mero alias para mesmo método em Apropriacaoimportacao::avaliaMudancaSituacaoVpdNoPasso2($apid)
     * 
     * @param number $apid
     */
    private function avaliaMudancaSituacaoVpd($apid)
    {
        $modeloImportacao = new Apropriacaoimportacao();
        $registrosAlterados = $modeloImportacao->avaliaMudancaSituacaoVpdNoPasso2($apid);
        
        if ($registrosAlterados != null) {
            // Retorna condição where
            $modeloImportacao = new Apropriacaoimportacao();
            $condicao = $modeloImportacao->retornaCondicaoComplexaSituacaoVpd($apid, $registrosAlterados);
            
            // Zera prévios valores dos registros alterados
            // Tanto em apropriacoes_situacao e apropriacoes_nota_empenho.
            $modelo = $this->whereRaw($condicao);
            $dados = $modelo->select();
            $dados->delete();
            
            // Retorna dados a serem atualizados
            $importacoes = $modeloImportacao->retornaListagemPasso3($apid, $condicao);
            $this->gravaNovosDadosPasso3($apid, $importacoes);
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    private function gravaValoresPorEmpenho($apid)
    {
        // Busca dados para serem regravados
        $modeloNe = new Apropriacaonotaempenho();
        $nes = $modeloNe->select('apropriacao_situacao_id')->get()->toArray();
        $dadosNe = array_column($nes, 'id');
        
        $dados = $this->where('apropriacao_id', $apid);
        $dados = $this->whereNotIn('id', $nes);
        
        
        
        
        
        $d1 = $dados->get()->toArray();
        dd('gravaValoresPorEmpenho', $dadosNe, $d1);
        
        
        
        
        $modeloNe = new Apropriacaonotaempenho();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    private function reagrupaValoresEmRegistrosAlterados($apid, $diferenças)
    {
        //
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * Retorna dados da apropriação para apresentação
     *
     * @return array
     */
    private function retornaListagem($apid)
    {
        $ug = session('user_ug');
        
        $listagem = DB::table('apropriacoes_situacao AS S');
        
        $listagem->leftjoin('apropriacoes AS A', 'A.id', '=', 'S.apropriacao_id');
        // $listagem->leftjoin('apropriacoes_nota_empenho AS N', 'N.apropriacao_situacao_id', '=', 'S.id');
        
        $listagem->where('S.apropriacao_id', $apid);
        $listagem->where('A.ug', $ug);
        
        $listagem->select([
            'S.id',
            'S.apropriacao_id',
            DB::raw('left("S"."conta", 6) as natureza'),
            DB::raw('right("S"."conta", 2) as subitem'),
            'S.conta',
            'S.situacao',
            'S.vpd',
            'S.valor_agrupado'
            /*
            ,
            'N.empenho',
            'N.fonte',
            'N.valor_rateado'
            */
        ]);
        
        // $sql = $listagem->toSql();
        return $listagem->get()->toArray();
        
        
        /*
        $listagem->select([
            'I.apropriacao_id',
            'I.id',
            'ug',
            'I.competencia',
            DB::raw('left(conta, 6) as natureza'),
            DB::raw('right(conta, 2) as subitem'),
            'I.conta',
            'I.nivel',
            'I.categoria',
            'I.rubrica',
            'situacao',
            'vpd',
            'atualizado'
        ])->where('S.apropriacao_id', $apid);
        
        $listagem = $this->select([
            'id',
            'apropriacao_id',
            'conta',
            'situacao',
            'vpd',
            'valor_agrupado',
            
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
        */
    }
    
    
    
    
    
    
    /**
     * Retorna dados da apropriação para identificação / seleção de empenhos
     *
     * @param number $apropriacaoId
     * @return array
     */
    public function retornaDadosPasso3($apropriacaoId)
    {
        $listagem = new Apropriacaoimportacao();
        // $listagem = Apropriacaoimportacao::where('apropriacao_id', $apropriacaoId);
        
        $listagem->where('apropriacao_id', $apropriacaoId);
        $listagem->whereIn('categoria', ['1', '2']);
        
        $listagem->groupBy(['situacao', 'vpd', 'conta']);
        
        $listagem->orderBy('situacao');
        $listagem->orderBy('vpd');
        $listagem->orderBy('conta');
        
        $listagem->select([
            'situacao',
            'vpd',
            DB::raw('left(conta, 6) as natureza'),
            DB::raw('right(conta, 2) as subitem'),
            'conta',
            DB::raw('sum(valor) as total')
        ]);
        
        return $listagem->get()->toArray();
    }
}
