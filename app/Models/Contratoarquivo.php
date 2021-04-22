<?php

namespace App\Models;

use App\Models\ContratoBase as Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratoarquivo extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;

    protected static $logFillable = true;
    protected static $logName = 'contrato_arquivos';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contrato_arquivos';
    protected $fillable = [
        'contrato_id',
        'tipo',
        'processo',
        'sequencial_documento',
        'descricao',
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

    public function getContrato()
    {
        return $this->getContratoNumero();
    }

    public function getTipo()
    {
        return $this->codigoItem()->first()->descricao;
    }

    public function getListaArquivosComPath()
    {
        $arquivos_array = [];
        $i = 1;
        foreach ($this->arquivos as $arquivo) {
            
            $arquivos_array[] = [
                'arquivo_'.$i => env('APP_URL'). '/storage/'. $arquivo,
            ];
            $i++;
        }
        return $arquivos_array;
    }
    public function arquivoAPI()
    {
        return [
                'contrato_id' => $this->contrato_id,
                'tipo' => $this->getTipo(),
                'processo' => $this->processo,
                'sequencial_documento' => $this->sequencial_documento,
                'descricao' => $this->descricao,
                'arquivos' => $this->getListaArquivosComPath(),
        ];
    }

    public function buscaArquivosPorContratoId(int $contrato_id, $range)
    {
        $arquivos = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->where('contrato_id', $contrato_id)
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contrato_arquivos.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $arquivos;
    }

    public function buscaArquivos($range)
    {
        $arquivos = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contrato_arquivos.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $arquivos;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function codigoItem()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo');
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
        $destination_path = "contrato/".$contrato->id."_".str_replace('/','_',$contrato->numero);

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }
}
