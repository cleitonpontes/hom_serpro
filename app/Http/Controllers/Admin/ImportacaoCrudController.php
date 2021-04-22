<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImportacaoRequest as StoreRequest;
use App\Http\Requests\ImportacaoRequest as UpdateRequest;
use App\Models\Contratoterceirizado;
use Illuminate\Http\Request;
use App\Http\Traits\Formatador;
use App\Http\Traits\Users;
use App\Http\Traits\BuscaCodigoItens;
use App\Jobs\InserirUsuarioEmMassaJob;
use App\Jobs\InserirTerceirizadoEmMassaJob;
use App\Models\BackpackUser;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Importacao;
use App\Models\Unidade;
use App\Notifications\PasswordUserNotification;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

/**
 * Class ImportacaoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ImportacaoCrudController extends CrudController
{

    use Users, Formatador, BuscaCodigoItens;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        if (!backpack_user()->hasRole('Administrador') or
            !backpack_user()->hasRole('Administrador Órgão') or
            !backpack_user()->hasRole('Administrador Unidade')) {
            abort('403', config('app.erro_permissao'));
        }

        $this->crud->setModel('App\Models\Importacao');
        $this->crud->setRoute(config('backpack.base.route_prefix') . 'admin/importacao');
        $this->crud->setEntityNameStrings('importacao', 'importações');
        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration Global
        |--------------------------------------------------------------------------
        */
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->enableExportButtons();

        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('importacao_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('importacao_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('importacao_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration Collumns Table
        |--------------------------------------------------------------------------
        */
        $colunas = $this->colunas();
        $this->crud->addColumns($colunas);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration Campos Formulário
        |--------------------------------------------------------------------------
        */
        $tipos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo Importação');
        })
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();

        $situacoes = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Situação Arquivo');
        })
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();

        $unidade = [session()->get('user_ug_id') => session()->get('user_ug')];

        $contratos = Contrato::select(DB::raw("CONCAT(contratos.numero,' | ',fornecedores.cpf_cnpj_idgener,' - ',fornecedores.nome) AS nome"), 'contratos.id')
            ->join('fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id')
            ->where('unidade_id', session()->get('user_ug_id'))
            ->where('situacao', true)
            ->orderBy('contratos.numero', 'asc')->pluck('nome', 'id')->toArray();


        if (backpack_user()->hasRole('Administrador Unidade')) {
            $roles = Role::where('guard_name', 'web')
                ->where('name', '<>', 'Administrador')
                ->where('name', '<>', 'Administrador Órgão')
                ->where('name', '<>', 'Administrador Unidade')
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        }

        if (backpack_user()->hasRole('Administrador Órgão')) {
            $roles = Role::where('guard_name', 'web')
                ->where('name', '<>', 'Administrador')
                ->where('name', '<>', 'Administrador Órgão')
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        }

        if (backpack_user()->hasRole('Administrador')) {
            $roles = Role::where('guard_name', 'web')
                ->where('name', '<>', 'Administrador')
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        }

        $campos = $this->campos($tipos, $unidade, $contratos, $situacoes, $roles);
        $this->crud->addFields($campos);

    }

    public function colunas()
    {
        return [
            [
                'name' => 'nome_arquivo',
                'label' => 'Nome Arquivo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getTipo',
                'label' => 'Tipo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipo', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getContrato',
                'label' => 'Número Contrato', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContrato', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getGrupoUsuarios',
                'label' => 'Grupo Usuário', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getGrupoUsuarios', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getUnidade',
                'label' => 'Unidade Gestora', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidade', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'arquivos',
                'label' => 'Arquivos',
                'type' => 'upload_multiple',
                'disk' => 'local',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'mensagem',
                'label' => 'Mensagem',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getSituacao',
                'label' => 'Situacao', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSituacao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],

        ];
    }

    public function campos($tipos, $unidade, $contratos, $situacoes, $roles)
    {
        return [
            [
                'name' => 'nome_arquivo',
                'label' => 'Nome do Arquivo',
                'type' => 'text',
            ],
            [
                'name' => 'tipo_id',
                'label' => "Tipo",
                'type' => 'select2_from_array',
                'options' => $tipos,
                'allows_null' => true,
                'attributes' => [
                    'id' => 'tipo_de_importacao_ti'
                ]
            ],
            [
                'name' => 'unidade_id',
                'label' => "Unidade Gestora",
                'type' => 'select2_from_array',
                'options' => $unidade,
                'allows_null' => false,
            ],
            [
                'name' => 'contrato_id',
                'label' => "Contrato",
                'type' => 'select2_from_array',
                'options' => $contratos,
                'allows_null' => true,
                'attributes' => [
                    'id' => 'tipo_de_importacao_ti_contrato'
                ]
            ],
            [
                'name' => 'role_id',
                'label' => "Grupo Usuário",
                'type' => 'select2_from_array',
                'options' => $roles,
                'allows_null' => true,
                'attributes' => [
                    'id' => 'tipo_de_importacao_ti_grupo_usuario'
                ]
            ],
            [
                'name' => 'delimitador',
                'label' => 'Delimitador',
                'type' => 'text',
                'limit' => 10
            ],
            [
                'name' => 'arquivos',
                'label' => 'Arquivos',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'public'
            ],
            [
                'name' => 'situacao_id',
                'label' => "Situação",
                'type' => 'select2_from_array',
                'options' => $situacoes,
                'allows_null' => true,
            ],
        ];

    }

    public function store(StoreRequest $request)
    {
        $redirect_location = parent::storeCrud($request);

        $situacao_id = $this->crud->entry->situacao_id;
        $situacao = Codigoitem::find($situacao_id);
        if ($situacao->descricao == 'Pendente de Execução') {
            $this->verificaTipoIniciarExecucao($this->crud->entry);
        }

        return $redirect_location;

    }

    public function update(UpdateRequest $request)
    {

        $redirect_location = parent::updateCrud($request);

        $situacao_id = $this->crud->entry->situacao_id;
        $situacao = Codigoitem::find($situacao_id);
        if ($situacao->descricao == 'Pendente de Execução') {
            $this->verificaTipoIniciarExecucao($this->crud->entry);
        }

        return $redirect_location;
    }

    private function verificaTipoIniciarExecucao($dados_importacao)
    {
        $tipo = Codigoitem::find($dados_importacao->tipo_id);

        foreach ($dados_importacao->arquivos as $arquivo) {
            if ($tipo->descricao == 'Usuários') {
                $this->lerArquivoImportacao($arquivo, $tipo->descricao, $dados_importacao);
            }

            if ($tipo->descricao == 'Terceirizado') {
                $this->lerArquivoImportacao($arquivo, $tipo->descricao, $dados_importacao);
            }
        }

        $nova_situacao = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Situação Arquivo');
        })
            ->where('descricao', 'Executado')->first();

        $dados_importacao->situacao_id = $nova_situacao->id;
        $dados_importacao->save();

    }


    private function lerArquivoImportacao($nome_arquivo, $tipo, $dados_importacao)
    {
        // alteração 3/3 -> adicionado o ../ no link abaixo
        $path = env('APP_PATH') . "storage/app/";

        $arquivo = fopen($path . $nome_arquivo, 'r');

        while (!feof($arquivo)) {
            $linha = fgets($arquivo, 1024);
            if ($tipo == 'Usuários') {
                $this->criaJobsInsercaoUsuarioEmMassa(utf8_encode($linha), $dados_importacao);
            }

            if ($tipo == 'Terceirizado') {
                $this->criaJobsInsercaoTerceirizadoEmMassa(utf8_encode($linha), $dados_importacao);
            }
        }
        fclose($arquivo);
    }

    private function criaJobsInsercaoUsuarioEmMassa($linha, $dados_importacao)
    {
        $array_dado = explode($dados_importacao->delimitador, $linha);
        $pkcount = is_array($array_dado) ? count($array_dado) : 0;
        if ($pkcount > 0) {
            InserirUsuarioEmMassaJob::dispatch($array_dado, $dados_importacao);
        }
    }

    private function criaJobsInsercaoTerceirizadoEmMassa($linha, $dados_importacao)
    {
        $array_dado = explode($dados_importacao->delimitador, $linha);
        InserirTerceirizadoEmMassaJob::dispatch($array_dado, $dados_importacao->contrato_id);
    }

    /**
     * @param $array_dado
     * @param $numero_instrumento
     * @return array|false
     */
    private function retornaParamsImportacao($array_dado, $contrato_id)
    {
        //valida se quantidade de campos por linha é valido
        if(count($array_dado) !== 14){
            return false;
        }

        $arrImportacaoParams = [
            'contrato_id' => $contrato_id,
            'cpf' => $array_dado[0],
            'nome' => strtoupper(trim($array_dado[1])),
            'escolaridade_id' => $this->retonarIdCodigoItemEscolaridade($array_dado[4]),
            'funcao_id' => $this->retornaIdCodigoItemPorDescres($array_dado[5], 'Mão de Obra'),
            'jornada' => $array_dado[7],
            'unidade' => $array_dado[8],
            'salario' => $this->formatToCurrenyValue($array_dado[9]),
            'custo' => $this->formatToCurrenyValue($array_dado[10]),
            'data_inicio' => $array_dado[13],
            'situacao' => true,
            'aux_transporte' => $this->formatToCurrenyValue($array_dado[11]),
        ];
        //valida se campos obrigatórios estão preenchidos
        foreach ($arrImportacaoParams as $itemParam){
            if($itemParam === ''){
                return false;
            }
        }

        //insere campos não obrigatórios
        $arrImportacaoParams['telefone_fixo'] = $array_dado[2];
        $arrImportacaoParams['telefone_celular'] = $array_dado[3];
        $arrImportacaoParams['descricao_complementar'] = strtoupper(trim($array_dado[6]));
        $arrImportacaoParams['vale_alimentacao'] = $this->formatToCurrenyValue($array_dado[12]);

        return $arrImportacaoParams;
    }

    private function retonarIdCodigoItemEscolaridade($num_escolaridade, $descCodigo = 'Escolaridade'){
            return Codigoitem::whereHas('codigo', function ($query) use ($descCodigo) {
                $query->where('descricao', '=', $descCodigo)
                    ->whereNull('deleted_at');
            })
                ->whereNull('deleted_at')
                ->where('descricao', 'ilike', "%$num_escolaridade%")
                ->first()->id;
    }

    private function formatToCurrenyValue($value){
        $field = str_replace(',', '.', str_replace('.','', $value));
        return number_format(floatval($field),2,'.','');
    }

    private function montaArrayDado($linha, $delimitador)
    {
        return explode($delimitador, $linha);
    }

    public function executaInsercaoMassa($dado, Importacao $dados_importacao)
    {
        // alteração 1/3 - verificação se o array está preenchido com os 4 índices
        $countDado = is_array($dado) ? count($dado) : 0;
        if ($countDado < 4) {
            // var_dump($dado);
        } else {
            $cpf = $this->formataCpf($dado[0]);
            $nome = strtoupper(trim($dado[1]));
            $ugprimaria = '';
            $ugsecundaria = [];

            if (strlen($dado[3]) > 6) {
                $ugs = explode(',', trim($dado[3]));
                $i = 0;
                foreach ($ugs as $ug) {
//                    dump(trim($ug));
                    if ($i == 0) {
                        $ugprimaria = $this->buscaUgPorCodigo(trim($ug));
                    } else {
                        $ugsecundaria[] .= $this->buscaUgPorCodigo(trim($ug));
                    }
                    $i++;
                }
            }
            if (strlen($dado[3]) == 6) {
                $ugprimaria = $this->buscaUgPorCodigo(trim($dado[3]));
            }

            if ($dado[2] == '') {
                $email = $dado[0] . "@alteraremail.com";
                $senha = substr($dado[0], 0, 6) . substr(strtolower($dado[1]), 0, 2);
            } else {
                $email = $dado[2];
                $senha = $this->geraSenhaAleatoria();
            }

            $user = $this->buscaUsuario($cpf, $email);

            if (!$user) {
                if ($ugprimaria != '' or $ugprimaria != null) {
                    $user = BackpackUser::firstOrCreate(
                        [
                            'cpf' => $cpf,
                            'email' => $email,
                        ],
                        [
                            'name' => $nome,
                            'email' => $email,
                            'ugprimaria' => $ugprimaria,
                            'password' => bcrypt($senha),
                            'situacao' => true
                        ]
                    );
                }

                if ($user) {
                    $role = Role::find($dados_importacao->role_id);
                    $user->assignRole($role->name);
                    if (count($ugsecundaria)) {
                        $user->unidades()->attach($ugsecundaria);
                    }
                    if ($email != $dado[0] . "@alteraremail.com") {
                        $dados = [
                            'cpf' => $cpf,
                            'nome' => $nome,
                            'senha' => $senha,
                        ];
                        // alteração 2/3 -> comentado o código abaixo
                        // $user->notify(new PasswordUserNotification($dados));
                    }
                }
            } else {
                $role = Role::find($dados_importacao->role_id);
                $user->assignRole($role->name);
                $user->ugprimaria = $ugprimaria;
                $user->save();
                if (count($ugsecundaria)) {
                    $user->unidades()->attach($ugsecundaria);
                }
            }
//            dump($user->cpf,$user->name);
        }
    }

    public function executaInsercaoMassaTerceirizado($array_dado, $contrato_id){
        $params_importacao = $this->retornaParamsImportacao($array_dado, $contrato_id);
        Contratoterceirizado::create($params_importacao);
    }

    private function buscaUsuario($cpf, $email)
    {
        $user = BackpackUser::where('email', $email)->first();
        if (!isset($user->id)) {
            $user = BackpackUser::where('cpf', $cpf)->first();
        }
        if (!isset($user->id)) {
            return null;
        }
        return $user;
    }

    private function buscaUgPorCodigo($cod)
    {
        $unidade = Unidade::where('codigo', $cod)
            ->first();

        return $unidade->id;
    }

    public function importacaoTerceirizados(Request $request)
    {
        $unidade = session()->get('user_ug_id');
        $contrato = Contrato::find($request->contrato_id);

        if (strpos($contrato->numero, '/')) {
            $contrato->numero = str_replace('/', '_', $contrato->numero);
        }
        $nome_arquivo = $contrato->numero . '_' . date('d-m-Y_H_i_s');

        $importacao = new Importacao();

        /**
         * Salva na tabela de importacao
         */
        $tipos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo Importação');
        })->where('descricao', '=', 'Terceirizado')->first();

        $situacao_id = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Situação Arquivo');
        })
            ->where('descricao', 'Pendente de Execução')->first()->id;

        $role_id = Role::where(['name' => 'Responsável por Contrato'])->first()->id;

        $importacao->unidade_id = $unidade;
        $importacao->nome_arquivo = $nome_arquivo;
        $importacao->delimitador = $request->delimitador;
        $importacao->arquivos = $request->arquivos;
        $importacao->contrato_id = $contrato->id;
        $importacao->tipo_id = $tipos->id;
        $importacao->situacao_id = $situacao_id;
        $importacao->save();

        $this->verificaTipoIniciarExecucao($importacao, $contrato->id);

        \Alert::success('Item cadastrado com sucesso!')->flash();
        return redirect()->route('crud.terceirizados.index',['contrato_id' => $contrato->id]);
    }
}
