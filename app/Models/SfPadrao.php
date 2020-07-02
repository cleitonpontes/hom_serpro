<?php

namespace App\Models;

use App\SfDocContabilizacao;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class SfPadrao extends Model
{

    use CrudTrait;
    use LogsActivity;
    /**
     * Informa que não utilizará os campos create_at e update_at do Laravel
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'sfpadrao';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'fk',
        'categoriapadrao',
        'decricaopadrao',
        'codugemit',
        'anodh',
        'codtipodh',
        'numdh',
        'dtemis',
        'txtmotivo',
        'msgretorno',
        'tipo',
        'situacao'
    ];

    public function dadosBasicos()
    {
        return $this->hasOne(SfDadosBasicos::class, 'sfpadrao_id');
    }

    public function pco()
    {
        return $this->hasMany(SfPco::class, 'sfpadrao_id');
    }

    public function pso()
    {
        return $this->hasMany(SfPso::class, 'sfpadrao_id');
    }

    public function credito()
    {
        return $this->hasMany(SfCredito::class, 'sfpadrao_id');
    }

    public function outrosLanc()
    {
        return $this->hasMany(SfOutrosLanc::class, 'sfpadrao_id');
    }

    public function deducao()
    {
        return $this->hasMany(SfDeducao::class, 'sfpadrao_id');
    }

    public function encargo()
    {
        return $this->hasMany(SfDeducao::class, 'sfpadrao_id');
    }

    public function despesaAnular()
    {
        return $this->hasMany(SfDespesaAnular::class, 'sfpadrao_id');
    }

    public function compensacao()
    {
        return $this->hasMany(SfDespesaAnular::class, 'sfpadrao_id');
    }

    public function centroCusto()
    {
        return $this->hasMany(SfCentroCusto::class, 'sfpadrao_id');
    }

    public function dadospgto()
    {
        return $this->hasMany(SfDadosPgto::class, 'sfpadrao_id');
    }

    public function docContabilizacao()
    {
        return $this->hasMany(SfDocContabilizacao::class, 'sfpadrao_id');
    }
}
