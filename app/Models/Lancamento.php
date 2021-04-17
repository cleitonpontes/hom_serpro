<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Movimentacaocontratoconta;
use App\Models\Contratoconta;
class Lancamento extends Model
{
    use CrudTrait;
    use LogsActivity;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'lancamentos';
    protected $fillable = [
        'contratoterceirizado_id', 'encargo_id', 'valor', 'movimentacao_id'
    ];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getValorTotalLancamentosByIdMovimentacao($idMovimentacao){
        $valorTotal = Lancamento::where('movimentacao_id', '=', $idMovimentacao)->sum('valor');
        // \Log::info('total mov = '.$valorTotal);
        return $valorTotal;
    }
    public function getSalarioContratoTerceirizado(){
        $objContratoTerceirizado = Contratoterceirizado::find($this->contratoterceirizado_id);
        return $objContratoTerceirizado->salario;
    }
    public function getNomePessoaContratoTerceirizado(){
        $objContratoTerceirizado = Contratoterceirizado::find($this->contratoterceirizado_id);
        return $objContratoTerceirizado->nome;
    }
    public function getTipoEncargo(){
        $objEncargo = Encargo::find($this->encargo_id);
        $objCodigoItem = Codigoitem::find($objEncargo->tipo_id);
        return $descricao= $objCodigoItem->descricao;
    }
    // Com a mudança na regra do grupo A, que passou para a tabela da conta, o encargo_id pode chegar aqui em branco.
    // este método se repete em Extratocontratoconta.php
    public function getTipoEncargoOuGrupoA(){
        if($this->encargo_id != null){
            $idEncargo = $this->encargo_id;
            $objEncargo = Encargo::find($idEncargo);
            $objCodigoItem = Codigoitem::find($objEncargo->tipo_id);
            return $descricao= $objCodigoItem->descricao;
        }
        return 'Incidência do Submódulo 2.2 sobre férias, 1/3 (um terço) constitucional de férias e 13o (décimo terceiro) salário';
    }
    public function getPercentualEncargo(){
        $objEncargo = Encargo::find($this->encargo_id);
        return $objEncargo->percentual;
    }
    public function getPercentualEncargoOuGrupoA(){
        if( $this->encargo_id != null ){
            $objEncargo = Encargo::find($this->encargo_id);
            return $objEncargo->percentual;
        } else {
            $idLancamento = $this->id;
            $idContratoConta = Contratoconta::getIdContratocontaByidLancamento($idLancamento);
            $objContratoconta = Contratoconta::where('id', $idContratoConta)->first();
            return $percentual = $objContratoconta->percentual_grupo_a_13_ferias;
        }
    }
    public function formatValor(){
        return number_format($this->valor, 2, ',', '.');
    }
    public function getTipoMovimentacao(){
        $idMovimentacao = $this->movimentacao_id;
        $objMovimentacao = Movimentacaocontratoconta::find($idMovimentacao);
        return $objMovimentacao->getTipoMovimentacao();
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
