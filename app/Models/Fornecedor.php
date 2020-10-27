<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Fornecedor extends Model
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
    protected static $logName = 'fornecedor';

    protected $table = 'fornecedores';

    // protected $guarded = ['id'];

    protected $fillable = [
        'tipo_fornecedor',
        'cpf_cnpj_idgener',
        'nome',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getTipo()
    {
        switch ($this->tipo_fornecedor) {
            case 'FISICA':
                return 'Pessoa Física';
                break;
            case 'JURIDICA':
                return 'Pessoa Jurídica';
                break;
            case 'UG':
                return 'UG Siafi';
                break;
            case 'IDGENERICO':
                return 'ID Genérico';
                break;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }

    public function empenhos()
    {
        return $this->hasMany(Empenhos::class);
    }

    public function minuta_empenhos_compra()
    {
        return $this->hasMany(MinutaEmpenho::class, 'fornecedor_compra_id');
    }

    public function minuta_empenhos()
    {
        return $this->hasMany(MinutaEmpenho::class, 'fornecedor_empenho_id');
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
