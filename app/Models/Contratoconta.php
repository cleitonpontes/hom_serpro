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
    protected $fillable = [
        'contrato_id', 'banco', 'conta', 'agencia', 'conta_corrente', 'fat_empresa', 'percentual_grupo_a_13_ferias', 'percentual_submodulo22'
    ];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function getIdContratocontaByidLancamento($idLancamento){
        $obj = \DB::table('lancamentos')
            ->select('contratocontas.id')
            ->where('lancamentos.id','=',$idLancamento)
            ->join('movimentacaocontratocontas', 'movimentacaocontratocontas.id', '=', 'lancamentos.movimentacao_id')
            ->join('contratocontas', 'contratocontas.id', '=', 'movimentacaocontratocontas.contratoconta_id')
            ->first();
        $idContratoConta = $obj->id;
        return $idContratoConta;
    }
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
    public function alterarSituacaoFuncion├írioParaDemitido($idContratoTerceirizado, $dataDemissao){
        $objContratoTerceirizado = Contratoterceirizado::where('id', '=', $idContratoTerceirizado)->first();
        $objContratoTerceirizado->situacao = 'f';
        $objContratoTerceirizado->data_fim = $dataDemissao;
        if( $objContratoTerceirizado->save() ){
            return true;
        } else {
            return false;
        }
    }
    // retorna ativa ou encerrada, de acordo com a data de encerramento.
    public function getStatusDaConta(){
        if($this->data_encerramento == null){return 'Ativa';}
        else{
            $dataEncerramento = $this->data_encerramento;
            return '<font color="red">Encerrada em '.$dataEncerramento.'</font>';
        }
    }
    public function getSaldoContratoContaPorIdEncargoPorContratoTerceirizado($idContratoTerceirizado, $idEncargo){
        $tipoIdEncargo = self::getTipoIdEncargoByIdEncargo($idEncargo);
        return $saldo = self::getSaldoContratoContaPorTipoEncargoPorContratoTerceirizado($idContratoTerceirizado, $tipoIdEncargo);
    }
    /**
     * O saldo para grupo A ser├í pego de forma diferente
     * Ap├│s reuni├úo com Gabriel, em 04/2021 - grupo A ser├í armazenado em contrato conta e n├úo mais em encargo,
     * pois ir├í variar de conta para conta.
     */
    public function getSaldoContratoContaGrupoAPorContratoTerceirizado($idContratoTerceirizado){
        $saldoDeposito = self::getSaldoDepositoGrupoAPorContratoTerceirizado($idContratoTerceirizado);
        $saldoRetirada = self::getSaldoRetiradaGrupoAPorContratoTerceirizado($idContratoTerceirizado);
        return $saldo = ($saldoDeposito - $saldoRetirada);
    }
    public function getSaldoDepositoGrupoAPorContratoTerceirizado($idContratoTerceirizado){
        $saldoDeposito = \DB::table('lancamentos')
            ->join('movimentacaocontratocontas', 'lancamentos.movimentacao_id', '=', 'movimentacaocontratocontas.id')
            ->join('codigoitens', 'codigoitens.id', '=', 'movimentacaocontratocontas.tipo_id')
            ->where('codigoitens.descricao','=','Provis├úo')
            ->join('codigos', 'codigos.id', '=', 'codigoitens.codigo_id')
            ->where('codigos.descricao','=','Tipo Movimenta├ž├úo')
            ->where('lancamentos.encargo_id', null)
            ->where('lancamentos.contratoterceirizado_id','=',$idContratoTerceirizado)
            ->sum('lancamentos.valor');
        return $saldoDeposito = number_format(floatval($saldoDeposito), 2, '.', '');
    }
    public function getSaldoRetiradaGrupoAPorContratoTerceirizado($idContratoTerceirizado){
        $saldoRetirada = \DB::table('lancamentos')
            ->join('movimentacaocontratocontas', 'lancamentos.movimentacao_id', '=', 'movimentacaocontratocontas.id')
            ->join('codigoitens', 'codigoitens.id', '=', 'movimentacaocontratocontas.tipo_id')
            ->where('codigoitens.descricao','=','Libera├ž├úo')
            ->join('codigos', 'codigos.id', '=', 'codigoitens.codigo_id')
            ->where('codigos.descricao','=','Tipo Movimenta├ž├úo')
            ->where('lancamentos.encargo_id', null)
            ->where('lancamentos.contratoterceirizado_id','=',$idContratoTerceirizado)
            ->sum('lancamentos.valor');
        return $saldoRetirada = number_format(floatval($saldoRetirada), 2, '.', '');
    }
    public function getTipoIdEncargoByIdEncargo($idEncargoInformado){
        return $id = Encargo::where('id', '=', $idEncargoInformado)->first()->tipo_id;
    }
    public function getSaldoDepositoPorTipoEncargoPorContratoTerceirizado($idContratoTerceirizado, $tipo_id){
        $saldoDeposito = \DB::table('lancamentos')
            ->join('movimentacaocontratocontas', 'lancamentos.movimentacao_id', '=', 'movimentacaocontratocontas.id')
            ->join('codigoitens', 'codigoitens.id', '=', 'movimentacaocontratocontas.tipo_id')
            ->where('codigoitens.descricao','=','Provis├úo')
            ->join('codigos', 'codigos.id', '=', 'codigoitens.codigo_id')
            ->where('codigos.descricao','=','Tipo Movimenta├ž├úo')
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
            ->where('codigoitens.descricao','=','Provis├úo')
            ->join('codigos', 'codigos.id', '=', 'codigoitens.codigo_id')
            ->where('codigos.descricao','=','Tipo Movimenta├ž├úo')
            ->where('lancamentos.contratoterceirizado_id','=',$idContratoTerceirizado)
            ->sum('lancamentos.valor');
        return $saldoDeposito = number_format(floatval($saldoDeposito), 2, '.', '');
    }
    public function getSaldoRetiradaPorTipoEncargoPorContratoTerceirizado($idContratoTerceirizado, $tipo_id){
        $saldoRetirada = \DB::table('lancamentos')
            ->join('movimentacaocontratocontas', 'lancamentos.movimentacao_id', '=', 'movimentacaocontratocontas.id')
            ->join('codigoitens', 'codigoitens.id', '=', 'movimentacaocontratocontas.tipo_id')
            ->where('codigoitens.descricao','=','Libera├ž├úo')
            ->join('codigos', 'codigos.id', '=', 'codigoitens.codigo_id')
            ->where('codigos.descricao','=','Tipo Movimenta├ž├úo')
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
            ->where('codigos.descricao','=','Tipo Movimenta├ž├úo')
            ->where('codigoitens.descricao','=','Libera├ž├úo')
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
