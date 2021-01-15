<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratoconta extends Model
{

    protected $primaryKey = 'id';

    use CrudTrait;
    use LogsActivity;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratocontas';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'contrato_id', 'banco', 'conta', 'agencia', 'conta_corrente', 'fat_empresa'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getIdContratoContaByIdContratoTerceirizado($idContratoTerceirizado){
        $obj = \DB::table('contratoterceirizados')
            ->select('contratocontas.id')
            ->where('contratoterceirizados.id','=',$idContratoTerceirizado)
            ->join('contratos', 'contratos.id', '=', 'contratoterceirizados.contrato_id')
            ->join('contratocontas', 'contratocontas.contrato_id', '=', 'contratos.id')
            ->first();
        $idContratoConta = $obj->id;
        return $idContratoConta;
    }



    public function alterarSituacaoFuncionárioParaDemitido($idContratoTerceirizado, $dataDemissao){
        $objContratoTerceirizado = Contratoterceirizado::where('id', '=', $idContratoTerceirizado)->first();
        $objContratoTerceirizado->situacao = 'f';
        $objContratoTerceirizado->data_fim = $dataDemissao;
        if( $objContratoTerceirizado->save() ){
            return true;
        } else {
            return false;
        }
    }
    public function getSaldoContratoContaPorIdEncargoPorContratoTerceirizado($idContratoTerceirizado, $idEncargo){
        $tipoIdEncargo = self::getTipoIdEncargoByIdEncargo($idEncargo);
        return $saldo = self::getSaldoContratoContaPorTipoEncargoPorContratoTerceirizado($idContratoTerceirizado, $tipoIdEncargo);
    }
    public function getTipoIdEncargoByIdEncargo($idEncargoInformado){
        return $id = Encargo::where('id', '=', $idEncargoInformado)->first()->tipo_id;
    }
    public function getSaldoDepositoPorTipoEncargoPorContratoTerceirizado($idContratoTerceirizado, $tipo_id){
        $saldoDeposito = \DB::table('lancamentos')
            ->join('movimentacaocontratocontas', 'lancamentos.movimentacao_id', '=', 'movimentacaocontratocontas.id')

            ->join('codigoitens', 'codigoitens.id', '=', 'movimentacaocontratocontas.tipo_id')
            // ->where('codigoitens.descricao','=','Depósito')
            ->where('codigoitens.descricao','=','Provisão')

            ->join('codigos', 'codigos.id', '=', 'codigoitens.codigo_id')
            ->where('codigos.descricao','=','Tipo Movimentação')

            ->join('encargos', 'encargos.id', '=', 'lancamentos.encargo_id')
            ->where('encargos.tipo_id', '=', $tipo_id)

            ->where('lancamentos.contratoterceirizado_id','=',$idContratoTerceirizado)
            ->sum('lancamentos.valor');
        return $saldoDeposito = number_format(floatval($saldoDeposito), 2, '.', '');
    }
    public function getSaldoDepositoPorContratoTerceirizado($idContratoTerceirizado){
        $saldoDeposito = \DB::table('lancamentos')
            ->join('movimentacaocontratocontas', 'lancamentos.movimentacao_id', '=', 'movimentacaocontratocontas.id')

            ->join('codigoitens', 'codigoitens.id', '=', 'movimentacaocontratocontas.tipo_id')
            // ->where('codigoitens.descricao','=','Depósito')
            ->where('codigoitens.descricao','=','Provisão')

            ->join('codigos', 'codigos.id', '=', 'codigoitens.codigo_id')
            ->where('codigos.descricao','=','Tipo Movimentação')

            ->where('lancamentos.contratoterceirizado_id','=',$idContratoTerceirizado)
            ->sum('lancamentos.valor');
        return $saldoDeposito = number_format(floatval($saldoDeposito), 2, '.', '');
    }
    public function getSaldoRetiradaPorTipoEncargoPorContratoTerceirizado($idContratoTerceirizado, $tipo_id){
        $saldoRetirada = \DB::table('lancamentos')
            ->join('movimentacaocontratocontas', 'lancamentos.movimentacao_id', '=', 'movimentacaocontratocontas.id')

            ->join('codigoitens', 'codigoitens.id', '=', 'movimentacaocontratocontas.tipo_id')
            // ->where('codigoitens.descricao','=','Retirada')
            ->where('codigoitens.descricao','=','Liberação')

            ->join('codigos', 'codigos.id', '=', 'codigoitens.codigo_id')
            ->where('codigos.descricao','=','Tipo Movimentação')

            ->join('encargos', 'encargos.id', '=', 'lancamentos.encargo_id')
            ->where('encargos.tipo_id', '=', $tipo_id)

            ->where('lancamentos.contratoterceirizado_id','=',$idContratoTerceirizado)
            ->sum('lancamentos.valor');
        return $saldoRetirada = number_format(floatval($saldoRetirada), 2, '.', '');
    }
    public function getSaldoRetiradaPorContratoTerceirizado($idContratoTerceirizado){
        $saldoRetirada = \DB::table('lancamentos')
            ->join('movimentacaocontratocontas', 'lancamentos.movimentacao_id', '=', 'movimentacaocontratocontas.id')
            ->join('codigoitens', 'codigoitens.id', '=', 'movimentacaocontratocontas.tipo_id')
            ->join('codigos', 'codigos.id', '=', 'codigoitens.codigo_id')
            ->where('codigos.descricao','=','Tipo Movimentação')
            // ->where('codigoitens.descricao','=','Retirada')
            ->where('codigoitens.descricao','=','Liberação')
            ->where('lancamentos.contratoterceirizado_id','=',$idContratoTerceirizado)
            ->sum('lancamentos.valor');
        return $saldoRetirada = number_format(floatval($saldoRetirada), 2, '.', '');
    }
    public function getSaldoContratoContaPorContratoTerceirizado($idContratoTerceirizado){
        $saldoDeposito = self::getSaldoDepositoPorContratoTerceirizado($idContratoTerceirizado);
        $saldoRetirada = self::getSaldoRetiradaPorContratoTerceirizado($idContratoTerceirizado);
        return $saldo = ($saldoDeposito - $saldoRetirada);
    }
    public function getSaldoContratoContaPorTipoEncargoPorContratoTerceirizado($idContratoTerceirizado, $tipo_id){
        $saldoDeposito = self::getSaldoDepositoPorTipoEncargoPorContratoTerceirizado($idContratoTerceirizado, $tipo_id);
        $saldoRetirada = self::getSaldoRetiradaPorTipoEncargoPorContratoTerceirizado($idContratoTerceirizado, $tipo_id);
        return $saldo = ($saldoDeposito - $saldoRetirada);
    }
    public function getSaldoContratoContaParaColunas(){
        $idContrato = $this->contrato_id;
        $arrayTodosContratosTerceirizadosByIdContrato = Contratoterceirizado::where('contrato_id', $idContrato)->get();
        $saldoTotal = 0;
        foreach($arrayTodosContratosTerceirizadosByIdContrato as $objContratoTerceirizado){
            $idContratoTerceirizado = $objContratoTerceirizado->id;
            $saldoContratoTerceirizado = self::getSaldoContratoContaPorContratoTerceirizado($idContratoTerceirizado);
            $saldoTotal = ( $saldoTotal + $saldoContratoTerceirizado );
        }
        return number_format($saldoTotal, 2, ',', '.');
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
