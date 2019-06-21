<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratocronograma extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'contrato_cronograma';
    use SoftDeletes;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratocronograma';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'contrato_id',
        'contratohistorico_id',
        'receita_despesa',
        'mesref',
        'anoref',
        'vencimento',
        'valor',
        'retroativo',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
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

    public function atualizaCronogramaFromHistorico(Contratohistorico $contratohistorico)
    {
        if (!$contratohistorico->num_parcelas AND !$contratohistorico->vigencia_inicio AND !$contratohistorico->valor_parcela) {
            return '';
        }

        $contratohistorico->cronograma()->delete();
        $dados = $this->montaCronograma($contratohistorico);
        $this->inserirDadosEmMassa($dados);

        return $this;

    }

    private function montaCronograma(Contratohistorico $contratohistorico)
    {
        $data = date_create($contratohistorico->vigencia_inicio);
        $mesref = date_format($data, 'Y-m');
        $mesrefnew = $mesref . "-01";

        $t = $contratohistorico->num_parcelas;
        $dados = [];
        for ($i = 1; $i <= $t; $i++) {
            $vencimento = date('Y-m-d', strtotime("+" . $i . " month", strtotime($mesrefnew)));
            $ref = date('Y-m-d', strtotime("-1 month", strtotime($vencimento)));

            $buscacron = $this->where('contrato_id','=',$contratohistorico->contrato_id)
                ->where('mesref','=',date('m', strtotime($ref)))
                ->where('anoref','=',date('Y', strtotime($ref)))
                ->where('retroativo','=','f')
                ->get();

            $valor = $contratohistorico->valor_parcela;

            if($buscacron){
                $v = $valor;
                foreach ($buscacron as $b){
                    $v = $valor - $b->valor;
                }
                    $dados[] = [
                        'contrato_id' => $contratohistorico->contrato_id,
                        'contratohistorico_id' => $contratohistorico->id,
                        'receita_despesa' => $contratohistorico->receita_despesa,
                        'mesref' => date('m', strtotime($ref)),
                        'anoref' => date('Y', strtotime($ref)),
                        'vencimento' => $vencimento,
                        'valor' => $v,
                    ];
            }else{
                $dados[] = [
                    'contrato_id' => $contratohistorico->contrato_id,
                    'contratohistorico_id' => $contratohistorico->id,
                    'receita_despesa' => $contratohistorico->receita_despesa,
                    'mesref' => date('m', strtotime($ref)),
                    'anoref' => date('Y', strtotime($ref)),
                    'vencimento' => $vencimento,
                    'valor' => $valor,
                ];
            }



        }

        return $dados;
    }

    private function inserirDadosEmMassa(array $dados)
    {
        foreach ($dados as $d){
            if($d['valor'] != 0){
                $cronograma = $this->insertCronograma($d);
            }
        }

        return true;

    }

    private function insertCronograma(array $dado){

        $cronograma = new $this;
        $cronograma->fill($dado);
        $cronograma->save();

        return $cronograma;
    }


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

    public function getContratoNumero()
    {
        $contrato = Contrato::find($this->contrato_id);
        return $contrato->numero;

    }

    public function getContratoHistorico()
    {
        $contratohistorico = Contratohistorico::find($this->contratohistorico_id);

        return $contratohistorico->tipo->descricao . ' - ' . $contratohistorico->numero;

    }

    public function montaArrayTipoDescricaoNumeroInstrumento(string $contrato_id)
    {
        $array = [];

        $historico = Contratohistorico::where('contrato_id','=',$contrato_id)
            ->orderBy('data_assinatura')
            ->get();

        foreach ($historico as $h){
            $array[$h->id] = implode('/',array_reverse(explode('-',$h->data_assinatura))) . ' | ' . $h->tipo->descricao . ' - ' . $h->numero;
        }

        return $array;

    }

    public function getMesAnoReferencia()
    {
        return $this->mesref . '/' . $this->anoref;

    }

    public function formatVlr()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
