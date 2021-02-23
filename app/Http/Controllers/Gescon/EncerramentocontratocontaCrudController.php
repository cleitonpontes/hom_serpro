<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Contrato;
use App\Models\Contratoconta;
use App\Models\Encargo;
use App\Models\Contratoterceirizado;
use App\Models\Retiradacontratoconta;
// use App\Models\Movimentacaocontratoconta;
// use App\Models\Lancamento;
use App\Models\Encerramentocontratoconta;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EncerramentocontratocontaRequest as StoreRequest;
use App\Http\Requests\EncerramentocontratocontaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

// inserido
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class DepositocontratocontaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EncerramentocontratocontaCrudController extends CrudController
{
    public function setup()
    {
        $user_id = backpack_user()->id;

        // como demissão é um tipo de liberação, vamos buscar seu código
        $tipo_id_demissao = Encargo::getIdCodigoItemByDescricao('Liberação');

        $contratoconta_id = \Route::current()->parameter('contratoconta_id');
        $contratoConta = Contratoconta::where('id','=',$contratoconta_id)->first();
        if(!$contratoConta){
            abort('403', config('app.erro_permissao'));
        }
        $contrato_id = $contratoConta->contrato_id;
        \Route::current()->setParameter('contrato_id', $contrato_id);

        $contrato = Contrato::where('id','=',$contrato_id)
            ->where('unidade_id','=',session()->get('user_ug_id'))->first();

        if(!$contrato){
            abort('403', config('app.erro_permissao'));
        }

        // buscar quantidade de contratos terceirizados pelo contrato_id
        // $arrayContratosTerceirizados = Contratoterceirizado::where('contrato_id','=',$contrato_id)->get();
        // $quantidadeContratosTerceirizados = count($arrayContratosTerceirizados);

        // buscar o tipo de movimentação em codigoitens = depósito
        // $objTipoMovimentacaoDeposito = Codigoitem::whereHas('codigo', function ($query) {
        //     $query->where('descricao', '=', 'Tipo Movimentação');
        // })
        // ->where('descricao', '=', 'Depósito')
        // ->where('descricao', '=', 'Provisão')
        // ->first();
        // $idTipoMovimentacaoDeposito = $objTipoMovimentacaoDeposito->id;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Encerramentocontratoconta');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/contratoconta/' . $contratoconta_id . '/encerramentocontratoconta');
        $this->crud->setEntityNameStrings('encerramento', 'Encerramento');

        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->enableExportButtons();

        // cláusulas para possibilitar buscas
        // $this->crud->addClause('where', 'contratoconta_id', '=', $contratoconta_id);
        // $this->crud->addClause('orderby', 'ano_competencia');
        // $this->crud->addClause('orderby', 'mes_competencia');

        // $this->crud->denyAccess('create');
        // $this->crud->denyAccess('update');
        // $this->crud->denyAccess('delete');
        // $this->crud->denyAccess('show');
        $this->crud->denyAccess('list');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // $colunas = $this->Colunas();
        // $this->crud->addColumns($colunas);

        // $campos = $this->Campos($idTipoMovimentacaoDeposito, $contratoconta_id, $contrato_id, $quantidadeContratosTerceirizados);
        $campos = $this->Campos($contratoconta_id, $contrato_id, $user_id, $tipo_id_demissao);
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in DepositocontratocontaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

    }

    // public function Campos($idTipoMovimentacaoDeposito, $contratoconta_id, $contrato_id, $quantidadeContratosTerceirizados)
    public function Campos($contratoconta_id, $contrato_id, $user_id, $tipo_id_demissao)
    {
        // vamos pegar o mês e ano de agora, que serão nossos mês / ano competência.
        $dataHoje = date('d/m/Y');
        $mesHoje = substr($dataHoje, 3,2);
        $anoHoje = substr($dataHoje,-4);

        $campos = [
            [   // Hidden
                'name' => 'contratoconta_id',
                'type' => 'hidden',
                'default' => $contratoconta_id,
            ],
            [   // Hidden
                'name' => 'situacao_retirada',
                'type' => 'hidden',
                'default' => 'Demissão',
            ],
            [   // Hidden
                'name' => 'situacao_movimentacao',
                'type' => 'hidden',
                'default' => 'Movimentação Criada',
            ],
            [   // Hidden
                'name' => 'tipo_id',
                'type' => 'hidden',
                'default' => $tipo_id_demissao,
            ],
            [   // Hidden
                'name' => 'user_id_encerramento',
                'type' => 'hidden',
                'default' => $user_id,
            ],
            [   // Hidden
                'name' => 'user_id',
                'type' => 'hidden',
                'default' => $user_id,
            ],

            [   // Hidden
                'name' => 'mes_competencia',
                'type' => 'hidden',
                'default' => $mesHoje,
            ],

            [   // Hidden
                'name' => 'ano_competencia',
                'type' => 'hidden',
                'default' => $anoHoje,
            ],

            [   // Hidden
                'name' => 'contrato_id',
                'type' => 'hidden',
                'default' => $contrato_id,
            ],
            [   // Hidden
                'name' => 'contratoconta_id',
                'type' => 'hidden',
                'default' => $contratoconta_id,
            ],

            [   //
                'name' => 'data_encerramento',
                'label' => 'Data do encerramento',
                'type' => 'text',
                // optionals
                'attributes' => [
                    // 'readonly' => 'readonly',
                    // 'style' => 'pointer-events: none;touch-action: none;'
                ], // allow decimals
                // 'default' => $quantidadeContratosTerceirizados,
            ],

            [   //
                'name' => 'obs_encerramento',
                'label' => 'Observação',
                'type' => 'text',
                // optionals
                'attributes' => [
                    // 'readonly' => 'readonly',
                    // 'style' => 'pointer-events: none;touch-action: none;'
                ], // allow decimals
                // 'default' => $quantidadeContratosTerceirizados,
            ],
        ];
        return $campos;
    }

    // public function Colunas()
    // {
    //     $colunas = [

    //         [
    //             'name'  => 'obs_encerramento',
    //             'label' => 'Observação',
    //             'type'  => 'text',
    //         ],
    //     ];
    //     return $colunas;
    // }
    // public function verificarSeMovimentacaoExiste($request){
    //     $objMovimentacao = new Movimentacaocontratoconta();
    //     if($objMovimentacao->verificarSeMovimentacaoExiste($request)){
    //         return true;
    //     }
    //     return false;
    // }
    // // verificar se no ano/mês de competência, o funcionário já tinha iniciado.
    // public function verificarSeCompetenciaECompativelComDataInicio($request, $objContratoTerceirizado){
    //     $mesCompetencia = $request->input('mes_competencia');
    //     $anoCompetencia = $request->input('ano_competencia');

    //     $dataInicio = $objContratoTerceirizado->data_inicio;
    //     $dataFim = $objContratoTerceirizado->data_fim;

    //     $mesDataInicio = substr($dataInicio, 5, 2);
    //     $anoDataInicio = substr($dataInicio, 0, 4);
    //     $diaDataInicio = substr($dataInicio, 8, 2);
    //     $mesDataFim = substr($dataFim, 5, 2);
    //     $anoDataFim = substr($dataFim, 0, 4);
    //     $diaDataFim = substr($dataFim, 8, 2);
    //     if( $anoCompetencia < $anoDataInicio ){
    //         return false;
    //     }
    //     if( $anoCompetencia == $anoDataInicio  && $mesCompetencia < $mesDataInicio){
    //         return false;
    //     }
    //     return true;
    // }
    // // verificar se o ano/mês de competência é menor do que o ano/mes atual.
    // public function verificarSeCompetenciaECompativelComDataAtual($request, $objContratoTerceirizado){
    //     $mesCompetencia = $request->input('mes_competencia');
    //     $anoCompetencia = $request->input('ano_competencia');

    //     $dataHoje = date("Y-m-d");
    //     $mesHoje = substr($dataHoje, 5, 2);
    //     $anoHoje = substr($dataHoje, 0, 4);
    //     $diaHoje = substr($dataHoje, 8, 2);

    //     if( $anoCompetencia > $anoHoje ){
    //         return false;
    //     }
    //     if( $anoCompetencia == $anoHoje  && $mesCompetencia >= $mesHoje){
    //         return false;
    //     }
    //     return true;
    // }
    // public function alterarStatusMovimentacao($idMovimentacao, $statusMovimentacao){
    //     $objMovimentacao = new Movimentacaocontratoconta();
    //     if($objMovimentacao->alterarStatusMovimentacao($idMovimentacao, $statusMovimentacao)){
    //         return true;
    //     }
    //     return false;
    // }
    // // este método não é o mesmo que tem em Movimentacaocontratoconta
    // public function criarMovimentacao($request){
    //     $objMovimentacaocontratoconta = new Movimentacaocontratoconta();
    //     if($id = $objMovimentacaocontratoconta->criarMovimentacao($request)){
    //         return $id;
    //     }
    //     return false;
    // }
    // public function excluirMovimentacao($idMovimentacao){
    //     $objMovimentacaocontratoconta = new Movimentacaocontratoconta();
    //     if($id = $objMovimentacaocontratoconta->excluirMovimentacao($idMovimentacao)){
    //         return true;
    //     }
    //     return false;
    // }
    public function store(StoreRequest $request)
    {
        $contrato_id = $request->input('contrato_id');
        $contratoconta_id = $request->input('contratoconta_id');
        $user_id = $request->input('user_id');
        $dataHoje = date("Y-m-d");
        $objRetiradacontratoconta = new Retiradacontratoconta();
        $obs_encerramento = $request->input('obs_encerramento');

        if( !$objRetiradacontratoconta->encerrarContaVinculada($request) ){
            \Alert::error('Problemas ao encerrar a conta.')->flash();
            return redirect()->back();
        }

        // salvar informações do encerramento em contrato conta
        $objContratoconta = Contratoconta::where('id', $contratoconta_id)->first();
        $objContratoconta->data_encerramento = $dataHoje;
        $objContratoconta->user_id_encerramento = $user_id;
        $objContratoconta->obs_encerramento = $obs_encerramento;
        if( !$objContratoconta->save() ){
            $mensagem = 'Erro ao encerrar a conta.';
            \Alert::error($mensagem)->flash();
            // if( !self::excluirMovimentacao($idMovimentacao) ){
            //     \Alert::error('Problemas ao excluir a movimentação.')->flash();
            // }
            return redirect()->back();
        }

        $mensagem = 'Conta encerrada com sucesso!';
        \Alert::success($mensagem)->flash();

        // $linkLocation = '/gescon/contrato/'.$contrato_id.'/contratocontas';
        $linkLocation = '/gescon/contrato/contratoconta/'.$contratoconta_id.'/movimentacaocontratoconta';
        return redirect($linkLocation);


        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
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
