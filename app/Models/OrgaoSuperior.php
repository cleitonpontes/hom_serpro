<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class OrgaoSuperior extends Model
{
    use CrudTrait;

    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'orgao_superior';

    protected $table = 'orgaossuperiores';

    protected $fillable = [
        'codigo', 'nome', 'situacao'
    ];


    public function orgaos(){

        return $this->hasMany(Orgao::class, 'orgaosuperior_id');

    }
}
