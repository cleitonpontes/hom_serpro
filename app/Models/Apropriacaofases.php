<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apropriacaofases extends Model
{

    const APROP_FASE_NAO_INICIADA = 0;

    const APROP_FASE_IMPORTAR_DDP = 1;

    const APROP_FASE_IDENTIFICAR_SITUACAO = 2;

    const APROP_FASE_IDENTIFICAR_EMPENHO = 3;

    const APROP_FASE_VALIDAR_SALDO = 4;

    const APROP_FASE_INFORMAR_DADOS_COMPL = 5;

    const APROP_FASE_PERSISTIR_DADOS = 6;

    const APROP_FASE_GERAR_XML = 7;

    const APROP_FASE_FINALIZADA = 8;

    protected $table = 'apropriacoes_fases';

    protected $fillable = ['id', 'fase'];

    public $timestamps = false;

    public function descricao()
    {
        return $this->belongsTo('App\Models\Apropriacao', 'fases_id');
    }
}
