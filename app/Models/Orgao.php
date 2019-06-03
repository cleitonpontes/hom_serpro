<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Orgao extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'orgao';
    use SoftDeletes;

    protected $table = 'orgaos';

    protected $fillable = [
        'codigo',
        'orgaosuperior_id',
        'nome',
        'codigosiasg',
        'situacao'
    ];

    public function getOrgaoSuperior()
    {
        $orgaosuperior = OrgaoSuperior::find($this->orgaosuperior_id);
        return $orgaosuperior->codigo . " - " . $orgaosuperior->nome;

    }

    public function orgaosuperior()
    {

        return $this->belongsTo(OrgaoSuperior::class, 'orgaosuperior_id');

    }

    public function unidades()
    {

        return $this->hasMany(Unidade::class, 'orgao_id');

    }


}
