<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Unidade extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'unidade';

    use SoftDeletes;
    protected $table = 'unidades';

    protected $fillable = [
        'orgao_id',
        'codigo',
        'gestao',
        'codigosiasg',
        'nome',
        'nomeresumido',
        'telefone',
        'tipo',
        'situacao',
        'sisg',
        'municipio_id',
        'esfera',
        'poder',
        'tipo_adm',
        'aderiu_siasg',
        'utiliza_siafi',
        'codigo_siorg',

    ];
    public function buscaUnidadeExecutoraPorCodigo($codigo)
    {
        $unidade = $this->where('codigo', $codigo)
            ->where('tipo', 'E')
            ->first();
        return $unidade->id;
    }

    public function getOrgao()
    {
        if ($this->orgao_id) {
            $orgao = Orgao::find($this->orgao_id);
            return $orgao->codigo . " - " . $orgao->nome;
        }

        return '';
    }

    public function getTipo()
    {

        if ($this->tipo == 'E') {
            $tipo = "Executora";
        }

        if ($this->tipo == 'C') {
            $tipo = "Controle";
        }

        if ($this->tipo == 'S') {
            $tipo = "Setorial Contábil";
        }

        return $tipo;

    }

    public function getMunicipio()
    {
        if (!$this->municipio_id)
            return '';
        return $this->municipio->nome;
    }
    public function getUF()
    {
        if (!$this->municipio_id)
            return '';
        return $this->municipio->estado->sigla;
    }

    public function orgao()
    {
        return $this->belongsTo(Orgao::class, 'orgao_id');
    }

    public function users()
    {
        return $this->belongsToMany(BackpackUser::class, 'unidadesusers', 'unidade_id', 'user_id');
    }

    public function contratos()
    {
        return $this->hasMany(Contrato::class, 'unidade_id');
    }

    public function compras()
    {
        return $this->hasMany(Siasgcompra::class, 'unidade_id');
    }

    public function configuracao()
    {
        return $this->hasOne(Unidadeconfiguracao::class, 'unidade_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

}
