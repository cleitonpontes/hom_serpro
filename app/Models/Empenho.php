<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Empenho extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'empenho';
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'empenhos';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'numero',
        'unidade_id',
        'fornecedor_id',
        'planointerno_id',
        'naturezadespesa_id'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getFornecedor()
    {
        $fornecedor = Fornecedor::find($this->fornecedor_id);
        return $fornecedor->cpf_cnpj_idgener . ' - ' . $fornecedor->nome;

    }
    public function getUnidade()
    {
        $unidade = Unidade::find($this->unidade_id);
        return $unidade->codigo . ' - ' . $unidade->nomeresumido;

    }

    public function getNatureza()
    {
        if($this->naturezadespesa_id){

           $naturezadespesa = Naturezadespesa::find($this->naturezadespesa_id);
            return $naturezadespesa->codigo . ' - ' . $naturezadespesa->descricao;

        }else{
            return '';
        }
    }

    public function getPi()
    {
        if($this->planointerno_id){
            $planointerno = Planointerno::find($this->planointerno_id);
            return $planointerno->codigo . ' - ' . $planointerno->descricao;
        }else{
            return '';
        }
    }

    public function formatVlrEmpenhado()
    {
        return 'R$ '.number_format($this->empenhado, 2, ',', '.');
    }

    public function formatVlraLiquidar()
    {
        return 'R$ '.number_format($this->aliquidar, 2, ',', '.');
    }

    public function formatVlrLiquidado()
    {
        return 'R$ '.number_format($this->liquidado, 2, ',', '.');
    }

    public function formatVlrPago()
    {
        return 'R$ '.number_format($this->pago, 2, ',', '.');
    }

    public function formatVlrRpInscrito()
    {
        return 'R$ '.number_format($this->rpinscrito, 2, ',', '.');
    }

    public function formatVlrRpaLiquidar()
    {
        return 'R$ '.number_format($this->rpaliquidar, 2, ',', '.');
    }

    public function formatVlrRpLiquidado()
    {
        return 'R$ '.number_format($this->rpliquidado, 2, ',', '.');
    }

    public function formatVlrRpPago()
    {
        return 'R$ '.number_format($this->rppago, 2, ',', '.');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
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
