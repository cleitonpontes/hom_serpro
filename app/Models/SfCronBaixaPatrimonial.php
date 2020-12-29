<?php

namespace App\Models;

use App\SfParcela;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SfCronBaixaPatrimonial extends Model
{
    use LogsActivity;

    /**
     * Informa que não utilizará os campos create_at e update_at do Laravel
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'sfcronbaixapatrimonial';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfpco_id',
        'sfoutroslanc_id',
        'parcela'
    ];

    public function pco()
    {
        return $this->belongsTo(SfPco::class, 'sfpco_id');
    }

    public function outrosLanc()
    {
        return $this->belongsTo(SfOutrosLanc::class, 'sfoutroslanc_id');
    }

    public function parcela()
    {
        return $this->hasMany(SfParcela::class, 'sfpco_id');
    }


}
