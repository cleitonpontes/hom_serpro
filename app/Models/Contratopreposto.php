<?php

namespace App\Models;

use App\Models\ContratoBase as Model;
use App\Http\Traits\Formatador;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratopreposto extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;
    use Formatador;

    protected static $logFillable = true;
    protected static $logName = 'contratopreposto';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratopreposto';
    protected $fillable = [
        'contrato_id',
        'user_id',
        'cpf',
        'nome',
        'email',
        'telefonefixo',
        'celular',
        'doc_formalizacao',
        'informacao_complementar',
        'data_inicio',
        'data_fim',
        'situacao',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getContrato()
    {
        return $this->getContratoNumero();
    }

    public function prepostoAPI($usuarioTransparencia)
    {
        return [
                'id' => $this->id,
                'contrato_id' => $this->contrato_id,
                'usuario' => $usuarioTransparencia,
                'email' => $this->email,
                'telefonefixo' => $this->telefonefixo,
                'celular' => $this->celular,
                'doc_formalizacao' => $this->doc_formalizacao,
                'informacao_complementar' => $this->informacao_complementar,
                'data_inicio' => $this->data_inicio,
                'data_fim' => $this->data_fim,
                'situacao' => $this->situacao == true ? 'Ativo' : 'Inativo',
        ];
    }

    public function buscaPrepostosPorContratoId(int $contrato_id, $range)
    {
        $prepostos = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->where('contrato_id', $contrato_id)
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contratopreposto.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $prepostos;
    }

    public function buscaPrepostos($range)
    {
        $prepostos = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contratopreposto.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $prepostos;
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

    public function getMaskedCpfAttribute($value)
    {
        return $this->retornaMascaraCpf($this->cpf);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
