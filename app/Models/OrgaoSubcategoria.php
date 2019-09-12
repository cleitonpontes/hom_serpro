<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class OrgaoSubcategoria extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'orgaosubcategorias';
//    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'orgaosubcategorias';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'orgao_id',
        'categoria_id',
        'descricao',
        'situacao'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getOrgao()
    {
        $orgao = Orgao::find($this->orgao_id);

        return $orgao->codigo . ' - ' . $orgao->nome;
    }
    public function getCategoria()
    {
        $codigoitem = Codigoitem::find($this->categoria_id);

        return $codigoitem->descricao;
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function orgao()
    {
        $this->belongsTo(Orgao::class, 'orgao_id');
    }

    public function categoria()
    {
        $this->belongsTo(Codigoitem::class, 'categoria_id');
    }

    public function contratos()
    {
        $this->hasMany(Contrato::class, 'subcategoria_id');
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
