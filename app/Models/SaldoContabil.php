<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Eduardokum\LaravelMailAutoEmbed\Models\EmbeddableEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class SaldoContabil extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected static $logFillable = true;
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
        'saldo'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function retornaSaldos(){
        return SaldoContabil::select([
                'saldo_contabil.id',
                DB::raw("SUBSTRING(saldo_contabil.conta_corrente,1,1) AS esfera"),
                DB::raw("SUBSTRING(saldo_contabil.conta_corrente,2,6) AS ptrs"),
                DB::raw("SUBSTRING(saldo_contabil.conta_corrente,8,10) AS fonte"),
                DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6) AS nd"),
                DB::raw("SUBSTRING(saldo_contabil.conta_corrente,24,8) AS ugr"),
                DB::raw("SUBSTRING(saldo_contabil.conta_corrente,32,11) AS plano_interno"),
                'saldo_contabil.saldo',
            ])
            ->where(DB::raw("SUBSTRING(saldo_contabil.conta_corrente,22,2)"),'<>','00')
            ->orderby('saldo_contabil.saldo','DESC')
            ->get()
            ->toArray();
    }

    public static function gravaSaldoContabil($ano,$unidade_id,$contacorrente,$contacontabil,$saldo)
    {
        $saldoContabil = SaldoContabil::updateOrCreate(
            ['ano'=> $ano,'unidade_id' => $unidade_id,'conta_corrente' => $contacorrente,'conta_contabil' => $contacontabil],
            ['saldo' => $saldo]
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
