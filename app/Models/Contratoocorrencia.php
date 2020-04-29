<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratoocorrencia extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'ocorrencia';
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratoocorrencias';

    // protected $primaryKey = 'id';
    public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'numero',
        'contrato_id',
        'user_id',
        'data',
        'ocorrencia',
        'notificapreposto',
        'emailpreposto',
        'numeroocorrencia',
        'novasituacao',
        'situacao',
        'arquivos',
    ];

    protected $casts = [
        'arquivos' => 'array'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function inserirContratoocorrenciaMigracaoConta(array $dados)
    {
        $this->fill($dados);
        $this->save();

        return $this;
    }

    public function getContrato()
    {
        if($this->contrato_id){
            $contrato = Contrato::find($this->contrato_id);
            return $contrato->numero;
        }else{
            return '';
        }
    }
    public function getUser()
    {
        if($this->user_id){
            $user = BackpackUser::find($this->user_id);
            return $user->cpf . ' - ' . $user->name;
        }else{
            return '';
        }
    }

    public function getSituacao()
    {
        if($this->situacao){
            $situacao = Codigoitem::find($this->situacao);
            return $situacao->descricao;
        }else{
            return '';
        }
    }

    public function getNovaSituacao()
    {
        if($this->novasituacao){
            $situacao = Codigoitem::find($this->novasituacao);
            return $situacao->descricao;
        }else{
            return '';
        }
    }

    public function getNumeroOcorrencia()
    {
        if($this->numeroocorrencia){
            $ocorrencianumero = Contratoocorrencia::find($this->numeroocorrencia);
            return $ocorrencianumero->numero;
        }else{
            return '';
        }
    }

    public function getArquivos()
    {
        if($this->arquivos){
            return $this->arquivos;
        }else{
            return '';
        }
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

    public function situacao()
    {
        return $this->belongsTo(Codigoitem::class, 'situacao');
    }

    public function novasituacao()
    {
        return $this->belongsTo(Codigoitem::class, 'novasituacao');
    }

    public function numeroocorrencia()
    {
        return $this->belongsTo(Contratoocorrencia::class, 'id');
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
    public function setArquivosAttribute($value)
    {

        $attribute_name = "arquivos";
        $disk = "local";
        $contrato = Contrato::find($this->contrato_id);
        $destination_path = "ocorrencia/".$contrato->id."_".str_replace('/','_',$contrato->numero);

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }
}
