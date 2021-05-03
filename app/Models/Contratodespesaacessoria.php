<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratodespesaacessoria extends ContratoBase
{
    use CrudTrait;
    use LogsActivity;
    /**
     * @var bool
     */
    protected static $logFillable = true;
    /**
     * @var string
     */
    protected static $logName = 'contrato_despesa_acessoria';
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratodespesaacessoria';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'contrato_id',
        'tipo_id',
        'recorrencia_id',
        'descricao_complementar',
        'vencimento',
        'valor',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    public function getUnidade()
    {
        return $this->contrato->unidade->codigo . ' - ' . $this->contrato->unidade->nomeresumido;
    }

    public function getFornecedor()
    {
        return $this->contrato->fornecedor->cpf_cnpj_idgener . ' - ' . $this->contrato->fornecedor->nome;
    }

    public function getContrato()
    {
        return $this->contrato->numero;
    }

    public function getTipoDespesa()
    {
        return $this->tipoDespesa->descricao;
    }


    public function getRecorrenciaDespesa()
    {
        return $this->recorrenciaDespesa->descricao;
    }

    public function formatValor()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function despesaAcessoriaAPI()
    {
        return [
                'contrato_id' => $this->contrato_id,
                'tipo_id' => $this->tipoDespesa->descricao,
                'recorrencia_id' => $this->recorrenciaDespesa->descricao,
                'descricao_complementar' => $this->descricao_complementar,
                'vencimento' => $this->vencimento,
                'valor' => number_format($this->valor, 2, ',', '.'),
        ];
    }

    public function buscaDespesasAcessoriasPorContratoId(int $contrato_id, $range)
    {
        $despesas_acessorias = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->where('contrato_id', $contrato_id)
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contratodespesaacessoria.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $despesas_acessorias;
    }

    public function buscaDespesasAcessorias($range)
    {
        $despesas_acessorias = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contratodespesaacessoria.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $despesas_acessorias;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    public function tipoDespesa()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_id');
    }

    public function recorrenciaDespesa()
    {
        return $this->belongsTo(Codigoitem::class, 'recorrencia_id');
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
