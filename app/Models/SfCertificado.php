<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfCertificado extends Model
{
    protected $table = 'sfcertificado';

    protected $fillable = [
        'certificado', 'chaveprivada', 'vencimento', 'situacao'
    ];


}
