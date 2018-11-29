<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Orgao extends Model
{
    use CrudTrait;

    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'orgao';

    protected $table = 'orgaos';

    protected $fillable = [
        'codigo', 'orgaosuperior_id', 'nome', 'codigosiasg', 'situacao'
    ];

    public function orgaosuperior(){

        return $this->belongsTo(OrgaoSuperior::class, 'orgaosuperior_id');

    }

    public function unidades(){

        return $this->hasMany(Unidade::class, 'orgao_id');

    }

}
