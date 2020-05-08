<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ContratoocorrenciaConsulta extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratoocorrencias';
    protected $dateFormat = 'd/m/Y';
    // protected $fillable = [];
    protected $casts = [
        'arquivos' => 'array'
    ];

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getFornecedor()
    {
        $fornCpfCnpj = $this->contrato->fornecedor->cpf_cnpj_idgener;
        $fornNome = $this->contrato->fornecedor->nome;

        return $fornCpfCnpj . ' - ' . $fornNome;
    }

    public function getVigenciaInicio()
    {
        return $this->retornaDataAPartirDeCampo($this->contrato->vigencia_inicio);
    }

    public function getVigenciaFim()
    {
        return $this->retornaDataAPartirDeCampo($this->contrato->vigencia_fim);
    }

    public function getvalorGlobal()
    {
        return $this->retornaCampoFormatadoComoNumero($this->contrato->valor_global);
    }

    public function getValorParcela()
    {
        return $this->retornaCampoFormatadoComoNumero($this->contrato->valor_parcela);
    }

    public function getUsuario()
    {
        $userCpfCnpj = $this->usuario->cpf;
        $userNome = $this->usuario->name;

        return $userCpfCnpj . ' - ' . $userNome;
    }

    public function getSituacao()
    {
        return $this->ocorSituacao->descricao;
    }

    public function getSituacaoNova()
    {
        return $this->ocorSituacaoNova->descricao;
    }

    public function getArquivos()
    {
        return 'sem arquivos...';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id', 'id');
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
        return $this->belongsTo(BackpackUser::class,'user_id');
    }

    public function ocorSituacao()
    {
        return $this->belongsTo(Codigoitem::class, 'situacao');
    }

    public function ocorSituacaoNova()
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
