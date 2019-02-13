<?php
namespace App\Models;

use Backpack\CRUD\CrudTrait;
use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Apropriacaoimportacao extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'apropriacoes_importacao';

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
    protected $table = 'apropriacoes_importacao';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nome_arquivo',
        'numero_linha',
        'linha',
        'apropriacao_id',
        'ug',
        'competencia',
        'nivel',
        'categoria',
        'conta',
        'rubrica',
        'descricao',
        'valor',
        'situacao',
        'vpd',
        'situacao_original',
        'vpd_original'
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
     * Retorna dados da apropriação para identificação / seleção de situações
     *
     * @param number $apropriacaoId
     * @return array
     */
    public function retornaListagemPasso2($apropriacaoId)
    {
        $ug = session('user_ug');

        $listagem = DB::table('apropriacoes_importacao AS I');
        $listagem->leftjoin('apropriacoes AS A', 'A.id', '=', 'I.apropriacao_id');

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
            'situacao_original',
            'vpd_original'
        ]);

        $listagem->where('I.apropriacao_id', $apropriacaoId);
        $listagem->where('A.ug', $ug);

        return $listagem->get()->toArray();
    }

    /**
     * Retorna quantidade de registros ainda sem a situação informada
     *
     * @param number $apropriacaoId
     * @return number
     */
    public function retornaQtdeRegistroComSituacaoInformada($apropriacaoId)
    {
        $dados = $this->select();
        $dados->where('apropriacao_id', $apropriacaoId);
        $dados->whereNull('situacao');

        $retorno = $dados->count();

        return $retorno;
    }

    /**
     * Retorna dados da apropriação para identificação / seleção de empenhos
     *
     * @param number $apropriacaoId
     * @return array
     */
    public function retornaDadosPasso3($apropriacaoId)
    {
        $listagem = $this->retornaListagemPasso3($apropriacaoId);
        $dados = $listagem->toArray();

        return $dados;
    }

    /**
     * Retorna dados da apropriação para identificação / seleção de empenhos
     *
     * @param number $apropriacaoId
     * @return array
     */
    public function retornaListagemPasso3($apropriacaoId, $condicaoRaw = '')
    {
        $listagem = $this->where('apropriacao_id', $apropriacaoId);

        $listagem->whereIn('categoria', ['1', '2']);

        if ($condicaoRaw != '') {
            $listagem->whereRaw($condicaoRaw);
        }

        $listagem->groupBy(['competencia', 'situacao', 'vpd', 'conta']);

        $listagem->orderBy('situacao');
        $listagem->orderBy('vpd');
        $listagem->orderBy('conta');

        $listagem->select([
            DB::raw('left(competencia, 4) as ano'),
            'situacao',
            'vpd',
            DB::raw('left(conta, 6) as natureza'),
            DB::raw('right(conta, 2) as subitem'),
            'conta',
            DB::raw('sum(valor) as total')
        ]);

        return $listagem->get();
    }

    /**
     * Retorna complexa condição (where) para uso em querys através de raw
     *
     * @param number $apid
     * @param array $registrosAlterados
     * @return string
     */
    public function retornaCondicaoComplexaSituacaoVpd($apid, $registrosAlterados)
    {
        $condicao = " apropriacao_id = $apid AND ( ";
        foreach ($registrosAlterados as $registro) {
            $conta = $registro->conta;
            $sit = $registro->situacao;
            $vpd = $registro->vpd;

            $condicao .= "( conta = '$conta' AND situacao = '$sit' AND vpd = '$vpd' ) OR ";
        }
        $condicao = substr($condicao, 0, - 4);
        $condicao .= " ) ";

        return $condicao;
    }

    /**
     * Retorna registros com Situação / VPDs alterados no Passo 2
     *
     * @param number $apid
     * @return array
     */
    public function avaliaMudancaSituacaoVpdNoPasso2($apid)
    {
        // Busca dados alterados
        $sql = '';
        $sql .= 'SELECT DISTINCT ';
        $sql .= '    base.conta, ';
        $sql .= '    base.situacao, ';
        $sql .= '    base.vpd ';
        $sql .= 'FROM ';
        $sql .= '    ( ';
        $sql .= '    /* Alterações na seleção manual de situações */ ';
        $sql .= '    SELECT ';
        $sql .= '        conta, ';
        $sql .= '        situacao, ';
        $sql .= '        vpd ';
        $sql .= '    FROM ';
        $sql .= '        apropriacoes_importacao ';
        $sql .= '    WHERE ';
        $sql .= "        apropriacao_id = $apid        AND ( ";
        $sql .= '        situacao <> situacao_original OR ';
        $sql .= '        situacao_original is null     OR ';
        $sql .= '        vpd <> vpd_original           OR ';
        $sql .= '        vpd_original is null ) ';
        $sql .= '    UNION ALL ';
        $sql .= '    SELECT ';
        $sql .= '        conta, ';
        $sql .= '        situacao_original             as situacao, ';
        $sql .= '        vpd_original                  as vpd ';
        $sql .= '    FROM ';
        $sql .= '        apropriacoes_importacao ';
        $sql .= '    WHERE ';
        $sql .= "        apropriacao_id = $apid        AND ( ";
        $sql .= '        situacao <> situacao_original OR ';
        $sql .= '        situacao_original is null     OR ';
        $sql .= '        vpd <> vpd_original           OR ';
        $sql .= '        vpd_original is null ) ';
        $sql .= '    /* Atualizações nos registros de notas de empenho */ ';
        $sql .= '    UNION ALL ';
        $sql .= '    SELECT DISTINCT ';
        $sql .= '        conta, ';
        $sql .= '        situacao, ';
        $sql .= '        vpd ';
        $sql .= '    FROM ';
        $sql .= '        apropriacoes_situacao ';
        $sql .= '    WHERE ';
        $sql .= "        apropriacao_id = $apid        AND ";
        $sql .= '        id not in ( ';
        $sql .= '            SELECT ';
        $sql .= '                apropriacao_situacao_id ';
        $sql .= '            FROM ';
        $sql .= '                apropriacoes_nota_empenho ';
        $sql .= '        ) ';
        $sql .= '    ) base ';
        $sql .= 'WHERE ';
        $sql .= '    base.situacao is not null         AND ';
        $sql .= '    base.vpd is not null ';

        $registrosAlterados = DB::select($sql);

        return $registrosAlterados;
    }

    /**
     * Iguala campos 'originais' com seus respectivos Situacao e VPD [após ações e conferências]
     *
     * @param number $apid
     */
    public function igualaCamposOriginais($apid)
    {
        $sql = '';
        $sql .= 'situacao <> situacao_original OR ';
        $sql .= 'situacao_original is null     OR ';
        $sql .= 'vpd <> vpd_original           OR ';
        $sql .= 'vpd_original is null ';

        $dados = $this->whereRaw($sql);

        $dados->select([
            'id',
            'situacao',
            'vpd',
            'situacao_original',
            'vpd_original'
        ]);

        $dados->update([
            'situacao_original' => DB::raw('situacao'),
            'vpd_original' => DB::raw('vpd')
        ]);
    }
}
