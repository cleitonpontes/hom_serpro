<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class SfDespesaAnular extends Model
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
    protected $table = 'sfdespesaanular';

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
        'txtinscrd',
        'numclassd',
        'txttnscre',
        'numclasse'
    ];

    /**
     * Retorna dados para o relatório de apropriação - Despesas a anular
     * 
     * @param number $apid
     * @return arrary
     */
    public function retornaDadosRelatorioApropriacao($apid)
    {
        $listagem = $this->retornaDadosBasicosPorApropriacao($apid);

        $listagem->select([
            'D.numseqitem    as seq',
            'I.numseqitem    as seq_item',
            'D.codsit        as situacao',
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
        $dados = $this->from('sfdespesaanular as D');

        $dados->leftjoin('sfdespesaanularitem as I', 'I.sfdespesaanular_id', '=', 'D.id');
        $dados->leftjoin('sfpadrao as A', 'A.id', '=', 'D.sfpadrao_id');
        $dados->leftjoin('execsfsituacao as E', 'E.codigo', '=', 'D.codsit');

        $dados->where('A.fk', $apid);

        $dados->orderBy('D.numseqitem');
        $dados->orderBy('I.numseqitem');

        return $dados;
    }
}
