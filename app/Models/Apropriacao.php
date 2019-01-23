<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apropriacao extends Model
{

    /**
     * Informa que não utilizará os campos create_at e update_at do Laravel
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Identifica o campo chave primária da tabela
     *
     * @var string
     */
    public $primaryKey = 'id';

    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'apropriacoes';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'competencia',
        'ug',
        'nivel',
        'valor_liquido',
        'valor_bruto',
        'fase_id',
        'arquivos',
        'ateste',
        'nup',
        'doc_origem',
        'observacoes'
    ];

    /**
     * Relacionamento com a tabela Apropriacoesfases
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function apropriacaoFases()
    {
        return $this->hasMany('App\Models\Apropriacoesfases', 'id');
    }

    /**
     * Retorna dados da apropriação para apresentação
     *
     * @return array
     */
    public function retornaListagem()
    {
        $ug = session('user_ug');
        $listagem = Apropriacao::join('apropriacoes_fases AS F', 'F.id', '=', 'apropriacoes.fase_id');

        $listagem->select([
            'apropriacoes.id',
            'competencia',
            'nivel',
            'valor_liquido',
            'valor_bruto',
            'fase_id',
            'F.fase',
            'arquivos'
        ])->where('ug', $ug);

        return $listagem->get();
    }

    /**
     * Retorna dados da apropriação para verificar se pode avançar para o passo 6
     *
     * @return array
     */
    public function validarPasso5($id)
    {
        $query = Apropriacao::query()->where('id', $id)
            ->whereNotNull('observacoes')
            ->whereNotNull('doc_origem')
            ->whereNotNull('nup')
            ->whereNotNull('ateste');

        return $query->exists();
    }
    
    
    
    
    
    
    
    
    
    

    /**
     * Retorna dados da apropriação para apresentação
     *
     * @return array
     */
    public function getRelatorioId($id)
    {
        $ug = session('user_ug');
        $listagem = Apropriacao::join('apropriacoes_fases AS F', 'F.id', '=', 'apropriacoes.fase_id');

        $listagem->select([
            'apropriacoes.id',
            'competencia',
            'nivel',
            'valor_liquido',
            'valor_bruto',
            'fase_id',
            'F.fase',
            'arquivos'
        ])->where('ug', $ug)
        ->where('apropriacoes.id', $id);

        return $listagem->get();
    }
}
