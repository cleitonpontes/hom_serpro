<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

use App\Models\Lancamento;


class Movimentacaocontratoconta extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'movimentacaocontratocontas';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'contratoconta_id', 'tipo_id', 'mes_competencia', 'ano_competencia', 'valor_total_mes_ano',
        'situacao_movimentacao', 'user_id'
    ];
    // protected $hidden = [];
    // protected $dates = [];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function excluirMovimentacao($idMovimentacao){
        if($objMovimentacaocontratoconta = Movimentacaocontratoconta::where('id','=',$idMovimentacao)->delete()){return true;}
        else{return false;}
    }
    public function verificarSeMovimentacaoExiste($request){
        // vamos verificar se para este mês / ano / tipoMovimentacao já existe lançamento
        $mesCompetencia = $request->input('mes_competencia');
        $anoCompetencia = $request->input('ano_competencia');
        $tipoMovimentacao = $request->input('tipo_id');
        $idContratoConta = $request->input('contratoconta_id');
        $qtdMovimentacoes = Depositocontratoconta::where('tipo_id','=',$tipoMovimentacao)
            ->where('contratoconta_id','=', $idContratoConta)
            ->where('mes_competencia','=', $mesCompetencia)
            ->where('ano_competencia','=', $anoCompetencia)
            ->count();
        if($qtdMovimentacoes>0){return true;}
        return false;
    }
    public function criarMovimentacao($request){
        $objMovimentacaocontratoconta = new Movimentacaocontratoconta();
        $objMovimentacaocontratoconta->contratoconta_id = $request->input('contratoconta_id');
        $objMovimentacaocontratoconta->tipo_id = $request->input('tipo_id');
        $objMovimentacaocontratoconta->mes_competencia = $request->input('mes_competencia');
        $objMovimentacaocontratoconta->ano_competencia = $request->input('ano_competencia');
        $objMovimentacaocontratoconta->valor_total_mes_ano = 0;
        $objMovimentacaocontratoconta->situacao_movimentacao = $request->input('situacao_movimentacao');
        $objMovimentacaocontratoconta->user_id = $request->input('user_id');
        if($objMovimentacaocontratoconta->save()){
            return $objMovimentacaocontratoconta->id;
        } else {
            echo false;
        }
    }
    public function alterarStatusMovimentacao($idMovimentacao, $statusMovimentacao){
        $objMovimentacao = Movimentacaocontratoconta::where('id','=',$idMovimentacao)->first();
        $objMovimentacao->situacao_movimentacao = $statusMovimentacao;
        if(!$objMovimentacao->save()){
            return false;
        } else {
            return true;
        }
    }
    public function getTotalMovimentacao(){
        $objLancamento = new Lancamento();
        $idMovimentacao= $this->id;
        // vamos pegar todosos lançamentos da movimentacao e somá-los
        $valorTotal = $objLancamento->getValorTotalLancamentosByIdMovimentacao($idMovimentacao);
        return $valorTotal = number_format($valorTotal, 2, ',', '.');
    }
    public function getTipoMovimentacao(){
        $objCodigoItem = Codigoitem::find($this->tipo_id);
        return $descricao= $objCodigoItem->descricao;
    }
    public function getIdEncargoByIdCodigoitens($idCodigoitens){
        $obj = \DB::table('encargos')
            ->select('encargos.id')
            ->join('contratoitens', 'contratoitens.id', '=', 'encargos.tipo_id')
            ->where('contratoitens.id', '=', $idCodigoitens)
            ->first();
        return $idEncargo = $obj->id;
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
