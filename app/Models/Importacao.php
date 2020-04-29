<?php

namespace App\Models;

//use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;


class Importacao extends Model
{
    use CrudTrait;
    use LogsActivity;

    protected static $logName = 'importacao';
    protected static $logFillable = true;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    public $timestamps = true;
    protected $table = 'importacoes';
    protected $primaryKey = 'id';
//    protected $guarded = ['id'];
    protected $fillable = [
        'nome_arquivo',
        'tipo_id',
        'unidade_id',
        'contrato_id',
        'situacao_id',
        'delimitador',
        'arquivos',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = ['arquivos' => 'array'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getTipo()
    {
        if (!$this->tipo_id) {
            return '';
        }

        $tipo = Codigoitem::find($this->tipo_id);
        return $tipo->descricao;
    }

    public function getUnidade()
    {
        $unidade = Unidade::find($this->unidade_id);
        return $unidade->codigo . ' - ' . $unidade->nomeresumido;
    }

    public function getContrato()
    {
        if (!$this->contrato_id) {
            return '';
        }

        $contrato = Contrato::find($this->contrato_id);
        return $contrato->numero;
    }

    public function getSituacao()
    {
        if (!$this->situacao_id) {
            return '';
        }

        $tipo = Codigoitem::find($this->situacao_id);
        return $tipo->descricao;
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

    public function tipo()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_id');
    }

    public function situacao()
    {
        return $this->belongsTo(Codigoitem::class, 'situacao_id');
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
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
        $destination_path = "importacao/" . $contrato->id . "_" . str_replace('/', '_', $contrato->numero);

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }
}
