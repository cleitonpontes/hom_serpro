<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfNonce extends Model
{
    protected $table = 'sfnonce';

    public $timestamps = false;

    protected $fillable = [
        'sf_id', 'tipo'
    ];
}
