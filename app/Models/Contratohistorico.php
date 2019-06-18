<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratohistorico extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'contrato_historico';
    use SoftDeletes;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratohistorico';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'numero',
        'contrato_id',
        'fornecedor_id',
        'unidade_id',
        'tipo_id',
        'categoria_id',
        'receita_despesa',
        'processo',
        'objeto',
        'info_complementar',
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
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getFornecedorHistorico()
    {
        $fornecedor = Fornecedor::find($this->fornecedor_id);
        return $fornecedor->cpf_cnpj_idgener . ' - ' . $fornecedor->nome;

    }

    public function getReceitaDespesaHistorico()
    {
        if($this->receita_despesa == 'D'){
            return 'Despesa';
        }
        if($this->receita_despesa == 'R'){
            return 'Receita';
        }

        return '';
    }

    public function getUnidadeHistorico()
    {
        $unidade = Unidade::find($this->unidade_id);
        return $unidade->codigo . ' - ' . $unidade->nomeresumido;

    }

    public function getOrgaoHistorico()
    {
        $orgao = Orgao::whereHas('unidades', function ($query) {
            $query->where('id','=', $this->unidade_id);
        })->first();

        return $orgao->codigo . ' - ' . $orgao->nome;

    }

    public function getTipoHistorico()
    {
        if($this->tipo_id){
            $tipo = Codigoitem::find($this->tipo_id);

            return $tipo->descricao;
        }else{
            return '';
        }


    }


    public function getCategoriaHistorico()
    {
        $categoria = Codigoitem::find($this->categoria_id);

        return $categoria->descricao;

    }


    public function formatVlrParcelaHistorico()
    {
        return 'R$ '.number_format($this->valor_parcela, 2, ',', '.');
    }

    public function formatVlrGlobalHistorico()
    {
        return 'R$ '.number_format($this->valor_global, 2, ',', '.');
    }

    public function createNewHistorico(array $dado)
    {
        $contratohistorico = $this->where('numero', '=', $dado['numero'])
            ->where('contrato_id', '=', $dado['contrato_id'])
            ->where('tipo_id', '=', $dado['tipo_id'])
            ->first();

        if(!$contratohistorico){
            $this->fill($dado);
            $this->save();
            return $this;
        }

        $contratohistorico->fill($dado);
        $contratohistorico->save();
        return $contratohistorico;

    }

    public function getFornecedor()
    {
        $fornecedor = Fornecedor::find($this->fornecedor_id);
        return $fornecedor->cpf_cnpj_idgener . ' - ' . $fornecedor->nome;

    }

    public function getReceitaDespesa()
    {
        if($this->receita_despesa == 'D'){
            return 'Despesa';
        }
        if($this->receita_despesa == 'R'){
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
            $query->where('id','=', $this->unidade_id);
        })->first();

        return $orgao->codigo . ' - ' . $orgao->nome;

    }

    public function getTipo()
    {
        if($this->tipo_id){
            $tipo = Codigoitem::find($this->tipo_id);

            return $tipo->descricao;
        }else{
            return '';
        }


    }

    public function getModalidade()
    {
        if($this->modalidade_id){
            $modalidade = Codigoitem::find($this->modalidade_id);

            return $modalidade->descricao;
        }else{
            return '';
        }


    }


    public function getCategoria()
    {
        $categoria = Codigoitem::find($this->categoria_id);

        return $categoria->descricao;

    }


    public function formatVlrParcela()
    {
        return 'R$ '.number_format($this->valor_parcela, 2, ',', '.');
    }

    public function formatVlrGlobal()
    {
        return 'R$ '.number_format($this->valor_global, 2, ',', '.');
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function cronograma(){

        return $this->hasMany(Contratocronograma::class, 'contratohistorico_id');

    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
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
