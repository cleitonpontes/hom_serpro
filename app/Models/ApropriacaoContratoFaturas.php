<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class ApropriacaoContratoFaturas extends Model
{
    use LogsActivity;

    protected $table = 'apropriacoes_faturas_contratofaturas';
    protected $primaryKey = ['apropriacoes_faturas_id', 'contratofaturas_id'];
    protected $fillable = [
        'apropriacoes_faturas_id',
        'contratofaturas_id'
    ];

    public $incrementing = false;
    public $timestamps = false;

    /**
     * Retorna se fatura está presente ou não em alguma apropriação que não cancelada
     *
     * @param array $id
     * @return bool
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    public static function existeFatura($id)
    {
        return self::from(
            'apropriacoes_faturas_contratofaturas as F'
        )
            ->leftJoin('apropriacoes_faturas AS A', 'A.id', '=', 'F.apropriacoes_faturas_id')
            ->whereNotIn('A.fase_id', [99])
            ->whereIn('F.contratofaturas_id', $id)
            // ->get()->toArray();
            ->exists();
    }

    public function apropriacao()
    {
        return $this->belongsTo('App\Models\ApropriacaoFaturas', 'apropriacoes_faturas_id');
    }

    public function fatura()
    {
        return $this->belongsTo('App\Models\Contratofatura', 'contratofaturas_id');
    }
}
