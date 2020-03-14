<?php

namespace App\Models;

use App\Repositories\Comunica as Repo;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role;

class Comunica extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    /**
     * @var string
     */
    const COMUNICA_SITUACAO_INACABADO = 'I';

    /**
     * @var string
     */
    const COMUNICA_SITUACAO_PRONTO_PARA_ENVIO = 'P';

    /**
     * @var string
     */
    const COMUNICA_SITUACAO_ENVIADO = 'E';

    /**
     * @var string
     */
    const COMUNICA_SITUACAO_INACABADO_DESC = 'Inacabado';

    /**
     * @var string
     */
    const COMUNICA_SITUACAO_PRONTO_PARA_ENVIO_DESC = 'Pronto para Envio';

    /**
     * @var string
     */
    const COMUNICA_SITUACAO_ENVIADO_DESC = 'Enviado';

    /**
     * @var string
     */
    protected $table = 'comunica';

    /**
     * @var bool
     */
    protected static $logFillable = true;

    /**
     * @var string
     */
    protected static $logName = 'comunica';

    /**
     * @var array
     */
    protected $fillable = [
        'role_id',
        'orgao_id',
        'unidade_id',
        'assunto',
        'mensagem',
        'anexos',
        'situacao'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'anexos' => 'array'
    ];

    // protected $primaryKey = 'id';
    // protected $guarded = ['id'];
    // protected $hidden = [];
    // protected $dates = [];
    // public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Retorna descrição do órgão para uso no combo
     *
     * @return string
     * @deprecated Usar o $repo->getOrgao($id)
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getOrgao()
    {
        $repo = new Repo;
        return $repo->getOrgao($this->orgao_id);
    }

    /**
     * Retorna unidades para exibição e preenchimento de Combo
     *
     * @return string
     * @deprecated Usar o $repo->getUnidade($id)
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getUnidade()
    {
        $repo = new Repo;
        return $repo->getUnidade($this->unidade_id);
    }

    /**
     * Retorna descrição do órgão para uso no combo
     *
     * @return string
     * @deprecated Usar o $repo->getGrupo($id)
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getGrupo()
    {
        $repo = new Repo;
        return $repo->getGrupo($this->role_id);
    }

    /**
     * Retorna apenas texto puro do campo mensagem
     *
     * @return string
     * @deprecated Usar o $repo->getTextoPuroDeCampoMensagem($msg)
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getMensagem()
    {
        $repo = new Repo;
        return $repo->getTextoPuroDeCampoMensagem($this->mensagem);
    }

    /**
     * Retorna a situação existente em $this->situacao
     *
     * @return string
     * @deprecated Usar o $repo->getSituacaoComunica($id)
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getSituacao()
    {
        $repo = new Repo;
        return $repo->getSituacaoComunica($this->situacao);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Define relacionamento com tabela Orgaos
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function orgao()
    {
        return $this->belongsTo(Orgao::class, 'orgao_id');
    }

    /**
     * Define relacionamento com tabela Unidades
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
    }

    /**
     * Define relacionamento com tabela Roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function grupo()
    {
        return $this->belongsTo(Role::class, 'role_id');
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

    /**
     * Salva o anexo
     *
     * @param $value
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function setAnexosAttribute($value)
    {
        $attribute_name = "anexos";
        $disk = "local";
        $destination_path = "comunica/anexos";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

}
