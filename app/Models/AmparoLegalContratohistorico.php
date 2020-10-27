<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmparoLegalContratohistorico extends Model
{


    protected $table = 'amparo_legal_contratohistorico';
    protected $primaryKey = ['contratohistorico_id', 'amparo_legal_id'];

    protected $fillable = [
        'contratohistorico_id',
        'amparo_legal_id'
    ];

    public function contratohistorico()
    {
        return $this->belongsTo(Contratohistorico::class, 'contratohistorico_id');
    }

    public function amparosLegais()
    {
        return $this->belongsTo(AmparoLegal::class, 'amparo_legal_id');
    }

}
