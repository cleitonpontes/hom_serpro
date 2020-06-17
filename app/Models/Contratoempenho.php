<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use function foo\func;

class Contratoempenho extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'contratoempenhos';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratoempenhos';
    // protected $primaryKey = 'id';
    public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'contrato_id',
        'fornecedor_id',
        'empenho_id'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function inserirContratoEmpenhoMigracaoConta(array $dados)
    {
        $this->fill($dados);
        $this->save();

        return $this;
    }

    public function buscaTodosEmpenhosContratosAtivos()
    {
        $empenhos = $this->whereHas('contrato', function ($c) {
            $c->where('situacao', true);
        })->get();

        return $empenhos;

    }

    public function getContrato()
    {
        if ($this->contrato_id) {
            $contrato = Contrato::find($this->contrato_id);
            return $contrato->numero;
        }

        return '';

    }

    public function getFornecedor()
    {
        $fornecedor = Fornecedor::find($this->fornecedor_id);
        return $fornecedor->cpf_cnpj_idgener . ' - ' . $fornecedor->nome;

    }

    public function getFornecedorEmpenho()
    {

        $empenho = Empenho::find($this->empenho_id);

        if ($empenho->fornecedor_id) {
            $fornecedor = $empenho->fornecedor()->first();
            return $fornecedor->cpf_cnpj_idgener . ' - ' . $fornecedor->nome;
        }

        return '';

    }

    public function getEmpenho()
    {
        $empenho = Empenho::find($this->empenho_id);
        return $empenho->numero;

    }

    /**
     * Retrona o código e a descrição do Plano Interno
     *
     * @return string
     */
    public function getPi()
    {
        $empenho = Empenho::find($this->empenho_id);
        if ($empenho->planointerno_id) {
            $planointerno = $empenho->planointerno()->first();
            return $planointerno->codigo . ' - ' . $planointerno->descricao;
        }

        return '-';

    }

    public function getNatureza()
    {
        $empenho = Empenho::find($this->empenho_id);

        if ($empenho->naturezadespesa_id) {

            $naturezadespesa = $empenho->naturezadespesa()->first();
            return $naturezadespesa->codigo . ' - ' . $naturezadespesa->descricao;

        }

        return '';

    }

    /**
     * Retorna a Data de Início da Vigência
     *
     * @return string
     */
    public function getVigenciaInicio()
    {
        return $this->retornaDataAPartirDeCampo($this->contrato->vigencia_inicio);
    }

    /**
     * Retorna a Data de Término da Vigência
     *
     * @return string
     */
    public function getVigenciaFim()
    {
        return $this->retornaDataAPartirDeCampo($this->contrato->vigencia_fim);
    }

    /**
     * Retorna o valor global, formatado como moeda em pt-Br
     *
     * @return string
     */
    public function getValorGlobal()
    {
        return $this->retornaCampoFormatadoComoNumero($this->contrato->valor_global);
    }

    /**
     * Retorna o valor da parcela, formatado como moeda em pt-Br
     *
     * @return string
     */
    public function getValorParcela()
    {
        return $this->retornaCampoFormatadoComoNumero($this->contrato->valor_parcela);
    }

    public function formatVlrEmpenhado()
    {
        if ($this->empenho_id) {
            $empenho = Empenhos::find($this->empenho_id);
            return 'R$ ' . number_format($empenho->empenhado, 2, ',', '.');
        }

        return '';
    }

    public function formatVlraLiquidar()
    {
        if ($this->empenho_id) {
            $empenho = Empenhos::find($this->empenho_id);
            return 'R$ ' . number_format($empenho->aliquidar, 2, ',', '.');
        }

        return '';
    }

    public function formatVlrLiquidado()
    {
        if ($this->empenho_id) {
            $empenho = Empenhos::find($this->empenho_id);
            return 'R$ ' . number_format($empenho->liquidado, 2, ',', '.');
        }

        return '';

    }

    public function formatVlrPago()
    {
        if ($this->empenho_id) {
            $empenho = Empenhos::find($this->empenho_id);
            return 'R$ ' . number_format($empenho->pago, 2, ',', '.');
        }

        return '';

    }

    public function formatVlrRpInscrito()
    {
        if ($this->empenho_id) {
            $empenho = Empenhos::find($this->empenho_id);
            return 'R$ ' . number_format($empenho->rpinscrito, 2, ',', '.');
        }

        return '';

    }

    public function formatVlrRpaLiquidar()
    {
        if ($this->empenho_id) {
            $empenho = Empenhos::find($this->empenho_id);
            return 'R$ ' . number_format($empenho->rpaliquidar, 2, ',', '.');
        }

        return '';

    }

    public function formatVlrRpLiquidado()
    {
        if ($this->empenho_id) {
            $empenho = Empenhos::find($this->empenho_id);
            return 'R$ ' . number_format($empenho->rpliquidado, 2, ',', '.');
        }

        return '';

    }

    public function formatVlrRpPago()
    {
        if ($this->empenho_id) {
            $empenho = Empenhos::find($this->empenho_id);
            return 'R$ ' . number_format($empenho->rppago, 2, ',', '.');
        }

        return '';

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

    public function empenho()
    {
        return $this->belongsTo(Empenho::class, 'empenho_id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
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

    /**
     * Retorna $campo data formatado no padrão pt-Br: dd/mm/yyyy
     *
     * @param $campo
     * @return string
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
