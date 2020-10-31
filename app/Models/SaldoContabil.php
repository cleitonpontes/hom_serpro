<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Eduardokum\LaravelMailAutoEmbed\Models\EmbeddableEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Http\Traits\Formatador;

class SaldoContabil extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;
    use Formatador;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected static $logFillable = true;
    public $timestamps = true;
    protected static $logName = 'saldo_contabil';

    protected $table = 'saldo_contabil';

    protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'unidade_id',
        'ano',
        'conta_contabil',
        'conta_corrente',
        'saldo',
        'timestamps'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function retornaSaldos($unidade_id){
        return  SaldoContabil::join('unidades', 'unidades.id', '=', 'saldo_contabil.unidade_id')
            ->select([
                'saldo_contabil.id',
                DB::raw("SUBSTRING(saldo_contabil.conta_corrente,1,1) AS esfera"),
                DB::raw("SUBSTRING(saldo_contabil.conta_corrente,2,6) AS ptrs"),
                DB::raw("SUBSTRING(saldo_contabil.conta_corrente,8,10) AS fonte"),
                DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6) AS nd"),
                DB::raw("SUBSTRING(saldo_contabil.conta_corrente,24,8) AS ugr"),
                DB::raw("SUBSTRING(saldo_contabil.conta_corrente,32,11) AS plano_interno"),
                DB::raw("TO_CHAR(saldo_contabil.saldo,'999G999G000D99') AS saldo")

            ])
            ->where(DB::raw("SUBSTRING(saldo_contabil.conta_corrente,22,2)"),'<>','00')
            ->where('saldo_contabil.unidade_id',$unidade_id)
            ->where('saldo_contabil.saldo','>',0)
            ->orderby('saldo','DESC')
            ->get()
            ->toArray();
//        dd($teste->getBindings(),$teste->toSql());
    }

    public function verificaDataAtualizacaoSaldoContabil($saldoSta)
    {
        $atualizar = false;
        $saldoLocal = SaldoContabil::where('conta_corrente',$saldoSta->contacorrente)->first();

        if(!is_null($saldoLocal)) {
            if ($saldoLocal->count() > 0) {
                if (strtotime($saldoLocal->updated_at) < strtotime($saldoSta->updated_at)) {
                    $atualizar = true;
                }
            }
        }
        return $atualizar;
    }

    public function gravaSaldoContabil($ano,$unidade_id,$contacorrente,$contacontabil,$saldo)
    {
        $saldoContabil = SaldoContabil::updateOrCreate(
            ['ano'=> $ano,'unidade_id' => $unidade_id,'conta_corrente' => $contacorrente,'conta_contabil' => $contacontabil],
            ['saldo' => $saldo]
        );
    }

    public function AtualizaSaldoContabil($ano,$unidade_id,$contacorrente,$contacontabil,$saldo)
    {
        $saldoContabil = SaldoContabil::updateOrCreate(
            ['ano'=> $ano,'unidade_id' => $unidade_id,'conta_corrente' => $contacorrente,'conta_contabil' => $contacontabil],
            ['saldo' => $saldo,'updated_at' => date("Y-m-d H:i:s")]
        );
    }



    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function unidade_id()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
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
}
