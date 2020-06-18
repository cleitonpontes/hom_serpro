<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Naturezadespesa extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'naturezadespesa';
    use SoftDeletes;

    protected $fillable = [
        'codigo',
        'descricao'
    ];

    protected $table = 'naturezadespesa';

    public function buscaNaturezadespesa(array $dado)
    {
        $nd = $this->where('codigo',$dado['codigo_nd'])
            ->first();
        if(!isset($nd->id)){
            $nd = new Naturezadespesa();
            $nd->codigo = $dado['codigo_nd'];
            $nd->descricao = $dado['descricao_nd'];
            $nd->situacao = true;
            $nd->save();
        }else{
            $nd->descricao = $dado['descricao_nd'];
            $nd->save();
        }
        return $nd;
    }

    public function naturezasubitem()
    {
        return $this->hasMany(Naturezasubitem::class, 'naturezasubitem_id');
    }

    public function empenhos()
    {
        return $this->hasMany(Empenho::class,'naturezadespesa_id');
    }


}
