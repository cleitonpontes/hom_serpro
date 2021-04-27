<?php

namespace App\Models;

use App\Http\Traits\Formatador;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratoterceirizado extends ContratoBase
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;
    use Formatador;

    protected static $logFillable = true;
    protected static $logName = 'terceirizado';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratoterceirizados';
    protected $fillable = [
        'contrato_id',
        'cpf',
        'nome',
        'funcao_id',
        'descricao_complementar',
        'jornada',
        'unidade',
        'salario',
        'custo',
        'escolaridade_id',
        'data_inicio',
        'data_fim',
        'situacao',
        'telefone_fixo',
        'telefone_celular',
        'aux_transporte',
        'vale_alimentacao',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function inserirContratoterceirizadoMigracaoConta(array $dados)
    {
        $this->fill($dados);
        $this->save();

        return $this;
    }

    public function getFuncao()
    {
        return $this->funcao->descricao;
    }

    public function getCpf()
    {
        return $this->retornaMascaraCpf($this->cpf);
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getTelefoneFixo()
    {
        $telefone_fixo = $this->telefone_fixo;
        return $telefone_fixo;
    }

    public function getTelefoneCelular()
    {
        $telefone_celular = $this->telefone_celular;
        return $telefone_celular;
    }

    public function getEscolaridade()
    {
        return $this->escolaridade->descricao;
    }

    public function formatAuxTransporte()
    {
        return $this->retornaCampoFormatadoComoNumero($this->aux_transporte, true);
    }

    public function formatValeAlimentacao()
    {
        return $this->retornaCampoFormatadoComoNumero($this->vale_alimentacao, true);
    }

    public function formatVlrSalario()
    {
        return $this->retornaCampoFormatadoComoNumero($this->salario, true);
    }

    public function formatVlrCusto()
    {
        return $this->retornaCampoFormatadoComoNumero($this->custo, true);
    }

    public function terceirizadoAPI($usuarioTransparencia)
    {
        return [
                'id' => $this->id,
                'contrato_id' => $this->contrato_id,
                'usuario' => $usuarioTransparencia,
                'funcao_id' => $this->funcao->descricao,
                'descricao_complementar' => $this->descricao_complementar,
                'jornada' => $this->jornada,
                'unidade' => $this->unidade,
                'salario' => number_format($this->salario, 2, ',', '.'),
                'custo' => number_format($this->custo, 2, ',', '.'),
                'escolaridade_id' => $this->escolaridade->descricao,
                'data_inicio' => $this->data_inicio,
                'data_fim' => $this->data_fim,
                'situacao' => $this->situacao == true ? 'Ativo' : 'Inativo',
                'telefone_fixo' => $this->telefone_fixo,
                'telefone_celular' => $this->telefone_celular,
                'aux_transporte' => number_format($this->aux_transporte, 2, ',', '.'),
                'vale_alimentacao' => number_format($this->vale_alimentacao, 2, ',', '.'),
        ];
    }

    public function buscaTerceirizadosPorContratoId(int $contrato_id, $range)
    {
        $terceirizados = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->where('contrato_id', $contrato_id)
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contratoterceirizados.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $terceirizados;
    }

    public function buscaTerceirizados($range)
    {
        $terceirizados = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contratoterceirizados.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $terceirizados;
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

    public function escolaridade()
    {
        return $this->belongsTo(Codigoitem::class, 'escolaridade_id');
    }

    public function funcao()
    {
        return $this->belongsTo(Codigoitem::class, 'funcao_id');
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
