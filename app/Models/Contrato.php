<?php

namespace App\Models;

use App\Http\Controllers\AdminController;
use Faker\ORM\Spot\EntityPopulator;
use Illuminate\Support\Facades\DB;
use function foo\func;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contrato extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'contrato';
//    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratos';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'numero',
        'fornecedor_id',
        'unidade_id',
        'tipo_id',
        'categoria_id',
        'subcategoria_id',
        'processo',
        'objeto',
        'info_complementar',
        'receita_despesa',
        'fundamento_legal',
        'modalidade_id',
        'licitacao_numero',
        'data_assinatura',
        'data_publicacao',
        'vigencia_inicio',
        'vigencia_fim',
        'valor_inicial',
        'valor_global',
        'num_parcelas',
        'valor_parcela',
        'valor_acumulado',
        'situacao_siasg',
        'situacao',
        'unidades_requisitantes',
    ];


    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function inserirContratoMigracaoConta(array $dados)
    {
        $this->fill($dados);
        $this->save();

        return $this;
    }

    public function buscaListaContratosUg($filtro)
    {

        $unidade_user = Unidade::find(session()->get('user_ug_id'));

        $lista = $this->select([
            DB::raw('CONCAT("O"."codigo", \' - \', "O"."nome")  as orgao'),
            DB::raw('CONCAT("U"."codigo",\' - \',"U"."nomeresumido")  as unidade'),
            DB::raw('CASE
                                WHEN receita_despesa = \'D\'
                                THEN \'Despesa\'
                                ELSE \'Receita\'
                           END as receita_despesa'),
            'unidades_requisitantes',
            'numero',
            DB::raw('"F"."cpf_cnpj_idgener" as fornecedor_codigo'),
            DB::raw('"F"."nome" as fornecedor_nome'),
            'T.descricao as tipo',
            'C.descricao as categoria',
            'S.descricao as subcategoria',
            'processo',
            'objeto',
            'info_complementar',
            'M.descricao as modalidade',
            'licitacao_numero',
            'data_assinatura',
            'data_publicacao',
            'vigencia_inicio',
            'vigencia_fim',
            'valor_inicial',
            'valor_global',
            'num_parcelas',
            'valor_parcela',
            'valor_acumulado',
            DB::raw('CASE
                                WHEN contratos.situacao = \'t\'
                                THEN \'Ativo\'
                                ELSE \'Inativo\'
                           END as situacao'),
            'unidades_requisitantes',
        ]);

        $lista->leftjoin('orgaosubcategorias as S', 'S.id', '=', 'contratos.subcategoria_id');
        $lista->leftjoin('codigoitens as M', 'M.id', '=', 'contratos.modalidade_id');
        $lista->leftjoin('codigoitens as C', 'C.id', '=', 'contratos.categoria_id');
        $lista->leftjoin('codigoitens as T', 'T.id', '=', 'contratos.tipo_id');
        $lista->leftjoin('fornecedores as F', 'F.id', '=', 'contratos.fornecedor_id');
        $lista->leftjoin('unidades as U', 'U.id', '=', 'contratos.unidade_id');
        $lista->leftjoin('orgaos as O', 'O.id', '=', 'U.orgao_id');

        $lista->where('U.id', $unidade_user->id);

        return $lista->get();
    }

    public function buscaListaContratosOrgao($filtro)
    {

        $unidade_user = Unidade::find(session()->get('user_ug_id'));

        $lista = $this->select([
            DB::raw('CONCAT("O"."codigo", \' - \', "O"."nome")  as orgao'),
            DB::raw('CONCAT("U"."codigo",\' - \',"U"."nomeresumido")  as unidade'),
            DB::raw('CASE
                                WHEN receita_despesa = \'D\'
                                THEN \'Despesa\'
                                ELSE \'Receita\'
                           END as receita_despesa'),
            'unidades_requisitantes',
            'numero',
            DB::raw('"F"."cpf_cnpj_idgener" as fornecedor_codigo'),
            DB::raw('"F"."nome" as fornecedor_nome'),
            'T.descricao as tipo',
            'C.descricao as categoria',
            'S.descricao as subcategoria',
            'processo',
            'objeto',
            'info_complementar',
            'M.descricao as modalidade',
            'licitacao_numero',
            'data_assinatura',
            'data_publicacao',
            'vigencia_inicio',
            'vigencia_fim',
            'valor_inicial',
            'valor_global',
            'num_parcelas',
            'valor_parcela',
            'valor_acumulado',
            DB::raw('CASE
                                WHEN contratos.situacao = \'t\'
                                THEN \'Ativo\'
                                ELSE \'Inativo\'
                           END as situacao'),
            'unidades_requisitantes',
        ]);

        $lista->leftjoin('orgaosubcategorias as S', 'S.id', '=', 'contratos.subcategoria_id');
        $lista->leftjoin('codigoitens as M', 'M.id', '=', 'contratos.modalidade_id');
        $lista->leftjoin('codigoitens as C', 'C.id', '=', 'contratos.categoria_id');
        $lista->leftjoin('codigoitens as T', 'T.id', '=', 'contratos.tipo_id');
        $lista->leftjoin('fornecedores as F', 'F.id', '=', 'contratos.fornecedor_id');
        $lista->leftjoin('unidades as U', 'U.id', '=', 'contratos.unidade_id');
        $lista->leftjoin('orgaos as O', 'O.id', '=', 'U.orgao_id');

        $lista->where('O.id', $unidade_user->orgao->id);

        return $lista->get();
    }

    public function buscaListaTodosContratos($filtro)
    {

        $lista = $this->select([
            DB::raw('CONCAT("O"."codigo", \' - \', "O"."nome")  as orgao'),
            DB::raw('CONCAT("U"."codigo",\' - \',"U"."nomeresumido")  as unidade'),
            DB::raw('CASE
                                WHEN receita_despesa = \'D\'
                                THEN \'Despesa\'
                                ELSE \'Receita\'
                           END as receita_despesa'),
            'unidades_requisitantes',
            'numero',
            DB::raw('"F"."cpf_cnpj_idgener" as fornecedor_codigo'),
            DB::raw('"F"."nome" as fornecedor_nome'),
            'T.descricao as tipo',
            'C.descricao as categoria',
            'S.descricao as subcategoria',
            'processo',
            'objeto',
            'info_complementar',
            'M.descricao as modalidade',
            'licitacao_numero',
            'data_assinatura',
            'data_publicacao',
            'vigencia_inicio',
            'vigencia_fim',
            'valor_inicial',
            'valor_global',
            'num_parcelas',
            'valor_parcela',
            'valor_acumulado',
            DB::raw('CASE
                                WHEN contratos.situacao = \'t\'
                                THEN \'Ativo\'
                                ELSE \'Inativo\'
                           END as situacao'),
            'unidades_requisitantes',
        ]);

        $lista->leftjoin('orgaosubcategorias as S', 'S.id', '=', 'contratos.subcategoria_id');
        $lista->leftjoin('codigoitens as M', 'M.id', '=', 'contratos.modalidade_id');
        $lista->leftjoin('codigoitens as C', 'C.id', '=', 'contratos.categoria_id');
        $lista->leftjoin('codigoitens as T', 'T.id', '=', 'contratos.tipo_id');
        $lista->leftjoin('fornecedores as F', 'F.id', '=', 'contratos.fornecedor_id');
        $lista->leftjoin('unidades as U', 'U.id', '=', 'contratos.unidade_id');
        $lista->leftjoin('orgaos as O', 'O.id', '=', 'U.orgao_id');

        return $lista->get();
    }

    public function buscaContratosNovosPorUg(int $unidade_id)
    {
        $data = date('Y-m-d H:i:s', strtotime('-5 days'));
        $contratos = $this->where('unidade_id', $unidade_id)
            ->where('created_at', '>=', $data)
            ->get();

        return $contratos->count();
    }

    public function buscaContratosAtualizadosPorUg(int $unidade_id)
    {
        $data = date('Y-m-d H:i:s', strtotime('-5 days'));
        $contratos = $this->where('unidade_id', $unidade_id)
            ->where('updated_at', '>=', $data)
            ->get();

        return $contratos->count();
    }

    public function buscaContratosVencidosPorUg(int $unidade_id)
    {
        $data = date('Y-m-d');
        $contratos = $this->where('unidade_id', $unidade_id)
            ->where('vigencia_fim', '<', $data)
            ->where('situacao', true)
            ->get();

        return $contratos->count();
    }

    public function atualizaContratoFromHistorico(string $contrato_id, array $array)
    {

        $this->where('id', '=', $contrato_id)
            ->update($array);

        return $this;
    }

    public function atualizaValorAcumuladoFromCronograma(Contratocronograma $contratocronograma)
    {
        $contrato_id = $contratocronograma->contrato_id;

        $valor_acumulado = $contratocronograma->where('contrato_id', '=', $contrato_id)
            ->sum('valor');

        $this->where('id', '=', $contrato_id)
            ->update(['valor_acumulado' => $valor_acumulado]);
    }

    public function getFornecedor()
    {
        $fornecedor = Fornecedor::find($this->fornecedor_id);
        return $fornecedor->cpf_cnpj_idgener . ' - ' . $fornecedor->nome;

    }

    public function getReceitaDespesa()
    {
        if ($this->receita_despesa == 'D') {
            return 'Despesa';
        }
        if ($this->receita_despesa == 'R') {
            return 'Receita';
        }

        return '';
    }

    public function getUnidade()
    {
        $unidade = Unidade::find($this->unidade_id);
        return $unidade->codigo . ' - ' . $unidade->nomeresumido;

    }

    public function getOrgao()
    {
        $orgao = Orgao::whereHas('unidades', function ($query) {
            $query->where('id', '=', $this->unidade_id);
        })->first();

        return $orgao->codigo . ' - ' . $orgao->nome;

    }

    public function getTipo()
    {
        if ($this->tipo_id) {
            $tipo = Codigoitem::find($this->tipo_id);

            return $tipo->descricao;
        } else {
            return '';
        }


    }


    public function getCategoria()
    {
        $categoria = Codigoitem::find($this->categoria_id);

        return $categoria->descricao;

    }

    public function getSubCategoria()
    {
        if ($this->subcategoria_id) {
            $subcategoria = OrgaoSubcategoria::find($this->subcategoria_id);
            return $subcategoria->descricao;
        } else {
            return '';
        }


    }


    public function formatVlrParcela()
    {
        return 'R$ ' . number_format($this->valor_parcela, 2, ',', '.');
    }

    public function formatVlrGlobal()
    {
        return 'R$ ' . number_format($this->valor_global, 2, ',', '.');
    }

    public function formatVlrAcumulado()
    {
        return 'R$ ' . number_format($this->valor_acumulado, 2, ',', '.');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function historico()
    {

        return $this->hasMany(Contratohistorico::class, 'contrato_id');

    }

    public function cronograma()
    {

        return $this->hasMany(Contratocronograma::class, 'contrato_id');

    }


    public function responsaveis()
    {

        return $this->hasMany(Contratoresponsavel::class, 'contrato_id');

    }

    public function garantias()
    {

        return $this->hasMany(Contratogarantia::class, 'contrato_id');

    }

    public function arquivos()
    {

        return $this->hasMany(Contratoarquivo::class, 'contrato_id');

    }

    public function empenhos()
    {

        return $this->hasMany(Contratoempenho::class, 'contrato_id');

    }

    public function faturas()
    {

        return $this->hasMany(Contratofatura::class, 'contrato_id');

    }

    public function ocorrencias()
    {

        return $this->hasMany(Contratoocorrencia::class, 'contrato_id');

    }

    public function terceirizados()
    {

        return $this->hasMany(Contratoterceirizado::class, 'contrato_id');

    }

    public function unidade()
    {

        return $this->belongsTo(Unidade::class, 'unidade_id');

    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Codigoitem::class, 'categoria_id');
    }

    public function modalidade()
    {
        return $this->belongsTo(Codigoitem::class, 'modalidade_id');
    }

    public function orgaosubcategoria()
    {
        return $this->belongsTo(OrgaoSubcategoria::class, 'subcategoria_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

}
