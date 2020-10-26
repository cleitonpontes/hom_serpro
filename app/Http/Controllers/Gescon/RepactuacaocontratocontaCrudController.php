<?php

namespace App\Http\Controllers\Gescon;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RepactuacaocontratocontaRequest as StoreRequest;
use App\Http\Requests\RepactuacaocontratocontaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Codigoitem;
use App\Models\Contratoconta;
use App\Models\Contratoterceirizado;
use App\Models\Movimentacaocontratoconta;
use App\Models\Encargo;
use App\Models\Lancamento;


/**
 * Class RepactuacaocontratocontaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RepactuacaocontratocontaCrudController extends CrudController
{
    public function setup()
    {
        $contratoconta_id = \Route::current()->parameter('contratoconta_id');
        $objContratoConta = Contratoconta::where('id', '=', $contratoconta_id)->first();
        if(!$objContratoConta){
            abort('403', config('app.erro_permissao'));
        }
        $idContrato = $objContratoConta->contrato_id;
        // $contratoconta_id = $objContratoConta->id;
        $funcao_id = \Route::current()->parameter('funcao_id');
        $objFuncao = Codigoitem::where('id', '=', $funcao_id)->first();

        \Route::current()->setParameter('contrato_id', $idContrato);
        \Route::current()->setParameter('contratoconta_id', $contratoconta_id);
        \Route::current()->setParameter('funcao_id', $funcao_id);


        // buscar o tipo de movimentação em codigoitens = depósito
        $objTipoMovimentacaoRepactuacao = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo Movimentação');
        })
        ->where('descricao', '=', 'Repactuação')
        ->first();
        $idTipoMovimentacaoRepactuacao = $objTipoMovimentacaoRepactuacao->id;

        // dd($this->crud->request->session('attributes');
        // echo ' ===>> '.$saveAction['active']['value'];


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Repactuacaocontratoconta');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/contratoconta/' . $contratoconta_id . '/'. $funcao_id .'/repactuacaocontratoconta');
        $this->crud->setEntityNameStrings('Repactuação', 'Repactuação');

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

        $campos = $this->Campos($objFuncao, $idContrato, $contratoconta_id, $idTipoMovimentacaoRepactuacao);
        $this->crud->addFields($campos);


        // add asterisk for fields that are required in RepactuacaocontratocontaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }
    public function Campos($objFuncao, $idContrato, $contratoconta_id, $idTipoMovimentacaoRepactuacao){
        $campos = [
            [   // Hidden
                'name' => 'situacao_movimentacao',
                'type' => 'hidden',
                'default' => 'Movimentação Criada',
            ],
            [   // Hidden
                'name' => 'tipo_id',
                'type' => 'hidden',
                'default' => $idTipoMovimentacaoRepactuacao, // tipo da movimentação (dep, ret, rep)
            ],
            [   // Hidden
                'name' => 'idContrato',
                'type' => 'hidden',
                'default' => $idContrato, // tipo da movimentação (dep, ret, rep)
            ],
            [   // Hidden
                'name' => 'contratoconta_id',
                'type' => 'hidden',
                'default' => $contratoconta_id, // tipo da movimentação (dep, ret, rep)
            ],
            [   // Hidden
                'name' => 'funcao_id',
                'type' => 'hidden',
                'default' => $objFuncao->id, // tipo da movimentação (dep, ret, rep)
            ],


            [   //
                'name' => 'nome_funcao',
                'label' => 'Nome da função',
                'type' => 'text',
                // optionals
                'attributes' => [
                    'readonly' => 'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ], // allow decimals
                'default' => $objFuncao->descricao,
            ],
            [
                'name'  => 'jornada',
                'label' => 'Jornada',
                'type'  => 'text',
            ],


            [   // Number
                'name' => 'novoSalario',
                'label' => 'Novo salário',
                'type' => 'money_fatura',
                // optionals
                'attributes' => [
                    'id' => 'novoSalario',
                    // 'readonly' => 'readonly',
                    // 'style' => 'pointer-events: none;touch-action: none;'
                ], // allow decimals
                'prefix' => "R$",
                // 'default' => $objContratoTerceirizado->salario, // tipo da movimentação (dep, ret, rep)
            ],

            [ // select_from_array
                'name' => 'mes_inicio',
                'label' => "Mês Início",
                'type' => 'select2_from_array',
                'options' => config('app.meses_referencia_fatura'), // vai buscar em app.php o array meses_referencia_fatura
                'allows_null' => false,
            ],
            [ // select_from_array
                'name' => 'ano_inicio',
                'label' => "Ano Início",
                'type' => 'select2_from_array',
                'options' => config('app.anos_referencia_fatura'), // vai buscar em app.php o array anos_referencia_fatura
                'default' => date('Y'),
                'allows_null' => false,
            ],


            [ // select_from_array
                'name' => 'mes_fim',
                'label' => "Mês Fim",
                'type' => 'select2_from_array',
                'options' => config('app.meses_referencia_fatura'), // vai buscar em app.php o array meses_referencia_fatura
                'allows_null' => false,
            ],
            [ // select_from_array
                'name' => 'ano_fim',
                'label' => "Ano Fim",
                'type' => 'select2_from_array',
                'options' => config('app.anos_referencia_fatura'), // vai buscar em app.php o array anos_referencia_fatura
                'default' => date('Y'),
                'allows_null' => false,
            ],



            // [
            //     'name' => 'data_inicio',
            //     'label' => "Data início",
            //     'type' => 'date',
            //     // 'default' => $objContratoTerceirizado->data_fim,
            //     'allows_null' => false,
            //     // optionals
            //     'attributes' => [
            //         'id' => 'data_inicio',
            //         // 'readonly' => 'readonly',
            //     ],
            // ],
            // [
            //     'name' => 'data_fim',
            //     'label' => "Data fim",
            //     'type' => 'date',
            //     // 'default' => $objContratoTerceirizado->data_fim,
            //     'allows_null' => false,
            //     // optionals
            //     'attributes' => [
            //         'id' => 'data_fim',
            //         // 'readonly' => 'readonly',
            //     ],
            // ],
            // [   // Hidden
            //     'name' => 'tipo_id',
            //     'type' => 'hidden',
            //     'default' => $idTipoMovimentacaoRetirada, // tipo da movimentação (dep, ret, rep)
            // ],
            // [   //
            //     'name' => 'funcao',
            //     'label' => 'Função do funcionário',
            //     'type' => 'text',
            //     // optionals
            //     'attributes' => [
            //         'readonly' => 'readonly',
            //         'style' => 'pointer-events: none;touch-action: none;'
            //     ], // allow decimals
            //     'default' => $nomeFuncaoContratoTerceirizado,
            // ],
            // [   //
            //     'name' => 'remuneracao',
            //     'label' => 'Remuneração do funcionário',
            //     'type' => 'text',
            //     // optionals
            //     'attributes' => [
            //         'readonly' => 'readonly',
            //         'style' => 'pointer-events: none;touch-action: none;'
            //     ], // allow decimals
            //     'default' => $objContratoTerceirizado->salario,
            // ],
            // [   //
            //     'name' => 'fat_empresa',
            //     'label' => 'Encargo',
            //     'type' => 'text',
            //     // optionals
            //     'attributes' => [
            //         'readonly' => 'readonly',
            //         'style' => 'pointer-events: none;touch-action: none;'
            //     ], // allow decimals
            //     'default' => $objContratoConta->fat_empresa,
            //     'prefix' => "%",
            // ],

            // [   // Hidden
            //     'name' => 'situacao_movimentacao',
            //     'type' => 'hidden',
            //     'default' => 'Movimentação Criada',
            // ],

            // [ // select_from_array
            //     'name' => 'mes_competencia',
            //     'label' => "Mês Retirada",
            //     'type' => 'select2_from_array',
            //     'options' => config('app.meses_referencia_fatura'), // vai buscar em app.php o array meses_referencia_fatura
            //     'allows_null' => false,
            // ],
            // [ // select_from_array
            //     'name' => 'ano_competencia',
            //     'label' => "Ano Retirada",
            //     'type' => 'select2_from_array',
            //     'options' => config('app.anos_referencia_fatura'), // vai buscar em app.php o array anos_referencia_fatura
            //     'default' => date('Y'),
            //     'allows_null' => false,
            // ],
            // [ // select_from_array
            //     'name' => 'situacao_retirada',
            //     'label' => "Situação da Retirada",
            //     // 'type' => 'select2_from_array',
            //     'type' => 'select2_from_array_hidden_field',    //  tipo criado para possibilitar uso do jquery para esconder campos
            //     'options' => $arrayObjetosEncargoParaCombo, // aqui é de onde vai buscar o array
            //     'allows_null' => false,
            // ],
            // [   // Number
            //     'name' => 'valor',
            //     'label' => 'Valor',
            //     'type' => 'money_fatura',
            //     // optionals
            //     'attributes' => [
            //         'id' => 'valor',
            //         // 'readonly' => 'readonly',
            //         // 'style' => 'pointer-events: none;touch-action: none;'
            //     ], // allow decimals
            //     'prefix' => "R$",
            //     // 'default' => $objContratoTerceirizado->salario, // tipo da movimentação (dep, ret, rep)
            // ],

        ];

        // // vamos gerar os campos com os valores dos saldos
        // foreach( $arrayObjetosEncargo as $objEncargo ){
        //     $nomeEncargo = $objEncargo->descricao;
        //     $tipoId = $objEncargo->tipo_id;
        //     $objContratoConta = new Contratoconta();


        //     $saldoEncargoContratoTerceirizado = $objContratoConta->getSaldoContratoContaPorTipoEncargoPorContratoTerceirizado($objContratoTerceirizado->id, $tipoId);
        //     $saldoEncargoContratoTerceirizado = number_format($saldoEncargoContratoTerceirizado, 2, '.', ',' );

        //     $saldoDepositoEncargoContratoTerceirizado = $objContratoConta->getSaldoDepositoPorTipoEncargoPorContratoTerceirizado($objContratoTerceirizado->id, $tipoId);
        //     $saldoDepositoEncargoContratoTerceirizado = number_format($saldoDepositoEncargoContratoTerceirizado, 2, '.', ',' );

        //     $saldoRetiradaEncargoContratoTerceirizado = $objContratoConta->getSaldoRetiradaPorTipoEncargoPorContratoTerceirizado($objContratoTerceirizado->id, $tipoId);
        //     $saldoRetiradaEncargoContratoTerceirizado = number_format($saldoRetiradaEncargoContratoTerceirizado, 2, '.', ',' );

        //     $campos[] = [   //
        //         'name' => $nomeEncargo,
        //         'label' => 'Saldo '.$nomeEncargo,
        //         'type' => 'text',
        //         // optionals
        //         'attributes' => [
        //             'readonly' => 'readonly',
        //             'style' => 'pointer-events: none;touch-action: none;'
        //         ], // allow decimals
        //         // 'default' => '('.$saldoDepositoEncargoContratoTerceirizado.' - '.$saldoRetiradaEncargoContratoTerceirizado.') = '.$saldoEncargoContratoTerceirizado,
        //         'default' => $saldoEncargoContratoTerceirizado,
        //     ];
        // }

        return $campos;
    }
    public function salvarNovoSalario($idContratoTerceirizado, $novoSalario){
        $objContratoTerceirizadoSalvarSalario = Contratoterceirizado::where('id', $idContratoTerceirizado)->first();
        $objContratoTerceirizadoSalvarSalario->salario = $novoSalario;
        $objContratoTerceirizadoSalvarSalario->save();
        return true;
    }
    public function alterarStatusMovimentacao($idMovimentacao, $statusMovimentacao){
        $objMovimentacao = new Movimentacaocontratoconta();
        if($objMovimentacao->alterarStatusMovimentacao($idMovimentacao, $statusMovimentacao)){
            return true;
        }
        return false;
    }

    public function criarMovimentacao($request){
        $dataHoje = time();
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

    public function store(StoreRequest $request)
    {
        $quantidadeLancamentosGerados = 0;
        $user_id = backpack_user()->id;
        $request->request->set('user_id', $user_id);
        $idContratoConta = $request->input('contratoconta_id');

        // buscar todos os terceirizados pelo contrato e pela função
        $novoSalario = $request->input('novoSalario');
        $novoSalario = str_replace('.', '', $novoSalario);
        $novoSalario = str_replace(',', '.', $novoSalario);
        $request->request->set('salario_novo', $novoSalario);

        $mesInicio = $request->input('mes_inicio');
        $mesFim = $request->input('mes_fim');

        $anoInicio = $request->input('ano_inicio');
        $anoFim = $request->input('ano_fim');

        $jornada = $request->input('jornada');

        $idContrato = $request->input('idContrato');
        $idFuncao = $request->input('funcao_id');
        $arrayContratosTerceirizados = Contratoterceirizado::where('contrato_id', $idContrato)
        ->where('funcao_id', $idFuncao)
        ->get();

        if(count($arrayContratosTerceirizados) > 0){

            // varrer os contratos
            $arrayIdsMovimentacoesGeradas = array();
            foreach( $arrayContratosTerceirizados as $objContratoTerceirizado ){
                $jornadaContratoTerceirizado = $objContratoTerceirizado->jornada;
                $salarioAtual = $objContratoTerceirizado->salario;
                $diferencaEntreSalarios = ($novoSalario - $salarioAtual);

                // vamos verificar se a jornada informada é a mesma da jornada do terceirizado.
                if( $jornada == $jornadaContratoTerceirizado ){
                    // para cada terceirizado - buscar os lançamentos pela data
                    $idContratoTerceirizado = $objContratoTerceirizado->id;

                    $arrayLancamentosTerceirizado = self::getTodosLancamentosDepositoByIdContratoTerceirizado($idContratoTerceirizado);

                    // vamos varrer os lançamentos para verificar o mês / ano
                    $idMovimentacaoAux = null;
                    foreach($arrayLancamentosTerceirizado as $objLancamentoExistente){
                        $encargo_id = $objLancamentoExistente->encargo_id;
                        $objEncargo = Encargo::where('id', $encargo_id)->first();
                        $idMovimentacaoLancamento = $objLancamentoExistente->movimentacao_id;
                        $objMovimentacaoLancamento = Movimentacaocontratoconta::where('id', $idMovimentacaoLancamento)->first();
                        $mesMovimentacaoLancamento = $objMovimentacaoLancamento->mes_competencia;
                        $anoMovimentacaoLancamento = $objMovimentacaoLancamento->ano_competencia;

                        // vamos verificar se é o caso de criarmos uma nova movimentação
                        if( $idMovimentacaoAux != $idMovimentacaoLancamento ){

                            // vamos finalizar a movimentação anterior
                            if($idMovimentacaoAux!=null){
                                // aqui os lançamentos já foram gerados e entraremos em uma nova movimentação.
                                self::alterarStatusMovimentacao($idMovimentacao, 'Movimentação Finalizada');
                            }

                            // Criar a nova movimentação
                            $request->request->set('mes_competencia', $mesMovimentacaoLancamento); // será utilizado na nova mov.
                            $request->request->set('ano_competencia', $anoMovimentacaoLancamento); // será utilizado na nova mov.
                            if( !$idMovimentacao = self::criarMovimentacao($request) ){
                                $mensagem = 'Problemas ao criar a movimentação.';
                                \Alert::error($mensagem)->flash();
                                return redirect()->back();
                            }

                            // aqui a movimentação foi criada.
                            array_push($arrayIdsMovimentacoesGeradas, $idMovimentacao);
                            $request->request->set('movimentacao_id', $idMovimentacao);

                            // vamos alterar o status da movimentação
                            self::alterarStatusMovimentacao($idMovimentacao, 'Movimentação Em Andamento');

                            //
                            $idMovimentacaoAux = $idMovimentacaoLancamento;
                        }





                        // início transformar em método
                        // verificar o mês e ano início da movimentação do lançamento
                        $continuar = false;
                        if( $anoMovimentacaoLancamento >= $anoInicio ){
                            if( $anoMovimentacaoLancamento == $anoInicio ){
                                if( $mesMovimentacaoLancamento >= $mesInicio ){
                                    $continuar = true;
                                } else {
                                    // aqui não faz nada
                                    $continuar = false;
                                }
                            } elseif( $anoMovimentacaoLancamento > $anoInicio ){
                                $continuar = true;
                            }
                        } else {
                            // aqui não faz nada
                            $continuar = false;
                        }
                        // verificar o mês e ano fim
                        if( $anoMovimentacaoLancamento <= $anoFim && $continuar == true  ){
                            if( $anoMovimentacaoLancamento == $anoFim ){
                                if( $mesMovimentacaoLancamento <= $mesFim ){
                                    // aqui tudo certo
                                } elseif( $mesMovimentacaoLancamento > $mesFim ){
                                    $continuar = false;
                                }
                            } elseif( $anoMovimentacaoLancamento < $anoFim ){
                                // aqui tudo certo
                            }
                        } else {
                            $continuar = false;
                        }
                        // fim transformar em método




                        // verificar se está tudo certo pra continuar
                        if($continuar){
                            // início transformar em método
                            // aqui as verificações estão ok
                            $percentualEncargo = $objEncargo->percentual;
                            $valorSalvar = ( $diferencaEntreSalarios * $percentualEncargo) / 100;

                            $request->request->set('valor', $valorSalvar);
                            $request->request->set('encargo_id', $encargo_id);

                            $objLancamento = new Lancamento();
                            $objLancamento->contratoterceirizado_id = $idContratoTerceirizado;
                            $objLancamento->encargo_id = $encargo_id;
                            $objLancamento->valor = $valorSalvar;
                            $objLancamento->movimentacao_id = $idMovimentacao;
                            if( !$objLancamento->save() ){
                                $mensagem = 'Erro ao salvar o lançamento.';
                                \Alert::error($mensagem)->flash();
                                if( !self::excluirMovimentacao($idMovimentacao) ){
                                    \Alert::error('Problemas ao excluir a movimentação.')->flash();
                                }
                                return redirect()->back();
                            } else {
                                $quantidadeLancamentosGerados ++;
                            }
                            // fim transformar em método
                        }
                    }
                    self::salvarNovoSalario($idContratoTerceirizado, $novoSalario);
                }
            }
            // precisamos finalizar a última movimentação
            self::alterarStatusMovimentacao($idMovimentacao, 'Movimentação Finalizada');
            // vamos verificar se alguma movimentação foi gerada sem nenhum lançamento - excluí-la se for o caso.
            self::verificarNecessidadeDeExcluirMovimentacao($arrayIdsMovimentacoesGeradas);

            // your additional operations before save here
            $redirect_location = parent::storeCrud($request);
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry

        } else {
            // aqui nenhum contrato terceirizado foi encontrado.
            \Alert::error('Nenhum contrato terceirizado foi encontrado.')->flash();
        }
        $linkLocation = '/gescon/contrato/contratoconta/'.$idContratoConta.'/movimentacaocontratoconta';
        return redirect($linkLocation);
    }
    public function verificarNecessidadeDeExcluirMovimentacao($arrayIdsMovimentacoesGeradas){
        foreach( $arrayIdsMovimentacoesGeradas as $idMovimentacaoVerificar ){
            $excluir = false;
            // se o valor total gerado para a movimentação for zero, deverá ser excluída.
            if( !self::verificarSeExitemValoresLancadosParaEstaMovimentacao($idMovimentacaoVerificar) ){$excluir = true;}
            // vamos verificar se é necessário excluir a movimentação
            if($excluir){self::excluirMovimentacao($idMovimentacaoVerificar);}
        }
        return true;
    }
    public function verificarSeExitemValoresLancadosParaEstaMovimentacao($idMovimentacao){
        $objLancamento = new Lancamento();
        if($objLancamento->getValorTotalLancamentosByIdMovimentacao($idMovimentacao) == 0){
            return false;
        }
        return true;
    }
    public function excluirMovimentacao($idMovimentacao){
        if($objMovimentacaocontratoconta = Movimentacaocontratoconta::where('id','=',$idMovimentacao)->delete()){return true;}
        else{return false;}
    }
    public function getTodosLancamentosDepositoByIdContratoTerceirizado($idContratoTerceirizado){
        $array = \DB::table('lancamentos as l')
        // ->select('encargos.*', 'codigoitens.descricao')
        ->join('movimentacaocontratocontas as m', 'm.id', '=', 'l.movimentacao_id')
        ->join('codigoitens as c', 'c.id', '=', 'm.tipo_id')
        ->where('l.contratoterceirizado_id', '=', $idContratoTerceirizado)
        ->where('c.descricao', '=', 'Depósito')
        ->get();
        return $array;
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
