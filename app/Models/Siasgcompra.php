<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Siasgcompra extends Model
{
    use CrudTrait;
    use LogsActivity;


    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected static $logFillable = true;
    protected static $logName = 'siasgcompras';

    protected $table = 'siasgcompras';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'unidade_id',
        'ano',
        'numero',
        'modalidade_id',
        'mensagem',
        'situacao',
        'json',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function atualizaJsonMensagemSituacao(int $id,string $json)
    {
        $json_var = json_decode($json);
        $situacao = ($json_var->messagem != 'Sucesso') ? 'Erro' :  'Importado';

        $compra = $this->find($id);
        $compra->json = $json;
        $compra->mensagem = $json_var->messagem;
        $compra->situacao = $situacao;
        $compra->save();

        return $compra;
    }


    public function getUnidade()
    {
        return $this->unidade->codigosiasg . ' - ' . $this->unidade->nomeresumido;
    }

    public function getModalidade()
    {
        return $this->modalidade->descres . ' - ' . $this->modalidade->descricao;
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
