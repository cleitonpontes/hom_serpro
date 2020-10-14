<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class AmparoLegal extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;

    protected static $logFillable = true;
    protected static $logName = 'amparo_legal';

    protected $table = 'amparo_legal';

    protected $fillable = [
        'modalidade_id',
        'ato_normativo',
        'artigo',
        'paragrafo',
        'inciso',
        'alinea'
    ];

    public function minuta_empenhos()
    {
        return $this->hasMany(MinutaEmpenho::class);
    }

    public function modalidade()
    {
        return $this->belongsTo(Codigoitem::class, 'modalidade_id');
    }
}
