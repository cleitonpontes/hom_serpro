<?php
namespace App\Models;

use Backpack\CRUD\CrudTrait;
use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Apropriacao extends Model
{

    use CrudTrait;
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logName = 'apropriacoes';

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
    protected $table = 'apropriacoes';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'competencia',
        'ug',
        'nivel',
        'valor_liquido',
        'valor_bruto',
        'fase_id',
        'arquivos',
        'ateste',
        'nup',
        'doc_origem',
        'centro_custo',
        'observacoes'
    ];

    /**
     * Relacionamento com a tabela Apropriacoesfases
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function apropriacaoFases()
    {
        return $this->hasMany('App\Models\Apropriacoesfases', 'id');
    }

    /**
     * Retorna dados da apropriação para apresentação
     *
     * @return array
     */
    public function retornaListagem()
    {
        $ug = session('user_ug');
        $listagem = Apropriacao::join('apropriacoes_fases AS F', 'F.id', '=', 'apropriacoes.fase_id');

        $listagem->select([
            'apropriacoes.id',
            'competencia',
            'nivel',
            'valor_liquido',
            'valor_bruto',
            'fase_id',
            'F.fase',
            'arquivos'
        ])->where('ug', $ug);

        return $listagem->get();
    }

    /**
     * Retorna dados da apropriação para verificar se pode avançar para o passo 6
     *
     * @return array
     */
    public function validarPasso5($id)
    {
        $query = Apropriacao::query()->where('id', $id)
            ->whereNotNull('observacoes')
            ->whereNotNull('doc_origem')
            ->whereNotNull('nup')
            ->whereNotNull('ateste');

        return $query->exists();
    }

    public function retornaDadosPasso6($id)
    {
        $ug = session('user_ug');

        $listagem = $this->select([
            'id',
            'competencia',
            DB::raw('left(competencia, 4) as ano'),
            DB::raw('right(competencia, 2) as mes'),
            'ug',
            'nivel',
            'valor_liquido',
            'valor_bruto',
            'arquivos',
            'ateste',
            'nup',
            'doc_origem',
            'observacoes'
        ]);

        $listagem->where('ug', $ug);
        $listagem->where('id', $id);

        $registros = $listagem->get()->toArray();
        $dados = isset($registros[0]) ? $registros[0] : null;

        return $dados;
    }

    /**
     * Retorna dados da apropriação para apresentação em relatório
     *
     * @return array
     */
    public function retornaDadosRelatorio($apid)
    {
        $ug = session('user_ug');

        $listagem = $this->select([
            'apropriacoes.id',
            DB::raw('"O"."nome" as orgao_nome'),
            'ug',
            DB::raw('"U"."nome" as ug_nome'),
            'competencia',
            'nivel',
            'valor_liquido',
            'valor_bruto',
            'arquivos',
            'ateste',
            'nup',
            'centro_custo',
            'doc_origem',
            'observacoes'
        ]);

        $listagem->leftjoin('unidades as U', 'U.codigo', '=', 'apropriacoes.ug');
        $listagem->leftjoin('orgaos as O', 'O.id', '=', 'U.orgao_id');

        $listagem->where('ug', $ug);
        $listagem->where('apropriacoes.id', $apid);

        $dados = $listagem->get()->toArray();

        return $dados;
    }
}
