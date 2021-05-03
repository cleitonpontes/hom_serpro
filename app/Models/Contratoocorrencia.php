<?php

namespace App\Models;

use App\Http\Traits\Formatador;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratoocorrencia extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;
    use Formatador;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratoocorrencias';
    protected $dateFormat = 'd/m/Y';
    protected static $logFillable = true;
    protected static $logName = 'ocorrencia';

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

    public $timestamps = false;

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
        if ($this->contrato_id) {
            $contrato = Contrato::find($this->contrato_id);
            return $contrato->numero;
        } else {
            return '';
        }
    }

    /**
     * @return string
     * @deprecated Melhor utilizar getUsuario()
     */
    public function getUser()
    {
        if ($this->user_id) {
            $user = BackpackUser::find($this->user_id);
            return $user->cpf . ' - ' . $user->name;
        } else {
            return '';
        }
    }

    public function getUserCpfInibido()
    {
        if ($this->user_id) {
            $user = BackpackUser::find($this->user_id);
            return 'XXX' . substr($user->cpf,3,9) . 'XX' . ' - ' . $user->name;
        } else {
            return '';
        }
    }

    /**
     * @return string
     * @deprecated Melhor utilizar getSituacaoConsulta()
     */
    public function getSituacao()
    {
        if ($this->situacao) {
            $situacao = Codigoitem::find($this->situacao);
            return $situacao->descricao;
        } else {
            return '';
        }
    }

    /**
     * @return string
     * @deprecated Melhor utilizar getSituacaoNovaConsulta()
     */
    public function getNovaSituacao()
    {
        if ($this->novasituacao) {
            $situacao = Codigoitem::find($this->novasituacao);
            return $situacao->descricao;
        } else {
            return '';
        }
    }

    public function getNumero()
    {
        $numero = '';

        if ($this->numero) {
            $numero = $this->numero;
        }

        return $numero;
    }

    public function getNumeroOcorrencia()
    {
        if ($this->numeroocorrencia) {
            $ocorrencianumero = Contratoocorrencia::find($this->numeroocorrencia);
            return $ocorrencianumero->numero;
        } else {
            return '';
        }
    }

    public function getArquivos()
    {
        if ($this->arquivos) {
            return $this->arquivos;
        } else {
            return '';
        }
    }

    /**
     * Retorna o Fornecedor, exibindo código e nome do mesmo
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getFornecedor()
    {
        $fornCpfCnpj = $this->contrato->fornecedor->cpf_cnpj_idgener;
        $fornNome = $this->contrato->fornecedor->nome;

        return $fornCpfCnpj . ' - ' . $fornNome;
    }

    /**
     * Retorna a Data de Início da Vigência
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getVigenciaInicio()
    {
        return $this->retornaDataAPartirDeCampo($this->contrato->vigencia_inicio);
    }

    /**
     * Retorna a Data de Término da Vigência
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getVigenciaFim()
    {
        return $this->retornaDataAPartirDeCampo($this->contrato->vigencia_fim);
    }

    /**
     * Retorna o valor global, formatado como moeda em pt-Br
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getValorGlobal()
    {
        return $this->retornaCampoFormatadoComoNumero($this->contrato->valor_global);
    }

    /**
     * Retorna o valor da parcela, formatado como moeda em pt-Br
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getValorParcela()
    {
        return $this->retornaCampoFormatadoComoNumero($this->contrato->valor_parcela);
    }

    /**
     * Retorna o usuário da ocorrência, exibindo CPF/CNPJ e nome do mesmo
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getUsuario()
    {
        $userCpfCnpj = $this->usuario->cpf;
        $userNome = $this->usuario->name;

        return $userCpfCnpj . ' - ' . $userNome;
    }

    /**
     * Retorna a situação da ocorrência
     *
     * @return mixed
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getSituacaoConsulta()
    {
        return $this->ocorSituacao->descricao;
    }

    /**
     * Retorna a nova situação da ocorrência
     *
     * @return mixed
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getSituacaoNovaConsulta()
    {
        $descricao = ' - ';

        if (isset($this->ocorSituacaoNova->descricao)) {
            $descricao = $this->ocorSituacaoNova->descricao;
        }

        return $descricao;
    }
    public function getListaArquivosComPath()
    {
        $arquivos_array = [];
        $i = 1;
        foreach ((array) $this->arquivos as $arquivo) {

            $arquivos_array[] = [
                'arquivo_'.$i => env('APP_URL'). '/storage/'. $arquivo,
            ];
            $i++;
        }
        return $arquivos_array;
    }

    public function ocorrenciaAPI($usuarioTransparencia)
    {
        return [
            'contrato_id' => $this->contrato_id,
            'numero' => $this->numero,
            'usuario' => $usuarioTransparencia,
            'data' => $this->data,
            'ocorrencia' => $this->ocorrencia,
            'notificapreposto' => $this->notificapreposto == true ? 'Sim' : 'Não',
            'emailpreposto' => $this->emailpreposto,
            'numeroocorrencia' => $this->getNumeroOcorrencia(),
            'novasituacao' => $this->getSituacaoNovaConsulta(),
            'situacao' => $this->ocorSituacao->descricao,
            'arquivos' => $this->getListaArquivosComPath(),
        ];
    }

    public function buscaOcorrenciasPorContratoId(int $contrato_id, $range)
    {
        $ocorrencias = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->where('contrato_id', $contrato_id)
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contratoocorrencias.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $ocorrencias;
    }

    public function buscaOcorrencias($range)
    {
        $ocorrencias = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contratoocorrencias.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $ocorrencias;
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

    public function fornecedor()
    {
        return $this->hasManyThrough(
            Fornecedor::class, //'App\Models\Fornecedor',
            Contrato::class, //'App\Models\Contrato',
            'id',
            'id',
            'contrato_id',
            'fornecedor_id'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(BackpackUser::class, 'user_id');
    }

    public function ocorSituacao()
    {
        return $this->belongsTo(Codigoitem::class, 'situacao');
    }

    public function ocorSituacaoNova()
    {
        return $this->belongsTo(Codigoitem::class, 'novasituacao');
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
        $destination_path = "ocorrencia/" . $contrato->id . "_" . str_replace('/', '_', $contrato->numero);

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

}
