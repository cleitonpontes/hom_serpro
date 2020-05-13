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
    use SoftDeletes;

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
        $descricao = '';

        if (isset($this->ocorSituacaoNova->descricao)) {
            $descricao = $this->ocorSituacaoNova->descricao;
        }

        return $descricao;
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

    /**
     * Retorna $campo data formatado no padrão pt-Br: dd/mm/yyyy
     *
     * @param $campo
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaDataAPartirDeCampo($campo)
    {
        try {
            $data = \DateTime::createFromFormat('Y-m-d', $campo);
            $retorno = $data->format('d/m/Y');
        } catch (\Exception $e) {
            $retorno = '';
        }

        return $retorno;
    }

    /**
     * Retorna $campo numérico formatado no padrão pt-Br: 0.000,00
     *
     * @param $campo
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaCampoFormatadoComoNumero($campo)
    {
        try {
            $retorno = number_format($campo, 2, ',', '.');
        } catch (\Exception $e) {
            $retorno = '';
        }

        return $retorno;
    }

}
