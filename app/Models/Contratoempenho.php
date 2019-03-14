<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratoempenho extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'contratoempenhos';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratoempenhos';
    // protected $primaryKey = 'id';
     public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'contrato_id',
        'fornecedor_id',
        'empenho_id'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getContrato()
    {
        if($this->contrato_id){
            $contrato = Contrato::find($this->contrato_id);
            return $contrato->numero;
        }else{
            return '';
        }
    }

    public function getFornecedor()
    {
        $fornecedor = Fornecedor::find($this->fornecedor_id);
        return $fornecedor->cpf_cnpj_idgener . ' - ' . $fornecedor->nome;

    }

    public function getEmpenho()
    {
        $empenho = Empenho::find($this->empenho_id);
        return $empenho->numero;

    }

    public function empenho()
    {
        return $this->belongsTo(Empenho::class, 'empenho_id');
    }

    public function formatVlrEmpenhado()
    {
        if($this->empenho_id){
            $empenho = Empenhos::find($this->empenho_id);
            return 'R$ ' . number_format($empenho->empenhado, 2, ',', '.');
        }else{
            return '';
        }

    }

    public function formatVlraLiquidar()
    {
        if($this->empenho_id){
            $empenho = Empenhos::find($this->empenho_id);
            return 'R$ ' . number_format($empenho->aliquidar, 2, ',', '.');
        }else{
            return '';
        }

    }

    public function formatVlrLiquidado()
    {
        if($this->empenho_id){
            $empenho = Empenhos::find($this->empenho_id);
            return 'R$ ' . number_format($empenho->liquidado, 2, ',', '.');
        }else{
            return '';
        }
    }

    public function formatVlrPago()
    {
        if($this->empenho_id){
            $empenho = Empenhos::find($this->empenho_id);
            return 'R$ ' . number_format($empenho->pago, 2, ',', '.');
        }else{
            return '';
        }
    }

    public function formatVlrRpInscrito()
    {
        if($this->empenho_id){
            $empenho = Empenhos::find($this->empenho_id);
            return 'R$ ' . number_format($empenho->rpinscrito, 2, ',', '.');
        }else{
            return '';
        }
    }

    public function formatVlrRpaLiquidar()
    {
        if($this->empenho_id){
            $empenho = Empenhos::find($this->empenho_id);
            return 'R$ ' . number_format($empenho->rpaliquidar, 2, ',', '.');
        }else{
            return '';
        }
    }

    public function formatVlrRpLiquidado()
    {
        if($this->empenho_id){
            $empenho = Empenhos::find($this->empenho_id);
            return 'R$ ' . number_format($empenho->rpliquidado, 2, ',', '.');
        }else{
            return '';
        }
    }

    public function formatVlrRpPago()
    {
        if($this->empenho_id){
            $empenho = Empenhos::find($this->empenho_id);
            return 'R$ ' . number_format($empenho->rppago, 2, ',', '.');
        }else{
            return '';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

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
