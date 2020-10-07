<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmparoLegalContrato extends Model
{


    protected $table = 'amparo_legal_contrato';
    protected $primaryKey = ['contrato_id', 'amparo_legal_id'];

    protected $fillable = [
        'contrato_id',
        'amparo_legal_id'
    ];

    public function contratos()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    public function amparosLegais()
    {
        return $this->belongsTo(AmparoLegal::class, 'amparo_legal_id');
    }

}
