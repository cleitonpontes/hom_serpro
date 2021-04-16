<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Movimentacaocontratoconta;
class Extratocontratoconta extends Model
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
    public function getTipoMovimentacao(){
        $idMovimentacao = $this->movimentacao_id;
        $objMovimentacao = Movimentacaocontratoconta::find($idMovimentacao);
        return $objMovimentacao->getTipoMovimentacao();
    }
    public function getNomeResumidoUnidadeMovimentacao(){
        $idMovimentacao = $this->movimentacao_id;
        $objMovimentacao = Movimentacaocontratoconta::find($idMovimentacao);
        return $objMovimentacao->getNomeResumidoUnidadeMovimentacao();
    }
    // Com a mudança na regra do grupo A, que passou para a tabela da conta, o encargo_id pode chegar aqui em branco.
    // este método se repete em Lancamento.php
    public function getTipoEncargoOuGrupoA(){
        if($this->encargo_id != null){
            $idEncargo = $this->encargo_id;
            $objEncargo = Encargo::find($idEncargo);
            $objCodigoItem = Codigoitem::find($objEncargo->tipo_id);
            return $descricao= $objCodigoItem->descricao;
        }
        return 'Incidência do Submódulo 2.2 sobre férias, 1/3 (um terço) constitucional de férias e 13o (décimo terceiro) salário';
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
