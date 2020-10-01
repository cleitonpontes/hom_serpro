<?php

namespace App\Http\Controllers\Gescon;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RetiradacontratocontaRequest as StoreRequest;
use App\Http\Requests\RetiradacontratocontaRequest as UpdateRequest;

use App\Models\Contrato;
use App\Models\Contratoconta;
use App\Models\Codigoitem;
use App\Models\Contratoterceirizado;
use App\Models\Retiradacontratoconta;
use App\Models\Movimentacaocontratoconta;
use App\Models\Lancamento;
use App\Models\Encargo;

use Backpack\CRUD\CrudPanel;

// inserido
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


/**
 * Class RetiradacontratocontaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RetiradacontratocontaCrudController extends CrudController
{
    public function setup()
    {

        $contratoterceirizado_id = \Route::current()->parameter('contratoterceirizado_id');
        $objContratoTerceirizado = Contratoterceirizado::where('id', '=', $contratoterceirizado_id)->first();
        $idFuncaoContratoTerceirizado = $objContratoTerceirizado->funcao_id;
        $objCodigoitensFuncaoContratoTerceirizado = Codigoitem::where('id', '=', $idFuncaoContratoTerceirizado)->first();
        $nomeFuncaoContratoTerceirizado = $objCodigoitensFuncaoContratoTerceirizado->descricao;

        $arrayObjetosEncargo = \DB::table('encargos')
        ->select('encargos.*', 'codigoitens.descricao')
        ->join('codigoitens', 'codigoitens.id', '=', 'encargos.tipo_id')
        ->get();


        // $contrato_id = $objContratoTerceirizado->contrato_id;
        // // $contratoconta_id = \Route::current()->parameter('contratoconta_id');
        // $objContratoConta = Contratoconta::where('contrato_id','=',$contrato_id)->first();
        // $contratoconta_id = $objContratoConta->id;

        // dd($contratoConta);
        // if(!$contratoConta){
        //     abort('403', config('app.erro_permissao'));
        // }
        // $contrato_id = $contratoConta->contrato_id;
        // $contrato = Contrato::where('id','=',$contrato_id)
        //     ->where('unidade_id','=',session()->get('user_ug_id'))->first();
        // if(!$contrato){
        //     abort('403', config('app.erro_permissao'));
        // }

        // buscar os tipos de movimentação em codigoitens para seleção
        $objTipoMovimentacaoRetirada = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo Movimentação');
        })
        ->where('descricao', '=', 'Retirada')
        ->first();
        $idTipoMovimentacaoRetirada = $objTipoMovimentacaoRetirada->id;

        $objRetiradacontratoconta = new Retiradacontratoconta();
        $arrayObjetosEncargoParaCombo = $objRetiradacontratoconta->getEncargosParaCombo();

        // // buscar o tipo de movimentação em codigoitens = retirada
        // $objTipoMovimentacaoRetirada = Codigoitem::whereHas('codigo', function ($query) {
        //     $query->where('descricao', '=', 'Tipo Movimentação');
        // })
        // ->where('descricao', '=', 'Retirada')
        // ->first();
        // $idTipoMovimentacaoRetirada = $objTipoMovimentacaoRetirada->id;




        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Retiradacontratoconta');
        // $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/contratoconta/' . $contratoconta_id . '/retiradacontratoconta');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/contratoconta/contratoterceirizado/'.$contratoterceirizado_id.'/retiradacontratoconta');
        $this->crud->setEntityNameStrings('nova retirada', 'Retiradas');

        $this->crud->enableExportButtons();

        // cláusulas para possibilitar buscas
        // $this->crud->addClause('where', 'contratoconta_id', '=', $contratoconta_id);
        // $this->crud->addClause('orderby', 'ano_competencia');
        // $this->crud->addClause('orderby', 'mes_competencia');


        // dd(\Route::current()->getName());

        // $this->crud->removeAllButtons();
        // $this->crud->addButtonFromView('top', 'adicionarretirada', 'adicionarretirada', 'end');
        // $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        // $this->crud->addButtonFromView('line', 'voltarcontavinculada', 'voltarcontavinculada', 'end');



        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // $colunas = $this->Colunas();
        // $this->crud->addColumns($colunas);

        $campos = $this->Campos($objContratoTerceirizado, $arrayObjetosEncargo, $idTipoMovimentacaoRetirada, $nomeFuncaoContratoTerceirizado, $arrayObjetosEncargoParaCombo);
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in RetiradacontratocontaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        // $this->crud->addButtonFromView('top', 'buscardadosfuncionarioretirada', 'buscardadosfuncionarioretirada', 'end');
        // $this->crud->addButtonFromModelFunction('top', 'adicionarBotaoAvancar', 'adicionarBotaoAvancar', 'end');
        // $this->crud->addButtonFromModelFunction('line', 'open_google', 'openGoogle', 'beginning');

        // dd($this->crud);

    }

    public function Campos($objContratoTerceirizado, $arrayObjetosEncargo, $idTipoMovimentacaoRetirada, $nomeFuncaoContratoTerceirizado, $arrayObjetosEncargoParaCombo){

        $campos = [
            [   // Hidden
                'name' => 'contratoterceirizado_id',
                'type' => 'hidden',
                'default' => $objContratoTerceirizado->id, // tipo da movimentação (dep, ret, rep)
            ],
            [   // Hidden
                'name' => 'tipo_id',
                'type' => 'hidden',
                'default' => $idTipoMovimentacaoRetirada, // tipo da movimentação (dep, ret, rep)
            ],
            [   //
                'name' => 'nome',
                'label' => 'Nome do funcionário',
                'type' => 'text',
                // optionals
                'attributes' => [
                    'readonly' => 'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ], // allow decimals
                'default' => $objContratoTerceirizado->nome,
            ],
            [   //
                'name' => 'funcao',
                'label' => 'Função do funcionário',
                'type' => 'text',
                // optionals
                'attributes' => [
                    'readonly' => 'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ], // allow decimals
                'default' => $nomeFuncaoContratoTerceirizado,
            ],
            [   //
                'name' => 'remuneracao',
                'label' => 'Remuneração do funcionário',
                'type' => 'text',
                // optionals
                'attributes' => [
                    'readonly' => 'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ], // allow decimals
                'default' => $objContratoTerceirizado->salario,
            ],

            [   // Hidden
                'name' => 'situacao_movimentacao',
                'type' => 'hidden',
                'default' => 'Movimentação Criada',
            ],

            [ // select_from_array
                'name' => 'mes_competencia',
                'label' => "Mês Retirada",
                'type' => 'select2_from_array',
                'options' => config('app.meses_referencia_fatura'), // vai buscar em app.php o array meses_referencia_fatura
                'allows_null' => false,
            ],
            [ // select_from_array
                'name' => 'ano_competencia',
                'label' => "Ano Retirada",
                'type' => 'select2_from_array',
                'options' => config('app.anos_referencia_fatura'), // vai buscar em app.php o array anos_referencia_fatura
                'default' => date('Y'),
                'allows_null' => false,
            ],
            [ // select_from_array
                'name' => 'tipo_id_encargo',
                'label' => "Situação da Retirada",
                'type' => 'select2_from_array',
                'options' => $arrayObjetosEncargoParaCombo, // aqui é de onde vai buscar o array
                'allows_null' => false,
            ],

            [   // Number
                'name' => 'valor',
                'label' => 'Valor',
                'type' => 'money_fatura',
                // optionals
                'attributes' => [
                    'id' => 'valor',
                    // 'readonly' => 'readonly',
                    // 'style' => 'pointer-events: none;touch-action: none;'
                ], // allow decimals
                'prefix' => "R$",
                // 'default' => $objContratoTerceirizado->salario, // tipo da movimentação (dep, ret, rep)
            ],
        ];

        // vamos gerar os campos com os valores dos saldos
        foreach( $arrayObjetosEncargo as $objEncargo ){
            $nomeEncargo = $objEncargo->descricao;
            $tipoId = $objEncargo->tipo_id;
            $objContratoConta = new Contratoconta();
            $saldoEncargoContratoTerceirizado = $objContratoConta->getSaldoContratoContaPorTipoEncargoPorContratoTerceirizado($objContratoTerceirizado->id, $tipoId);

            $saldoDepositoEncargoContratoTerceirizado = $objContratoConta->getSaldoDepositoPorTipoEncargoPorContratoTerceirizado($objContratoTerceirizado->id, $tipoId);
            $saldoRetiradaEncargoContratoTerceirizado = $objContratoConta->getSaldoRetiradaPorTipoEncargoPorContratoTerceirizado($objContratoTerceirizado->id, $tipoId);

            $campos[] = [   //
                'name' => $nomeEncargo,
                'label' => 'Saldo '.$nomeEncargo,
                'type' => 'text',
                // optionals
                'attributes' => [
                    'readonly' => 'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ], // allow decimals
                'default' => '('.$saldoDepositoEncargoContratoTerceirizado.' - '.$saldoRetiradaEncargoContratoTerceirizado.') = '.$saldoEncargoContratoTerceirizado,
            ];
        }

        return $campos;
    }
    public function getIdContratoByIdContratoTerceirizado($idContratoTerceirizado){
        $obj = \DB::table('contratoterceirizados')
            ->select('contratocontas.contrato_id')
            ->where('contratoterceirizados.id','=',$idContratoTerceirizado)
            ->join('contratos', 'contratos.id', '=', 'contratoterceirizados.contrato_id')
            ->join('contratocontas', 'contratocontas.contrato_id', '=', 'contratos.id')
            ->first();
        $idContrato = $obj->contrato_id;
        return $idContrato;

    }
    public function getIdContratoContaByIdContratoTerceirizado($idContratoTerceirizado){
        $obj = \DB::table('contratoterceirizados')
            ->select('contratocontas.id')
            ->where('contratoterceirizados.id','=',$idContratoTerceirizado)
            ->join('contratos', 'contratos.id', '=', 'contratoterceirizados.contrato_id')
            ->join('contratocontas', 'contratocontas.contrato_id', '=', 'contratos.id')
            ->first();
        $idContratoConta = $obj->id;
        return $idContratoConta;

    }
    public function criarMovimentacao($request){
        $objMovimentacaocontratoconta = new Movimentacaocontratoconta();
        $objMovimentacaocontratoconta->contratoconta_id = $request->input('contratoconta_id');
        $objMovimentacaocontratoconta->tipo_id = $request->input('tipo_id');
        $objMovimentacaocontratoconta->mes_competencia = $request->input('mes_competencia');
        $objMovimentacaocontratoconta->ano_competencia = $request->input('ano_competencia');
        $objMovimentacaocontratoconta->valor_total_mes_ano = 0;
        $objMovimentacaocontratoconta->situacao_movimentacao = $request->input('situacao_movimentacao');
        $objMovimentacaocontratoconta->user_id = $request->input('user_id');
        if($objMovimentacaocontratoconta->save()){
            return $objMovimentacaocontratoconta->id;
        } else {
            echo false;
        }
    }
    public function excluirMovimentacao($idMovimentacao){
        if($objMovimentacaocontratoconta = Movimentacaocontratoconta::where('id','=',$idMovimentacao)->delete()){return true;}
        else{return false;}
    }

    public function alterarStatusMovimentacao($idMovimentacao, $statusMovimentacao){
        $objMovimentacao = Movimentacaocontratoconta::where('id','=',$idMovimentacao)->first();
        $objMovimentacao->situacao_movimentacao = $statusMovimentacao;
        if(!$objMovimentacao->save()){
            return false;
        } else {
            return true;
        }

    }


    public function store(StoreRequest $request)
    {
        /*

            - salvar a movimentação de retirada

            - salvar os lançamentos da movimentação



            - pegar o contratoterceirizado_id e buscar o contratoconta_id - ok

            - salvar a movimentação - usar o id para os lançamentos - ok

            - verificar se tem saldo para o encargo / valor - caso não tenha, excluir movimentação - ok

            - verificar se ainda não existe a movimentação

            - alterar o tipo da movimentação

            - verificar se é demissão - tirar tudo
            - salvar o lançamento para o encargo / valor

        */

        // salvar a movimentação
        $idContratoTerceirizado = $request->input('contratoterceirizado_id');
        $idContratoConta = self::getIdContratoContaByIdContratoTerceirizado($idContratoTerceirizado);

        $user_id = backpack_user()->id;
        $request->request->set('user_id', $user_id);

        $valorRetirada = $request->input('valor');
        $valorRetirada = str_replace('.', '', $valorRetirada);
        $valorRetirada = str_replace(',', '.', $valorRetirada);

        $idContrato = self::getIdContratoByIdContratoTerceirizado($idContratoTerceirizado);

        // vamos buscar o contratoconta_id pelo contratoterceirizado_id
        $request->request->set('contratoconta_id', $idContratoConta);
        // aqui quer dizer que ainda não existe a movimentação. Precisamos criá-la.
        if( !$idMovimentacao = self::criarMovimentacao($request) ){
            $mensagem = 'Problemas ao criar a movimentação.';
            \Alert::error($mensagem)->flash();
            return redirect()->back();
        }
        // aqui a movimentação já foi criada e já temos o $idMovimentacao - vamos atribuir seu valor ao request
        $request->request->set('movimentacao_id', $idMovimentacao);

        // vamos alterar o status da movimentação
        self::alterarStatusMovimentacao($idMovimentacao, 'Movimentação Em Andamento');

        // vamos verificar o saldo total da conta
        $objContratoConta = new Contratoconta();
        $saldoContratoConta = $objContratoConta->getSaldoContratoContaPorContratoTerceirizado($idContratoTerceirizado);

        // vamos verificar o saldo por encargo
        $tipoIdEncargo = $request->input('tipo_id_encargo');
        $saldoContratoContaPorTipoEncargo = $objContratoConta->getSaldoContratoContaPorTipoEncargoPorContratoTerceirizado($idContratoTerceirizado, $tipoIdEncargo);


        if( $valorRetirada > $saldoContratoContaPorTipoEncargo ){
            // aqui quer dizer que não existe saldo para esta retirada - vamos excluir a movimentação
            self::excluirMovimentacao($idMovimentacao);
            \Alert::error('Não existe saldo suficiente para esta retirada.')->flash();
            return redirect()->back();
        }

        // aqui quer dizer que está tudo certo para salvar a retirada
        $objMovimentacaoIdEncargo = new Movimentacaocontratoconta();
        $idEncargo = $objMovimentacaoIdEncargo->getIdEncargoByIdCodigoItens($tipoIdEncargo);

        // gerar o lançamento
        $objLancamento = new Lancamento();
        $objLancamento->contratoterceirizado_id = $idContratoTerceirizado;
        $objLancamento->encargo_id = $idEncargo;
        $objLancamento->valor = $valorRetirada;
        $objLancamento->movimentacao_id = $idMovimentacao;
        if( !$objLancamento->save() ){
            $mensagem = 'Erro ao salvar o lançamento.';
            \Alert::error($mensagem)->flash();
            if( !self::excluirMovimentacao($idMovimentacao) ){
                \Alert::error('Problemas ao excluir a movimentação.')->flash();
            }
            return redirect()->back();
        }

        // aqui os lançamentos já foram gerados. Vamos alterar o status da movimentação
        self::alterarStatusMovimentacao($idMovimentacao, 'Movimentação Finalizada');

        $linkLocation = '/gescon/contrato/'.$idContrato.'/contratocontas';
        return redirect($linkLocation)->with('msg', 'Testes!!!!!');

        // // your additional operations before save here
        // $redirect_location = parent::storeCrud($request);
        // // your additional operations after save here
        // // use $this->data['entry'] or $this->crud->entry
        // return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
