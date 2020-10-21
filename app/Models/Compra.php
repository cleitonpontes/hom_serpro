<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Compra extends Model
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
    protected static $logName = 'compras';

    protected $table = 'compras';
    protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'unidade_origem_id',
        'unidade_subrrogada_id',
        'modalidade_id',
        'tipo_compra_id',
        'numero_ano',
        'inciso',
        'lei'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function gravaCompra($params)
    {

        $this->unidade_origem_id = $params['unidade_origem_id'];
        $this->unidade_subrrogada_id = $params['unidade_subrrogada_id'];
        $this->modalidade_id = $params['modalidade_id'];
        $this->tipo_compra_id = $params['tipo_compra_id'];
        $this->numero_ano = $params['numero_ano'];
        $this->inciso = $params['inciso'];
        $this->lei = $params['lei'];
//        dd($params);
        $this->save($params);

        return $this->id;
    }

    public function retornaForcedoresdaCompra()
    {
        $fornecedores = [];
        foreach ($this->compra_item()->get() as $key => $value) {
            $fornecedores[] = [
                'id' => $value->fornecedor->id,
                'nome' => $value->fornecedor->cpf_cnpj_idgener . ' - ' . $value->fornecedor->nome];
        }
        return $fornecedores;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function compra_item()
    {
        return $this->hasMany(CompraItem::class);
    }

    public function minuta_empenhos()
    {
        return $this->hasMany(MinutaEmpenho::class);
    }

    public function modalidade()
    {
        return $this->belongsTo(Codigoitem::class, 'modalidade_id');
    }

    public function tipo_compra()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_compra_id');
    }

    public function unidade_origem()
    {
        return $this->belongsTo(Unidade::class, 'unidade_origem_id');
    }

    public function unidade_subrrogada()
    {
        return $this->belongsTo(Unidade::class, 'unidade_subrrogada_id');
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
