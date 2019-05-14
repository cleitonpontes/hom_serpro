<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Unidade extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'unidade';

    use SoftDeletes;
    protected $table = 'unidades';

    protected $fillable = [
        'orgao_id',
        'codigo',
        'gestao',
        'codigosiasg',
        'nome',
        'nomeresumido',
        'telefone',
        'tipo',
        'situacao'
    ];

    public function orgao()
    {

        return $this->belongsTo(Orgao::class, 'orgao_id');

    }

    public function users()
    {
        return $this->belongsToMany(BackpackUser::class, 'unidadesusers', 'unidade_id', 'user_id');
    }

    public function contratos(){

        return $this->hasMany(Contrato::class, 'unidade_id');

    }
    public function getCodigoNome()
    {
        return $this->codigo . ' - ' . $this->nomeresumido;
    }

    public function moreOptions($crud = false)
    {
        $button = '<div class="btn-group">
                        <button type="button" title="Mais" class="btn btn-xs btn-default dropdown-toggle dropdown-toggle-split"
                            data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-gear"></i>'.$this->id.'
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                            <ul class="dropdown-menu" >
                                <li><a href="#">Teste 1</a></li>
                                <li><a href="#">Teste 2</a></li>
                            </ul>
                    </div>';

        return $button;

    }

}
