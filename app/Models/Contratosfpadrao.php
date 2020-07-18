<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratosfpadrao extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sfpadrao';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    //protected $guarded = ['id'];
    protected $fillable = [
        'id',
        'fk',
        'categoriapadrao',
        'decricaopadrao',
        'codugemit',
        'anodh',
        'codtipodh',
        'numdh',
        'dtemis',
        'txtmotivo',
        'msgretorno',
        'tipo',
        'situacao',
        'user_id'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getNumeroContrato()
    {
        $contrato = Contrato::find($this->fk);
        return $contrato->numero;

    }

    public function getSituacao()
    {
        $retorno = '';

        switch ($this->situacao) {
            case 'P':
                $retorno = 'Pendente';
                break;
            case 'I':
                $retorno = 'Importado';
                break;
            case 'E':
                $retorno = 'Erro na importação';
                break;
        }

        return $retorno;
    }

    public function atualizaMensagemSituacao(int $id,array $params)
    {

        $contratosfpadrao = $this->find($id);

        if($contratosfpadrao->dadosBasicos()->exists()){
            $this->dtemis = $params['dtemis'];
            $this->situacao = $params['situacao'];
            $this->msgretorno = $params['msgretorno'];
        }else{
            $this->dtemis = $params['dtemis'];
            $this->situacao = $params['situacao'];
            $this->msgretorno = $params['msgretorno'];
        }

        DB::beginTransaction();
        try {
            $this->save();
            DB::commit();

        } catch (\Exception $exc) {
            DB::rollback();
        }

    }

    public function retornaCpfBackpackUser()
    {

    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */



    public function dadosBasicos()
    {
        return $this->hasOne(SfDadosBasicos::class, 'sfpadrao_id');
    }

    public function pco()
    {
        return $this->hasMany(SfPco::class, 'sfpadrao_id');
    }

    public function pso()
    {
        return $this->hasMany(SfPso::class, 'sfpadrao_id');
    }

    public function credito()
    {
        return $this->hasMany(SfCredito::class, 'sfpadrao_id');
    }

    public function outrosLanc()
    {
        return $this->hasMany(SfOutrosLanc::class, 'sfpadrao_id');
    }

    public function deducao()
    {
        return $this->hasMany(SfDeducao::class, 'sfpadrao_id');
    }

    public function encargo()
    {
        return $this->hasMany(SfDeducao::class, 'sfpadrao_id');
    }

    public function despesaAnular()
    {
        return $this->hasMany(SfDespesaAnular::class, 'sfpadrao_id');
    }

    public function compensacao()
    {
        return $this->hasMany(SfDespesaAnular::class, 'sfpadrao_id');
    }

    public function centroCusto()
    {
        return $this->hasMany(SfCentroCusto::class, 'sfpadrao_id');
    }

    public function dadospgto()
    {
        return $this->hasMany(SfDadosPgto::class, 'sfpadrao_id');
    }

    public function docContabilizacao()
    {
        return $this->hasMany(SfDocContabilizacao::class, 'sfpadrao_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
