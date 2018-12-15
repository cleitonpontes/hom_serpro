<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instalacao extends Model
{
    protected $table = 'instalacoes';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'nome',
    ];
}
