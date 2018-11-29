<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Unidade extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'unidades';


    protected $table = 'unidades';

    protected $fillable = [
        'orgao_id', 'codigo', 'codigosiasg', 'nome', 'nomeresumido', 'telefone', 'tipo', 'situacao'
    ];

    public function orgao(){

        return $this->belongsTo(Orgao::class, 'orgao_id');

    }

    public function users(){

        return $this->belongsToMany(BackpackUser::class, 'unidadeuser', 'unidade_id', 'user_id');

    }

}
