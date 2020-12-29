<?php
namespace App\Models;

use Backpack\CRUD\CrudTrait;
use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Apropriacaosituacao extends Model
{
    use CrudTrait;
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logName = 'apropriacoes_situacao';

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
        $temDados = $this->retornaHaDadosPasso3($apid);

        $modeloImportacao = new Apropriacaoimportacao();

        if ($temDados == false) {
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
        session(['empenho.fonte.conta' => array()]);

        return $dados;
    }

    /**
     * Retorna dados da apropriação para identificação / seleção de empenhos
     *
     * @param number $apropriacaoId
     * @return array
     */
    public function retornaDadosPasso3($apid)
    {
        $listagem = new Apropriacaoimportacao();

        $listagem->where('apropriacao_id', $apid);
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

    /**
     * Retorna dados para o passo 6, PCOs
     *
     * @param number $apid
     * @return array
     */
    public function retornaDadosPasso6Pco($apid)
    {
        $categoriaDdp = 1; // 1 = PCO
        $dados = $this->retornaDadosPasso6($apid, $categoriaDdp);

        return $dados;
    }

    /**
     * Retorna dados para o passo 6, Despesas a anular
     *
     * @param number $apid
     * @return array
     */
    public function retornaDadosPasso6DespesaAnular($apid)
    {
        $categoriaDdp = 2; // 2 = Despesas a anular
        $dados = $this->retornaDadosPasso6($apid, $categoriaDdp);

        return $dados;
    }

    /**
     * Retorna a verificação da existência ou não de registros conforme Apropriação ($apid)
     *
     * @param number $apid
     * @return number
     */
    private function retornaHaDadosPasso3($apid)
    {
        $consulta = $this->where('apropriacao_id', $apid);
        return $consulta->exists();
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

        $modeloEmpenhos = new Empenhos();

        foreach ($importacoes as $registroSituacao) {
            $ano = $registroSituacao['ano'];
            $conta = $registroSituacao['conta'];

            $situacao['apropriacao_id'] = $apid;
            $situacao['conta'] = $conta;
            $situacao['situacao'] = $registroSituacao['situacao'];
            $situacao['vpd'] = $registroSituacao['vpd'];
            $situacao['valor_agrupado'] = $registroSituacao['total'];

            $retorno = $this->create($situacao);
            $situacaoId = $retorno->id;

            $registrosEncontradosEmpenhoFonte = $modeloEmpenhos->retornaEmpenhoFontePorAnoConta($ano, $conta);
            $qtdeRegistro = (is_array($registrosEncontradosEmpenhoFonte) ? count($registrosEncontradosEmpenhoFonte) : 0);

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

            // Zera prévios valores dos registros alterados, tanto em apropriacoes_situacao quanto apropriacoes_nota_empenho.
            $modelo = $this->whereRaw($condicao);
            $dados = $modelo->select();
            $dados->delete();

            // Retorna dados a serem atualizados
            $importacoes = $modeloImportacao->retornaListagemPasso3($apid, $condicao);
            $this->gravaNovosDadosPasso3($apid, $importacoes);
        }
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

        $listagem->where('S.apropriacao_id', $apid);
        $listagem->where('A.ug', $ug);

        $dados = $listagem->get()->toArray();

        $listagem->select([
            'S.id',
            'S.apropriacao_id',
            DB::raw('left("A"."competencia", 4) as ano'),
            DB::raw('left("S"."conta", 6) as natureza'),
            DB::raw('right("S"."conta", 2) as subitem'),
            'S.conta',
            'S.situacao',
            'S.vpd',
            'S.valor_agrupado'
        ]);

        return $listagem->get()->toArray();
    }

    /**
     * Retorna dados básicos, pertinentes passo 6
     *
     * @param number $apid
     * @param number $categoria
     * @return array
     */
    private function retornaDadosPasso6($apid, $categoria = '')
    {
        $listagem = $this->select([
            'A.ug',
            'situacao',
            DB::raw('left("apropriacoes_situacao"."conta", 6) as natureza'),
            DB::raw('right("apropriacoes_situacao"."conta", 2) as subitem'),
            'conta',
            'vpd',
            'N.empenho',
            'N.fonte',
            'N.valor_rateado'
        ]);

        $listagem->leftjoin('apropriacoes AS A', 'A.id', '=', 'apropriacao_id');
        $listagem->leftjoin('apropriacoes_nota_empenho AS N', 'N.apropriacao_situacao_id', '=', 'apropriacoes_situacao.id');
        $listagem->leftjoin('execsfsituacao AS E', 'E.codigo', '=', 'situacao');

        $listagem->where('apropriacao_id', $apid);
        $listagem->where('N.valor_rateado', '>', 0);

        if ($categoria != '') {
            $listagem->where('E.categoria_ddp', $categoria);
        }

        $listagem->orderBy('A.ug');
        $listagem->orderBy('situacao');
        $listagem->orderBy('N.empenho');
        $listagem->orderBy('conta');
        $listagem->orderBy('vpd');
        $listagem->orderBy('N.fonte');
        $listagem->orderBy('N.valor_rateado');

        $dados = $listagem->get()->toArray();

        return $dados;
    }
}
