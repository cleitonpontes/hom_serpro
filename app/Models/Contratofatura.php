<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratofatura extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'contratofaturas';
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratofaturas';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [

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
    public function getTipoListaFatura()
    {
        if($this->tipolistafatura_id){
            $tipolistafatura = Tipolistafatura::find($this->tipolistafatura_id);
            return $tipolistafatura->nome;
        }else{
            return '';
        }
    }
    public function getJustificativaFatura()
    {
        if($this->justificativafatura_id){
            $justificativafatura = Justificativafatura::find($this->justificativafatura_id);
            return $justificativafatura->nome . ": " . $justificativafatura->descricao;
        }else{
            return '';
        }
    }
    public function getSfpadrao()
    {
        if($this->sfpadrao_id){
            $sfpadrao = SfPadrao::find($this->sfpadrao_id);
            return $sfpadrao->anodh . $sfpadrao->codtipodh . str_pad($sfpadrao->numdh, 6, "0", STR_PAD_LEFT);
        }else{
            return '';
        }
    }

    public function formatValor()
    {
        if($this->valor){
            return 'R$ ' . number_format($this->valor, 2, ',', '.');
        }else{
            return '';
        }

    }

    public function formatJuros()
    {
        if($this->juros){
            return 'R$ ' . number_format($this->juros, 2, ',', '.');
        }else{
            return '';
        }

    }

    public function formatMulta()
    {
        if($this->multa){
            return 'R$ ' . number_format($this->multa, 2, ',', '.');
        }else{
            return '';
        }

    }

    public function formatGlosa()
    {
        if($this->glosa){
            return '(R$ ' . number_format($this->glosa, 2, ',', '.') . ')';
        }else{
            return '';
        }

    }

    public function formatValorLiquido()
    {
        if($this->valorliquido){
            return 'R$ ' . number_format($this->valorliquido, 2, ',', '.');
        }else{
            return '';
        }

    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function empenhos()
    {
        return $this->belongsToMany(Empenho::class, 'contratofatura_empenhos', 'contratofatura_id', 'empenho_id');
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
