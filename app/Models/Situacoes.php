<?php
namespace App\Models;

// use DB;
use Illuminate\Database\Eloquent\Model;

class Situacoes extends Model
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
    public $primaryKey = null;

    /**
     * Nome da view
     *
     * @var string
     */
    protected $table = 'situacoes';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'natureza',
        'nivel',
        'categoria',
        'rubrica',
        'situacao',
        'vpd'
    ];

    /**
     * Retorna dados para composição da listagem para identificação de situações
     * 
     * @return array
     */
    public function retornaListagemComoArray()
    {
        $listagem = $this->select([
            'natureza',
            'nivel',
            'categoria',
            'rubrica',
            'situacao',
            'vpd'
        ]);

        return $listagem->get()->toArray();
    }
}
