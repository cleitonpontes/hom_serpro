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

    public function modalidade()
    {
        return $this->belongsTo(Codigoitem::class, 'modalidade_id');
    }

    public function contrato()
    {
        return $this->belongsToMany(
            'App\Models\Contrato',
            'amparo_legal_contrato',
            'contrato_id',
            'amparo_legal_id'
        );
    }

    public function contratohistorico()
    {
        return $this->belongsToMany(
            'App\Models\Contratohistorico',
            'amparo_legal_contrato',
            'contratohistorico_id',
            'amparo_legal_id'
        );
    }
}
