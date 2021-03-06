<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class Contratocronograma
 * @package App\Models
 */
class Contratocronograma extends Model
{
    use CrudTrait;
    use LogsActivity;
    /**
     * @var bool
     */
    protected static $logFillable = true;
    /**
     * @var string
     */
    protected static $logName = 'contrato_cronograma';
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    /**
     * @var string
     */
    protected $table = 'contratocronograma';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    /**
     * @var array
     */
    protected $fillable = [
        'contrato_id',
        'contratohistorico_id',
        'receita_despesa',
        'mesref',
        'anoref',
        'vencimento',
        'valor',
        'retroativo',
        'soma_subtrai',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    /**
     * @param Contratohistorico $contratohistorico
     * @return $this|string
     */
    public function inserirCronogramaFromHistorico(Contratohistorico $contratohistorico)
    {
        if (!$contratohistorico->num_parcelas AND !$contratohistorico->vigencia_inicio AND !$contratohistorico->valor_parcela) {
            return '';
        }

        $dados = $this->montaCronograma($contratohistorico);
        $this->inserirDadosEmMassa($dados);

        return $this;

    }

    /**
     * @param Contratohistorico $contratohistorico
     * @return $this|string
     */
    public function atualizaCronogramaFromHistorico($historico)
    {

        foreach ($historico as $contratohistorico) {
            if (!$contratohistorico->num_parcelas AND !$contratohistorico->vigencia_inicio AND !$contratohistorico->valor_parcela) {
                return '';
            }

//            $contratohistorico->cronograma()->delete();
            $dados = $this->montaCronograma($contratohistorico);
            $this->inserirDadosEmMassa($dados);
        }

        return $this;

    }

    /**
     * @param Contratohistorico $contratohistorico
     * @return array
     */
    private function montaCronograma(Contratohistorico $contratohistorico)
    {

        if ($contratohistorico->data_inicio_novo_valor) {
            $data = date_create($contratohistorico->data_inicio_novo_valor);
            $mesinicio = new \DateTime($contratohistorico->data_inicio_novo_valor);
            $mesfim = new \DateTime($contratohistorico->data_fim_novo_valor ?: $contratohistorico->vigencia_fim);
            $interval = $mesinicio->diff($mesfim);
            if ($interval->y != 0) {
                $t = ($interval->y * 12) + $interval->m;
            } else {
                $t = $interval->m;
            }

            if($interval->d > 0)
            {
                $t = $t + 1;
            }

        } else {
            $data = date_create($contratohistorico->vigencia_inicio);
            $t = $contratohistorico->num_parcelas;
        }

        $mesref = date_format($data, 'Y-m');
        $mesrefnew = $mesref . "-01";

        $dados = [];
        for ($i = 1; $i <= $t; $i++) {
            $vencimento = date('Y-m-d', strtotime("+" . $i . " month", strtotime($mesrefnew)));
            $ref = date('Y-m-d', strtotime("-1 month", strtotime($vencimento)));

            $buscacron = $this->where('contrato_id', '=', $contratohistorico->contrato_id)
                ->where('mesref', '=', date('m', strtotime($ref)))
                ->where('anoref', '=', date('Y', strtotime($ref)))
                ->where('retroativo', '=', 'f')
                ->get();

            $valor = number_format($contratohistorico->valor_parcela,2,'.','');

            if ($buscacron){
                if ($contratohistorico->tipo_id == 65 or $contratohistorico->tipo_id == 68) {
                    $v = $valor;
                    foreach ($buscacron as $b) {
                        $v = number_format($v,2,'.', '') - number_format($b->valor,2,'.','');
                    }
                    $dados[] = [
                        'contrato_id' => $contratohistorico->contrato_id,
                        'contratohistorico_id' => $contratohistorico->id,
                        'receita_despesa' => $contratohistorico->receita_despesa,
                        'mesref' => date('m', strtotime($ref)),
                        'anoref' => date('Y', strtotime($ref)),
                        'vencimento' => $vencimento,
                        'valor' => number_format($v,2,'.', ''),
                        'soma_subtrai' => ($v < 0) ? false : true,
                    ];
                } else {
                    $dados[] = [
                        'contrato_id' => $contratohistorico->contrato_id,
                        'contratohistorico_id' => $contratohistorico->id,
                        'receita_despesa' => $contratohistorico->receita_despesa,
                        'mesref' => date('m', strtotime($ref)),
                        'anoref' => date('Y', strtotime($ref)),
                        'vencimento' => $vencimento,
                        'valor' => $valor,
                        'soma_subtrai' => ($valor < 0) ? false : true,
                    ];
                }
            }
        }

        if ($contratohistorico->retroativo == 1) {

            $ret_mesref_de = $contratohistorico->retroativo_mesref_de;
            $ret_anoref_de = $contratohistorico->retroativo_anoref_de;
            $ret_mesref_ate = $contratohistorico->retroativo_mesref_ate;
            $ret_anoref_ate = $contratohistorico->retroativo_anoref_ate;
            $ret_soma_subtrai = $contratohistorico->retroativo_soma_subtrai;


            if ($ret_mesref_de == $ret_mesref_ate AND $ret_anoref_de == $ret_anoref_ate) {
                $dados[] = [
                    'contrato_id' => $contratohistorico->contrato_id,
                    'contratohistorico_id' => $contratohistorico->id,
                    'receita_despesa' => $contratohistorico->receita_despesa,
                    'mesref' => $ret_mesref_de,
                    'anoref' => $ret_anoref_de,
                    'vencimento' => $contratohistorico->retroativo_vencimento,
                    'valor' => number_format($contratohistorico->retroativo_valor, 2, '.', ''),
                    'retroativo' => true,
                    'soma_subtrai' => $ret_soma_subtrai,
                ];
            } else {
                $mesrefde = new \DateTime($ret_anoref_de . '-' . $ret_mesref_de . '-01');
                $mesrefate = new \DateTime($ret_anoref_ate . '-' . $ret_mesref_ate . '-01');
                $intervalo = $mesrefde->diff($mesrefate);
                if ($intervalo->y != 0) {
                    $meses = ($intervalo->y * 12) + $intervalo->m + 1;
                } else {
                    $meses = $intervalo->m + 1;
                }

                $valor_ret = number_format($contratohistorico->retroativo_valor / $meses, 2, '.', '');

                for ($j = 1; $j <= $meses; $j++) {
                    $dtformat = $ret_anoref_de . '-' . $ret_mesref_de . '-01';

                    if ($j == 1) {
                        $ref1 = date('Y-m-d', strtotime($dtformat));
                    } else {
                        $p = $j - 1;
                        $ref1 = date('Y-m-d', strtotime("+" . $p . " month", strtotime($dtformat)));
                    }

                    $dados[] = [
                        'contrato_id' => $contratohistorico->contrato_id,
                        'contratohistorico_id' => $contratohistorico->id,
                        'receita_despesa' => $contratohistorico->receita_despesa,
                        'mesref' => date('m', strtotime($ref1)),
                        'anoref' => date('Y', strtotime($ref1)),
                        'vencimento' => $contratohistorico->retroativo_vencimento,
                        'valor' => number_format($valor_ret, 2, '.', ''),
                        'retroativo' => true,
                        'soma_subtrai' => $ret_soma_subtrai,
                    ];

                }

            }

        }

        return $dados;
    }

    /**
     * @param array $dados
     * @return bool
     */
    private function inserirDadosEmMassa(array $dados)
    {
        foreach ($dados as $d) {
            if ($d['valor'] != 0) {
                $cronograma = $this->insertCronograma($d);
            }
        }

        return true;

    }

    /**
     * @param array $dado
     * @return mixed
     */
    private function insertCronograma(array $dado)
    {

        $cronograma = new $this;
        $cronograma->fill($dado);
        $cronograma->save();

        return $cronograma;
    }

    /**
     * @return string
     */
    public function getReceitaDespesa()
    {
        if ($this->receita_despesa == 'D') {
            return 'Despesa';
        }
        if ($this->receita_despesa == 'R') {
            return 'Receita';
        }

        return '';
    }

    /**
     * @return mixed
     */
    public function getContratoNumero()
    {
        $contrato = Contrato::find($this->contrato_id);
        return $contrato->numero;

    }

    /**
     * @return string
     */
    public function getContratoHistorico()
    {
        $contratohistorico = Contratohistorico::find($this->contratohistorico_id);

        return $contratohistorico->tipo->descricao . ' - ' . $contratohistorico->numero;

    }

    /**
     * @param string $contrato_id
     * @return array
     */
    public function montaArrayTipoDescricaoNumeroInstrumento(string $contrato_id)
    {
        $array = [];
        $historico = Contratohistorico::where('contrato_id', '=', $contrato_id)
            ->orderBy('data_assinatura')
            ->get();

        foreach ($historico as $h) {
            $array[$h->id] = implode('/', array_reverse(explode('-',
                    $h->data_assinatura))) . ' | ' . $h->tipo->descricao . ' - ' . $h->numero;
        }
        return $array;
    }
    public function buscaCronogramasPorUg(int $ug)
    {
        $cronogramas = $this->whereHas('contrato', function ($contrato) use ($ug) {
            $contrato->whereHas('unidade', function ($unidade) use ($ug){
               $unidade->where('codigo',$ug);
            });
        });

        $cronogramas->leftjoin('contratos', 'contratos.id', '=', 'contratocronograma.contrato_id');
        $cronogramas->leftjoin('unidades', 'unidades.id', '=', 'contratos.unidade_id');

        $cronogramas->orderBy('unidade');
        $cronogramas->orderBy('anoref');
        $cronogramas->orderBy('mesref');

        $cronogramas->groupBy('unidade');
        $cronogramas->groupBy('mesref');
        $cronogramas->groupBy('anoref');

        $cronogramas->select([
            DB::raw('unidades.codigo || \' - \' || unidades.nomeresumido AS unidade'),
            DB::raw('contratocronograma.mesref || \'/\' || contratocronograma.anoref  AS mesref'),
            DB::raw('sum(valor) AS valor'),
        ]);

        return $cronogramas->get()->toArray();
    }

    /**
     * @return string
     */
    public function getMesAnoReferencia()
    {
        return $this->mesref . '/' . $this->anoref;

    }

    /**
     * @return string
     */
    public function formatVlr()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }

    /**
     * Retorna o Fornecedor, exibindo c??digo e nome do mesmo
     *
     * @return string
     */
    public function getFornecedor()
    {
        return $this->contrato->fornecedor->cpf_cnpj_idgener . ' - ' . $this->contrato->fornecedor->nome;
    }

    /**
     * Retorna a Data de In??cio da Vig??ncia
     *
     * @return string
     */
    public function getVigenciaInicio()
    {
        return $this->retornaDataAPartirDeCampo($this->contrato->vigencia_inicio);
    }

    /**
     * Retorna a Data de T??rmino da Vig??ncia
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    public function contratohistorico()
    {
        return $this->belongsTo(Contratohistorico::class, 'contratohistorico_id');
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
     * Retorna $campo data formatado no padr??o pt-Br: dd/mm/yyyy
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
     * Retorna $campo num??rico formatado no padr??o pt-Br: 0.000,00
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

    public function cronogramaAPI()
    {

        return [            
            'id' => $this->id,
            'contrato_id' => $this->contrato_id,
            'tipo' => $this->contratohistorico->tipo->descricao,
            'numero' => $this->contratohistorico->numero,
            'receita_despesa' => ($this->receita_despesa) == 'D' ? 'Despesa' : 'Receita',
            'observacao' => $this->contratohistorico->observacao,
            'mesref' => $this->mesref,
            'anoref' => $this->anoref,
            'vencimento' => $this->vencimento,
            'retroativo' => ($this->retroativo) == true ? 'Sim' : 'N??o',
            'valor' => number_format($this->valor, 2, ',', '.'),
        ];
    }

    public function buscaCronogramasPorContratoIdAPI(int $contrato_id, $range)
    {
        $cronogramas = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->where('contrato_id', $contrato_id)
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contratocronograma.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $cronogramas;
    }

    public function buscaCronogramasAPI($range)
    {
        $cronogramas = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contratocronograma.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $cronogramas;
    }

}
