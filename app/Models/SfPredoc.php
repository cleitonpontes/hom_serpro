<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfPredoc extends Model
{
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
    protected $table = 'sfpredoc';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfded_id',
        'sfencargo_id',
        'sfdadospgto_id',
        'txtobser',
        'codrecurso',
        'prdoapuracao',
        'numref',
        'txtprocesso',
        'vlrrctabrutaacum',
        'vlrpercentual',
        'mesreferencia',
        'anoreferencia',
        'codugtmdrserv',
        'numnf',
        'txtserienf',
        'numsubserienf',
        'codmuninf',
        'emisnf',
        'vlrnf',
        'numaliqnf',
        'numcodbarras',
        'codugfavorecida',
        'codrecolhedor',
        'numreferencia',
        'mescompet',
        'anocompet',
        'vlrdocumento',
        'vlrdesconto',
        'vlroutrdeduc',
        'codrecolhimento',
        'indradiant13',
        'codtipoob',
        'codcredordevedor',
        'codnumlista',
        'txtcit',
        'tipo'
    ];

    public function sfdeducao()
    {
        return $this->belongsTo(SfDeducao::class, 'sfdeducao_id');
    }
    public function sfencargos()
    {
        return $this->belongsTo(SfEncargos::class, 'sfencargo_id');
    }

    public function sfdadospgto()
    {
        return $this->belongsTo(SfDadosPgto::class, 'sfdadospgto_id');
    }

    public function sfddomiciliobancario()
    {
        return $this->hasOne(SfDomicilioBancario::class, 'sfpredoc_id');
    }

}
