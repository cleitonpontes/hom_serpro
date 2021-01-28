<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Http\Controllers\AdminController;
use Spatie\Activitylog\Traits\LogsActivity;

class Funcionarioscontratoconta extends Model
{
    use CrudTrait;
    use LogsActivity;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratoterceirizados';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['cpf', 'nome'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function salvarNovoSalario($idContratoTerceirizado, $novoSalario){
        $objContratoTerceirizadoSalvarSalario = Funcionarioscontratoconta::where('id', $idContratoTerceirizado)->first();
        $objContratoTerceirizadoSalvarSalario->salario = $novoSalario;
        $objContratoTerceirizadoSalvarSalario->save();
        return true;
    }

    public function getCpfFormatado(){
        $base = new AdminController();
        $cpf = $this->cpf;
        $cpf = '815.199.581-53';
        // vamos verificar se o cpf ainda não está formatado
        $posicaoPonto = strpos($cpf, '.');
        if($posicaoPonto > 0){
            return $cpf;
        }
        return $cpf = $base->formataCnpjCpfTipo($cpf, 'FISICA');
    }
    public function getSituacaoFuncionario(){
        $situacao = $this->situacao;
        if($situacao == 't'){return 'Alocado';} // retornava Admitido - foi solicitada a alteração.
        else{return 'Demitido';}
    }
    public function getIdCodigoitensDeposito(){

        // buscar os tipos de movimentação em codigoitens para seleção
        $objTipoMovimentacaoRetirada = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo Movimentação');
        })
        // ->where('descricao', '=', 'Depósito')
        ->where('descricao', '=', 'Provisão')
        ->first();
        return $idTipoMovimentacaoRetirada = $objTipoMovimentacaoRetirada->id;

    }
    public function getTotalDeposito(){
        $idContratoTerceirizado = $this->id;
        // $idCodigoitensDeposito = self::getIdCodigoitensDeposito();
        $objContratoConta = new Contratoconta();
        $saldo = $objContratoConta->getSaldoDepositoPorContratoTerceirizado($idContratoTerceirizado);
        // \Log::info('saldo depósito = '.$saldoDeposito);
        return $saldo;
    }
    public function getTotalRetirada(){
        $idContratoTerceirizado = $this->id;
        // $idCodigoitensDeposito = self::getIdCodigoitensDeposito();
        $objContratoConta = new Contratoconta();
        $saldo = $objContratoConta->getSaldoRetiradaPorContratoTerceirizado($idContratoTerceirizado);
        // \Log::info('saldo depósito = '.$saldoDeposito);
        return $saldo;
    }
    public function getSaldoContratoTerceirizado(){
        $totalDeposito = self::getTotalDeposito();
        $totalRetirada = self::getTotalRetirada();
        return  number_format(floatval(($totalDeposito - $totalRetirada)), 2, '.', '');
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
