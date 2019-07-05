<?php

namespace App\Models;

use Html2Text\Html2Text;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role;

class Comunica extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'comunica';
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'comunica';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'unidade_id',
        'role_id',
        'assunto',
        'mensagem',
        'anexos',
        'situacao'
    ];

    protected $casts = [
        'anexos' => 'array'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getUnidade()
    {
        if ($this->unidade_id) {
            $unidade = Unidade::find($this->unidade_id);
            return $unidade->codigo . ' - ' . $unidade->nomeresumido;
        } else {
            return 'Todas';
        }
    }

    public function getGrupo()
    {
        if ($this->role_id) {
            $grupo = Role::find($this->role_id);
            return $grupo->name;
        } else {
            return 'Todos';
        }
    }

    public function getMensagem()
    {
        if ($this->mensagem) {
            $mensagem = new Html2Text($this->mensagem);
            return $mensagem->getText();
        } else {
            return '';
        }
    }


    public function getSituacao()
    {
        if(trim($this->situacao) == 'P'){
            return 'Pronto para Envio';
        }

        if(trim($this->situacao) == 'E'){
            return 'Enviado';
        }

        if(trim($this->situacao) == 'I'){
            return 'Inacabado';
        }

    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
    }

    public function grupo()
    {
        return $this->belongsTo(Role::class, 'role_id');
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
    public function setAnexosAttribute($value)
    {
        $attribute_name = "anexos";
        $disk = "local";
        $destination_path = "comunica/anexos";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }
}
