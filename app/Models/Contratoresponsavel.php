<?php

namespace App\Models;

use App\Http\Traits\Formatador;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratoresponsavel extends ContratoBase
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;
    use Formatador;

    protected static $logFillable = true;
    protected static $logName = 'responsavel';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratoresponsaveis';
    protected $fillable = [
        'contrato_id',
        'user_id',
        'funcao_id',
        'instalacao_id',
        'portaria',
        'situacao',
        'data_inicio',
        'data_fim',
        'telefone_fixo',
        'telefone_celular',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function inserirContratoresponsavelMigracaoConta(array $dados)
    {
        $this->fill($dados);
        $this->save();

        return $this;
    }

    public function getContrato()
    {
        return $this->getContratoNumero();
    }

    public function getUser()
    {
        $usuarioCpf = $this->user->cpf;
        $usuarioNome = $this->user->name;

        return $usuarioCpf . ' - ' . $usuarioNome;
    }

    public function getFuncao()
    {
        return $this->funcao->descricao;
    }

    public function getInstalacao()
    {
        return ($this->instalacao) ? $instalacao = $this->instalacao->nome : '';
    }

    /**
     * Retorna a Data de Início
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getDataInicio()
    {
        return $this->retornaDataAPartirDeCampo($this->data_inicio);
    }

    /**
     * Retorna a Data de Início
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getDataFim()
    {
        return $this->retornaDataAPartirDeCampo($this->data_fim);
    }

    public function responsavelAPI($usuarioTransparencia)
    {
        return [
            'id' => $this->id,
            'contrato_id' => $this->contrato_id,
            'usuario' => $usuarioTransparencia,
            'funcao_id' => $this->funcao->descricao,
            'instalacao_id' => $this->getInstalacao(),
            'portaria' => $this->portaria,
            'situacao' => $this->situacao == true ? 'Ativo' : 'Inativo',
            'data_inicio' => $this->data_inicio,
            'data_fim' => $this->data_fim,
            'telefone_fixo' => $this->telefone_fixo,
            'telefone_celular' => $this->telefone_celular,
        ];
    }

    public function buscaResponsaveisPorContratoId(int $contrato_id, $range)
    {
        $responsaveis = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->where('contrato_id', $contrato_id)
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contratoresponsaveis.updated_at', [$range[0], $range[1]]);
            })
            ->get();


        return $responsaveis;
    }

    public function buscaResponsaveis($range)
    {
        $responsaveis = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contratoresponsaveis.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $responsaveis;
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

    public function funcao()
    {
        return $this->belongsTo(Codigoitem::class, 'funcao_id');
    }

    public function instalacao()
    {
        return $this->belongsTo(Instalacao::class, 'instalacao_id');
    }

    public function user()
    {
        return $this->belongsTo(BackpackUser::class, 'user_id');
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

    public function getMaskedCpfAttribute($value)
    {
        $retorno = '';
        if (isset($this->user()->first()->cpf)) {
            $retorno = $this->retornaMascaraCpf($this->user()->first()->cpf);
        }
        return $retorno;
    }

    public function getUsuarioNomeAttribute($value)
    {
        return $this->user()->first()->name ?? '';
    }

    public function getUsuarioEmailAttribute($value)
    {
        return $this->user()->first()->email ?? '';
    }

    public function getDescricaoTipoAttribute($value)
    {
        return $this->funcao()->first()->descricao ?? '';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

}
