<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SfPco extends Model
{
    use LogsActivity;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

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
    protected $table = 'sfpco';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfpadrao_id',
        'numseqitem',
        'codsit',
        'codugempe',
        'indrtemcontrato',
        'txtinscrd',
        'numclassd',
        'txttnscre',
        'numclasse'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Valida existência ou não de dados referentes ao Passo 6
     *
     * @param number $apid
     * @return boolean
     */
    public function validaPasso6($apid)
    {
        $dados = $this->retornaDadosBasicosPorApropriacao($apid);

        return $dados->exists();
    }

    /**
     * Retorna dados para o relatório de apropriação - PCO
     *
     * @param number $apid
     * @return array
     */
    public function retornaDadosRelatorioApropriacao($apid)
    {
        $listagem = $this->retornaDadosBasicosPorApropriacao($apid);

        $listagem->select([
            'P.numseqitem    as seq',
            'I.numseqitem    as seq_item',
            'P.codsit        as situacao',
            'E.descricao     as descricao',
            'I.numempe       as empenho',
            'I.numclassa     as vpd',
            DB::raw('lpad("I"."codsubitemempe"::text, 2, \'0\') as subitem'),
            DB::raw('\'000\' as fonte'),
            'I.vlr           as valor'
        ]);

        $dados = $listagem->get()->toArray();

        return $dados;
    }

    /**
     * Retorna estrutura de dados para consultas posteriores
     *
     * @param number $apid
     * @return array
     */
    private function retornaDadosBasicosPorApropriacao($apid)
    {
        $dados = $this->from('sfpco as P');

        $dados->leftjoin('sfpcoitem as I', 'I.sfpco_id', '=', 'P.id');
        $dados->leftjoin('sfpadrao as A', 'A.id', '=', 'P.sfpadrao_id');
        $dados->leftjoin('execsfsituacao as E', 'E.codigo', '=', 'P.codsit');

        $dados->where('A.fk', $apid);

        $dados->orderBy('P.numseqitem');
        $dados->orderBy('I.numseqitem');

        return $dados;
    }

    public function retornaPcosProApropriacaoDaFatura($apid)
    {
        return $this->from('sfpco as P')
            ->leftjoin('sfpcoitem as I', 'I.sfpco_id', '=', 'P.id')
            ->leftjoin('sfpadrao as A', 'A.id', '=', 'P.sfpadrao_id')
            ->leftjoin('execsfsituacao as E', 'E.codigo', '=', 'P.codsit')
            ->where('categoriapadrao', 'EXECFATURA')
            ->where('fk', $apid)
            ->select(
                'P.numseqitem    AS seq',
                'I.numseqitem    AS seq_item',
                'P.codsit        AS situacao',
                'E.descricao     AS descricao',
                'I.numempe       AS empenho',
                'I.numclassa     AS vpd',
                DB::raw('lpad("I"."codsubitemempe"::text, 2, \'0\') AS subitem'),
                DB::raw('\'000\' AS fonte'),
                'I.vlr           AS valor'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function sfpadrao()
    {
        return $this->belongsTo(Contratosfpadrao::class, 'sfpadrao_id');
    }

    public function pcoItens()
    {
        return $this->hasMany(SfPcoItem::class, 'sfpco_id');
    }

    public function cronBaixaPatrimonial()
    {
        return $this->hasMany(SfCronBaixaPatrimonial::class, 'sfpco_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
