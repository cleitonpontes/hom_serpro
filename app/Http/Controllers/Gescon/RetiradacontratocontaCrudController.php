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

        $contrato_id = $objContratoTerceirizado->contrato_id;
        $objContratoConta = Contratoconta::where('contrato_id','=',$contrato_id)->first();
        $contratoconta_id = $objContratoConta->id;
        \Route::current()->setParameter('contratoconta_id', $contratoconta_id);

        // buscar os tipos de movimentação em codigoitens para seleção
        $objTipoMovimentacaoRetirada = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo Movimentação');
        })
        ->where('descricao', '=', 'Liberação')  //inicialmente era tratada como retirada.
        ->first();
        $idTipoMovimentacaoRetirada = $objTipoMovimentacaoRetirada->id;
        $objRetiradacontratoconta = new Retiradacontratoconta();
        $arrayObjetosEncargoParaCombo = $objRetiradacontratoconta->getEncargosParaCombo();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Retiradacontratoconta');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/contratoconta/contratoterceirizado/'.$contratoterceirizado_id.'/retiradacontratoconta');
        $this->crud->setEntityNameStrings('nova liberação', 'Liberação');
        $this->crud->enableExportButtons();

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
        $campos = $this->Campos($objContratoTerceirizado, $arrayObjetosEncargo, $idTipoMovimentacaoRetirada, $nomeFuncaoContratoTerceirizado, $arrayObjetosEncargoParaCombo, $objContratoConta);
        $this->crud->addFields($campos);
        // add asterisk for fields that are required in RetiradacontratocontaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Campos($objContratoTerceirizado, $arrayObjetosEncargo, $idTipoMovimentacaoRetirada, $nomeFuncaoContratoTerceirizado, $arrayObjetosEncargoParaCombo, $objContratoConta){
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
                'label' => 'Nome do empregado',
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
                'label' => 'Função do empregado',
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
                'label' => 'Remuneração do empregado',
                'type' => 'text',
                // optionals
                'attributes' => [
                    'readonly' => 'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ], // allow decimals
                'default' => $objContratoTerceirizado->salario,
            ],
            [   //
                'name' => 'fat_empresa',
                'label' => 'Encargo',
                'type' => 'text',
                // optionals
                'attributes' => [
                    'readonly' => 'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ], // allow decimals
                'default' => $objContratoConta->fat_empresa,
                'prefix' => "%",
            ],
            [   //
                'name' => 'percentual_submodulo22',
                'label' => 'Submódulo 2.2',
                'type' => 'text',
                // optionals
                'attributes' => [
                    'readonly' => 'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ], // allow decimals
                'default' => $objContratoConta->percentual_submodulo22,
                'prefix' => "%",
            ],

            [   // Hidden
                'name' => 'situacao_movimentacao',
                'type' => 'hidden',
                'default' => 'Movimentação Criada',
            ],

            [ // select_from_array
                'name' => 'mes_competencia',
                'label' => "Mês Liberação",
                'type' => 'select2_from_array',
                'options' => config('app.meses_referencia_fatura'), // vai buscar em app.php o array meses_referencia_fatura
                'allows_null' => false,
            ],
            [ // select_from_array
                'name' => 'ano_competencia',
                'label' => "Ano Liberação",
                'type' => 'select2_from_array',
                'options' => config('app.anos_referencia_fatura'), // vai buscar em app.php o array anos_referencia_fatura
                'default' => date('Y'),
                'allows_null' => false,
            ],
            [ // select_from_array
                'name' => 'situacao_retirada',
                'label' => "Situação da Liberação",
                // 'type' => 'select2_from_array',
                'type' => 'select2_from_array_hidden_field',    //  tipo criado para possibilitar uso do jquery para esconder campos
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
                ],
                'prefix' => "R$",
            ],
            [
                'name' => 'data_demissao',
                'label' => "Data da demissão",
                'type' => 'date',
                'default' => $objContratoTerceirizado->data_fim,
                'allows_null' => false,
                // optionals
                'attributes' => [
                    'id' => 'data_demissao',
                    'readonly' => 'readonly',
                ],
            ],
            [   // Number
                'name' => 'valor_13_salario_para_demissao',
                'label' => 'Valor do 13o. salário para demissão',
                'type' => 'money_fatura',
                // optionals
                'attributes' => [
                    'id' => 'valor_13_salario_para_demissao',
                    'readonly' => 'readonly',
                ],
                'prefix' => "R$",
            ],
            [   // Number
                'name' => 'valor_ferias_para_demissao',
                'label' => 'Valor férias + 1/3 de férias para demissão',
                'type' => 'money_fatura',
                // optionals
                'attributes' => [
                    'id' => 'valor_ferias_para_demissao',
                    'readonly' => 'readonly',
                ],
                'prefix' => "R$",
            ],
            [   // Number
                'name' => 'valor_multa_demissao',
                'label' => 'Valor da multa caso a demissão não seja por justa causa',
                'type' => 'money_fatura',
                // optionals
                'attributes' => [
                    'id' => 'valor_multa_demissao',
                    'readonly' => 'readonly',
                ],
                'prefix' => "R$",
            ],

        ];

        // vamos gerar os campos com os valores dos saldos
        $saldoTotal = 0;
        foreach( $arrayObjetosEncargo as $objEncargo ){
            $nomeEncargo = $objEncargo->descricao;
            $tipoId = $objEncargo->tipo_id;
            $objContratoConta = new Contratoconta();

            $saldoEncargoContratoTerceirizado = $objContratoConta->getSaldoContratoContaPorTipoEncargoPorContratoTerceirizado($objContratoTerceirizado->id, $tipoId);
            $saldoEncargoContratoTerceirizado = number_format($saldoEncargoContratoTerceirizado, 2, '.', ',' );

            $saldoDepositoEncargoContratoTerceirizado = $objContratoConta->getSaldoDepositoPorTipoEncargoPorContratoTerceirizado($objContratoTerceirizado->id, $tipoId);
            $saldoDepositoEncargoContratoTerceirizado = number_format($saldoDepositoEncargoContratoTerceirizado, 2, '.', ',' );

            $saldoRetiradaEncargoContratoTerceirizado = $objContratoConta->getSaldoRetiradaPorTipoEncargoPorContratoTerceirizado($objContratoTerceirizado->id, $tipoId);
            $saldoRetiradaEncargoContratoTerceirizado = number_format($saldoRetiradaEncargoContratoTerceirizado, 2, '.', ',' );

            $campos[] = [   //
                'name' => $nomeEncargo,
                'label' => 'Saldo '.$nomeEncargo,
                'type' => 'text',
                // optionals
                'attributes' => [
                    'readonly' => 'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ],
                'default' => $saldoEncargoContratoTerceirizado,
            ];
            // vamos gerando o saldo total para mostrarmos no formulário
            $saldoTotal = $saldoTotal + $saldoEncargoContratoTerceirizado;
        }
        // vamos mostrar o campo com o saldo total
        $campos[] = [   //
            'name' => 'Saldo Total',
            'label' => 'Saldo Total',
            'type' => 'text',
            // optionals
            'attributes' => [
                'readonly' => 'readonly',
                'style' => 'pointer-events: none;touch-action: none;'
            ],
            'default' => $saldoTotal,
        ];
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
        $objContratoConta = new Contratoconta();
        return $idContratoConta = $objContratoConta->getIdContratoContaByIdContratoTerceirizado($idContratoTerceirizado);
    }
    public function criarMovimentacao($request){
        $objMovimentacaocontratoconta = new Movimentacaocontratoconta();
        if($id = $objMovimentacaocontratoconta->criarMovimentacao($request)){
            return $id;
        }
        return false;
    }
    public function excluirMovimentacao($idMovimentacao){
        $objMovimentacaocontratoconta = new Movimentacaocontratoconta();
        if($id = $objMovimentacaocontratoconta->excluirMovimentacao($idMovimentacao)){
            return true;
        }
        return false;
    }
    public function alterarStatusMovimentacao($idMovimentacao, $statusMovimentacao){
        $objMovimentacao = new Movimentacaocontratoconta();
        if($objMovimentacao->alterarStatusMovimentacao($idMovimentacao, $statusMovimentacao)){
            return true;
        }
        return false;
    }
    // verificar se no ano/mês de competência, o funcionário já tinha iniciado.
    public function verificarSeCompetenciaECompativelComDataInicio($request, $objContratoTerceirizado){
        $mesCompetencia = $request->input('mes_competencia');
        $anoCompetencia = $request->input('ano_competencia');

        $dataInicio = $objContratoTerceirizado->data_inicio;
        $dataFim = $objContratoTerceirizado->data_fim;

        $mesDataInicio = substr($dataInicio, 5, 2);
        $anoDataInicio = substr($dataInicio, 0, 4);
        $diaDataInicio = substr($dataInicio, 8, 2);
        $mesDataFim = substr($dataFim, 5, 2);
        $anoDataFim = substr($dataFim, 0, 4);
        $diaDataFim = substr($dataFim, 8, 2);
        if( $anoCompetencia < $anoDataInicio ){
            return false;
        }
        if( $anoCompetencia == $anoDataInicio  && $mesCompetencia < $mesDataInicio){
            return false;
        }
        return true;
    }
    /**
     * Após reunião com o Gabriel, em 04/2021, ficou acertado que o percentual do grupo A, não seria mais armazenado nos encargos e
     * sim na tabela contratoconta, pois esse percentual irá variar de conta para conta.
     */
    public function getIdEncargoByNomeEncargo($nomeEncargo){
        // bucar em codigoitens, pela descrição, pegar o id e buscar o tipo id em encargos pelo id
        $obj = \DB::table('codigoitens')
        ->select('encargos.id')
        ->where('codigoitens.descricao','=',$nomeEncargo)
        ->join('encargos', 'encargos.tipo_id', '=', 'codigoitens.id')
        ->first();
        if( !is_object($obj) ){
            echo $nomeEncargo.' -> Encargo não localizado.';
            exit;
        }
        return $obj->id;
    }
    public function getTipoIdEncargoByNomeEncargo($nomeEncargo){
        // bucar em codigoitens, pela descrição, pegar o id e buscar o tipo id em encargos pelo id
        return $tipoId = \DB::table('codigoitens')
        ->select('encargos.tipo_id')
        ->where('codigoitens.descricao','=',$nomeEncargo)
        ->join('encargos', 'encargos.tipo_id', '=', 'codigoitens.id')
        ->first()->tipo_id;
    }
    public function verificarSeValorRetiradaEstaDentroDoPermitidoEGerarLancamentos($valor_13_salario_para_demissao, $valor_ferias_para_demissao, $valor_multa_demissao, $valorInformadoRetirada, $objContratoTerceirizado, $request, $idMovimentacao, $situacaoRetirada, $dataDemissao){
        // vamos buscar o saldo do encargo grupo A sobre 13 salario e férias
        $idContratoTerceirizado = $objContratoTerceirizado->id;
        $idContratoConta = $request->input('contratoconta_id');
        $objContratoConta = Contratoconta::find($idContratoConta);
        $percentualSubmodulo22 = $objContratoConta->percentual_submodulo22;
        $valorTotalIncidenciaSubmodulo22 = 0;
        /**
         * Após reunião com Gabriel, em 04/2021, ficou acertado que o percentual do grupo A, não seria mais armazenado nos encargos e sim
         * na tabela contrato conta, pois esse percentual irá variar de conta pra conta.
         *
         * Por isso o idEncargoGrupoA será null.
         *
         */
        // $nomeGrupoA = 'Incidência do Submódulo 2.2 sobre férias, 1/3 (um terço) constitucional de férias e 13o (décimo terceiro) salário';
        $idEncargoGrupoA = null;
        $saldoEncargoGrupoA = $objContratoConta->getSaldoContratoContaGrupoAPorContratoTerceirizado($idContratoTerceirizado);
        $situacaoFuncionario = $objContratoTerceirizado->situacao;
        // Grupo A - vamos calcular o Grupo A, que é o percentual fat_empresa sobre o valor informado para retirada.
            /**
             * Após reunião com o Gabriel, foi solicitado que:
             * para uma liberação, deverá incidir o percentual do submódulo 2.2
             *
             * Então, o que vou fazer é o seguinte:
             * ao invés de pegar o fat_empresa, que é o valor de 1, 2 ou 3%, vou pegar o percentual submódulo 22
             */
            // $fat_empresa = $request->input('fat_empresa');  // Cáculo do grupo A, que é o fat_empresa da tab contratocontas
            $fat_empresa = $objContratoConta->percentual_submodulo22;   // Cáculo do grupo A, que agora é o percentual do submódulo 2.2. Antes era o fat_empresa da tab contratocontas

        $valorFatEmpresaGrupoA = ( $valorInformadoRetirada * $fat_empresa ) / 100 ;
        // vamos atualizar o valor da retirada, somando com o percentual do fat_empresa, que é o grupo A
        $valorRetirada = ( $valorInformadoRetirada + $valorFatEmpresaGrupoA );
        // vamos verificar quanto o funcionário tem de saldo para o encargo informado.
        $salario = $objContratoTerceirizado->salario;
        $umTercoSalario = ( $salario / 3 );
        if($situacaoRetirada=='Demissão'){
            /**
             * NOVA FORMA DE DEMISSÃO!!!
             *
             * 30/04/2021 - Após conversa com Gabriel por whatsapp, ficou decidido que a demissão funcionará da seguinte forma:
             *
             * 1 - usuário informa o valor da liberação;
             * 2 - sistema usa esse valor pra fazer liberações em cima das provisões existentes, seguindo a seguinte ordem:
             *      - 13o.
             *      - incidência módulo 2.2
             *      - férias e 1/3
             *      - multa sobre FGTS por último
             *
             */
            // aqui o usuário informou que a retirada é para demissão
            // verificar se o funcionário já não é demitido
            if( !$situacaoFuncionario ){
                $mensagem = 'Este empregado já está demitido.';
                \Alert::error($mensagem)->flash();
                return false;
            }
            // verificar se informou a data de demissão
            $dataDemissao = $request->input('data_demissao');
            if( $dataDemissao=='' ){
                $mensagem = 'Favor informar a data de demissão.';
                \Alert::error($mensagem)->flash();
                return false;
            }
            /**
             * Com a nova regra de demissão, precisaremos calcular a incidência do submódulo 2.2 sobre 13o. e férias
             */
            // buscar os saldos dos encargos e gerar um lançamento de retirada pra cada.
            $nomeEncargo13ParaDemissao = '13º (décimo terceiro) salário';
            $idEncargo13ParaDemissao = self::getIdEncargoByNomeEncargo($nomeEncargo13ParaDemissao);
            $saldoDecimoTerceiroParaDemissao = $objContratoConta->getSaldoContratoContaPorIdEncargoPorContratoTerceirizado($idContratoTerceirizado, $idEncargo13ParaDemissao);
            // vamos verificar se o saldo é maior ou igual ao valor informado
            if($saldoDecimoTerceiroParaDemissao < $valor_13_salario_para_demissao){
                $mensagem = 'O saldo para 13o. é menor do que o valor informado.';
                \Alert::error($mensagem)->flash();
                return false;
            } else {
                // com a nova regra de demissão, o valor que deverá ser lançado, não é mais o valor do saldo e sim o valor informado.
                $saldoDecimoTerceiroParaDemissao = $valor_13_salario_para_demissao;
                // aqui vamos calcular a incidência do submódulo 2.2
                $valorIncidenciaSubmodulo22_13 = ( ($valor_13_salario_para_demissao * $percentualSubmodulo22)/100 );
                // echo '<br>valor 13 = '.$valor_13_salario_para_demissao.' sub 13 = '.$valorIncidenciaSubmodulo22_13;
            }
            $nomeEncargoFeriasParaDemissao = 'Férias e 1/3 (um terço) constitucional de férias';
            $idEncargoFeriasParaDemissao = self::getIdEncargoByNomeEncargo($nomeEncargoFeriasParaDemissao);
            $saldoFeriasParaDemissao = $objContratoConta->getSaldoContratoContaPorIdEncargoPorContratoTerceirizado($idContratoTerceirizado, $idEncargoFeriasParaDemissao);
            // vamos verificar se o saldo é maior ou igual ao valor informado
            if($saldoFeriasParaDemissao < $valor_ferias_para_demissao){
                $mensagem = 'O saldo para férias é menor do que o valor informado.';
                \Alert::error($mensagem)->flash();
                return false;
            } else {
                // com a nova regra de demissão, o valor que deverá ser lançado, não é mais o valor do saldo e sim o valor informado.
                $saldoFeriasParaDemissao = $valor_ferias_para_demissao;
                // aqui vamos calcular a incidência do submódulo 2.2
                $valorIncidenciaSubmodulo22_ferias = ( ($valor_ferias_para_demissao * $percentualSubmodulo22)/100 );
                // echo '<br>valor ferias = '.$valor_ferias_para_demissao.' sub ferias = '.$valorIncidenciaSubmodulo22_ferias;
            }
            $nomeEncargoRescisaoParaDemissao = 'Multa sobre o FGTS para as rescisões sem justa causa';
            $idEncargoRescisaoParaDemissao = self::getIdEncargoByNomeEncargo($nomeEncargoRescisaoParaDemissao);
            $saldoRescisaoParaDemissao = $objContratoConta->getSaldoContratoContaPorIdEncargoPorContratoTerceirizado($idContratoTerceirizado, $idEncargoRescisaoParaDemissao);
            // vamos verificar se o saldo é maior ou igual ao valor informado
            if($saldoRescisaoParaDemissao < $valor_multa_demissao){
                $mensagem = 'O saldo para multa é menor do que o valor informado.';
                \Alert::error($mensagem)->flash();
                return false;
            } else {
                // com a nova regra de demissão, o valor que deverá ser lançado, não é mais o valor do saldo e sim o valor informado.
                $saldoRescisaoParaDemissao = $valor_multa_demissao;
            }
            // aqui já temos todos os valores de incidência do submódulo 2.2. Vamos somá-los
            $valorTotalIncidenciaSubmodulo22 = ($valorIncidenciaSubmodulo22_13 + $valorIncidenciaSubmodulo22_ferias );
            /**
             * Após reunião com Gabriel, em 04/2021, ficou acertado que o percentual do grupo A, não seria mais armazenado nos encargos e sim
             * na tabela contrato conta, pois esse percentual irá variar de conta pra conta.
             *
             * Por isso o idEncargoGrupoAParaDemissao será null.
             *
             */
            // $nomeEncargoGrupoAParaDemissao = 'Incidência do Submódulo 2.2 sobre férias, 1/3 (um terço) constitucional de férias e 13o (décimo terceiro) salário';
            $idEncargoGrupoAParaDemissao = null;
            $saldoGrupoAParaDemissao = $objContratoConta->getSaldoContratoContaGrupoAPorContratoTerceirizado($idContratoTerceirizado);
            if($saldoGrupoAParaDemissao < $valorTotalIncidenciaSubmodulo22){
                $mensagem = 'O saldo para o submódulo 2.2 ('.$saldoGrupoAParaDemissao.') é menor do que o valor calculado ('.$valorTotalIncidenciaSubmodulo22.').';
                \Alert::error($mensagem)->flash();
                return false;
            } else {
                // com a nova regra de demissão, o valor a ser lançado não é mais o saldo e sim o valor calculado em cima dos valores informados para férias e 13.
                $saldoGrupoAParaDemissao = $valorTotalIncidenciaSubmodulo22;
            }
            // $valorMaximoRetirada = ( $saldoDecimoTerceiroParaDemissao + $saldoFeriasParaDemissao + $saldoRescisaoParaDemissao + $saldoGrupoAParaDemissao );
            // $valorRetirada = $valorMaximoRetirada;
            // if($valorMaximoRetirada == 0){
            //     $mensagem = 'Não existe saldo para retirada.';
            //     \Alert::error($mensagem)->flash();
            //     return false;
            // }
            if($saldoDecimoTerceiroParaDemissao>0){
                // lançamento para o 13o.
                $objLancamento = new Lancamento();
                $objLancamento->contratoterceirizado_id = $idContratoTerceirizado;
                $objLancamento->encargo_id = $idEncargo13ParaDemissao;
                $objLancamento->valor = $saldoDecimoTerceiroParaDemissao;
                $objLancamento->movimentacao_id = $idMovimentacao;
                if( !$objLancamento->save() ){
                    $mensagem = 'Erro ao salvar o lançamento para Décimo Terceiro.';
                    \Alert::error($mensagem)->flash();
                    return false;
                }
            }
            if($saldoGrupoAParaDemissao>0){
                // lançamento para grupo A sobre 13 e férias
                $objLancamento = new Lancamento();
                $objLancamento->contratoterceirizado_id = $idContratoTerceirizado;
                $objLancamento->encargo_id = $idEncargoGrupoAParaDemissao;
                $objLancamento->valor = $saldoGrupoAParaDemissao;
                $objLancamento->movimentacao_id = $idMovimentacao;
                if( !$objLancamento->save() ){
                    $mensagem = 'Erro ao salvar o lançamento para grupo A sobre Décimo Terceiro e Férias.';
                    \Alert::error($mensagem)->flash();
                    return false;
                }
            }
            if($saldoFeriasParaDemissao>0){
                // lançamento para férias
                $objLancamento = new Lancamento();
                $objLancamento->contratoterceirizado_id = $idContratoTerceirizado;
                $objLancamento->encargo_id = $idEncargoFeriasParaDemissao;
                $objLancamento->valor = $saldoFeriasParaDemissao;
                $objLancamento->movimentacao_id = $idMovimentacao;
                if( !$objLancamento->save() ){
                    $mensagem = 'Erro ao salvar o lançamento para férias.';
                    \Alert::error($mensagem)->flash();
                    return false;
                }
            }
            if($saldoRescisaoParaDemissao>0){
                // lançamento para rescisão e adicional fgts
                $objLancamento = new Lancamento();
                $objLancamento->contratoterceirizado_id = $idContratoTerceirizado;
                $objLancamento->encargo_id = $idEncargoRescisaoParaDemissao;
                $objLancamento->valor = $saldoRescisaoParaDemissao;
                $objLancamento->movimentacao_id = $idMovimentacao;
                if( !$objLancamento->save() ){
                    $mensagem = 'Erro ao salvar o lançamento para rescisão e adicional fgts.';
                    \Alert::error($mensagem)->flash();
                    return false;
                }
            }
            // vamos chamar o método que altera a situação do funcionário para demitido.
            if( !$objContratoConta->alterarSituacaoFuncionárioParaDemitido($idContratoTerceirizado, $dataDemissao) ){
                $mensagem = 'Erro ao alterar a situação do funcioário para demitido.';
                \Alert::error($mensagem)->flash();
                return false;
            }
        } elseif($situacaoRetirada=='DemissãoBackup'){

            /**
             * BACKUP
             * COMO ERA ANTES DA ALTERAÇÃO PRA NOVA FORMA DE DEMISSÃO.
             *
             * BASTA VOLTAR O IF PARA DEMISSAO E RETIRAR COMENTAR O OUTRO IF DE DEMISSAO
             */
            /**
             * 30/04/2021 - Após conversa com Gabriel por whatsapp, ficou decidido que a demissão funcionará da seguinte forma:
             *
             * 1 - usuário informa o valor da liberação;
             * 2 - sistema usa esse valor pra fazer liberações em cima das provisões existentes, seguindo a seguinte ordem:
             *      - 13o.
             *      - incidência módulo 2.2
             *      - férias e 1/3
             *      - multa sobre FGTS por último
             *
             */
            // aqui o usuário informou que a retirada é para demissão
            // verificar se o funcionário já não é demitido
            if( !$situacaoFuncionario ){
                $mensagem = 'Este empregado já está demitido.';
                \Alert::error($mensagem)->flash();
                return false;
            }
            // verificar se informou a data de demissão
            $dataDemissao = $request->input('data_demissao');
            if( $dataDemissao=='' ){
                $mensagem = 'Favor informar a data de demissão.';
                \Alert::error($mensagem)->flash();
                return false;
            }



            // buscar os saldos dos encargos e gerar um lançamento de retirada pra cada.
            $nomeEncargo13ParaDemissao = '13º (décimo terceiro) salário';
            $idEncargo13ParaDemissao = self::getIdEncargoByNomeEncargo($nomeEncargo13ParaDemissao);
            $saldoDecimoTerceiroParaDemissao = $objContratoConta->getSaldoContratoContaPorIdEncargoPorContratoTerceirizado($idContratoTerceirizado, $idEncargo13ParaDemissao);

            $nomeEncargoFeriasParaDemissao = 'Férias e 1/3 (um terço) constitucional de férias';
            $idEncargoFeriasParaDemissao = self::getIdEncargoByNomeEncargo($nomeEncargoFeriasParaDemissao);
            $saldoFeriasParaDemissao = $objContratoConta->getSaldoContratoContaPorIdEncargoPorContratoTerceirizado($idContratoTerceirizado, $idEncargoFeriasParaDemissao);

            $nomeEncargoRescisaoParaDemissao = 'Multa sobre o FGTS para as rescisões sem justa causa';
            $idEncargoRescisaoParaDemissao = self::getIdEncargoByNomeEncargo($nomeEncargoRescisaoParaDemissao);
            $saldoRescisaoParaDemissao = $objContratoConta->getSaldoContratoContaPorIdEncargoPorContratoTerceirizado($idContratoTerceirizado, $idEncargoRescisaoParaDemissao);
            /**
             * Após reunião com Gabriel, em 04/2021, ficou acertado que o percentual do grupo A, não seria mais armazenado nos encargos e sim
             * na tabela contrato conta, pois esse percentual irá variar de conta pra conta.
             *
             * Por isso o idEncargoGrupoAParaDemissao será null.
             *
             */
            // $nomeEncargoGrupoAParaDemissao = 'Incidência do Submódulo 2.2 sobre férias, 1/3 (um terço) constitucional de férias e 13o (décimo terceiro) salário';
            $idEncargoGrupoAParaDemissao = null;
            $saldoGrupoAParaDemissao = $objContratoConta->getSaldoContratoContaGrupoAPorContratoTerceirizado($idContratoTerceirizado);

            $valorMaximoRetirada = ( $saldoDecimoTerceiroParaDemissao + $saldoFeriasParaDemissao + $saldoRescisaoParaDemissao + $saldoGrupoAParaDemissao );
            $valorRetirada = $valorMaximoRetirada;
            if($valorMaximoRetirada == 0){
                $mensagem = 'Não existe saldo para retirada.';
                \Alert::error($mensagem)->flash();
                return false;
            }

            if($saldoDecimoTerceiroParaDemissao>0){
                // lançamento para o 13o.
                $objLancamento = new Lancamento();
                $objLancamento->contratoterceirizado_id = $idContratoTerceirizado;
                $objLancamento->encargo_id = $idEncargo13ParaDemissao;
                $objLancamento->valor = $saldoDecimoTerceiroParaDemissao;
                $objLancamento->movimentacao_id = $idMovimentacao;
                if( !$objLancamento->save() ){
                    $mensagem = 'Erro ao salvar o lançamento para Décimo Terceiro.';
                    \Alert::error($mensagem)->flash();
                    return false;
                }
            }
            if($saldoGrupoAParaDemissao>0){
                // lançamento para grupo A sobre 13 e férias
                $objLancamento = new Lancamento();
                $objLancamento->contratoterceirizado_id = $idContratoTerceirizado;
                $objLancamento->encargo_id = $idEncargoGrupoAParaDemissao;
                $objLancamento->valor = $saldoGrupoAParaDemissao;
                $objLancamento->movimentacao_id = $idMovimentacao;
                if( !$objLancamento->save() ){
                    $mensagem = 'Erro ao salvar o lançamento para grupo A sobre Décimo Terceiro e Férias.';
                    \Alert::error($mensagem)->flash();
                    return false;
                }
            }
            if($saldoFeriasParaDemissao>0){
                // lançamento para férias
                $objLancamento = new Lancamento();
                $objLancamento->contratoterceirizado_id = $idContratoTerceirizado;
                $objLancamento->encargo_id = $idEncargoFeriasParaDemissao;
                $objLancamento->valor = $saldoFeriasParaDemissao;
                $objLancamento->movimentacao_id = $idMovimentacao;
                if( !$objLancamento->save() ){
                    $mensagem = 'Erro ao salvar o lançamento para férias.';
                    \Alert::error($mensagem)->flash();
                    return false;
                }
            }
            if($saldoRescisaoParaDemissao>0){
                // lançamento para rescisão e adicional fgts
                $objLancamento = new Lancamento();
                $objLancamento->contratoterceirizado_id = $idContratoTerceirizado;
                $objLancamento->encargo_id = $idEncargoRescisaoParaDemissao;
                $objLancamento->valor = $saldoRescisaoParaDemissao;
                $objLancamento->movimentacao_id = $idMovimentacao;
                if( !$objLancamento->save() ){
                    $mensagem = 'Erro ao salvar o lançamento para rescisão e adicional fgts.';
                    \Alert::error($mensagem)->flash();
                    return false;
                }
            }





            // vamos chamar o método que altera a situação do funcionário para demitido.
            if( !$objContratoConta->alterarSituacaoFuncionárioParaDemitido($idContratoTerceirizado, $dataDemissao) ){
                $mensagem = 'Erro ao alterar a situação do funcioário para demitido.';
                \Alert::error($mensagem)->flash();
                return false;
            }
        } else {
            // aqui o usuário informou que a retirada não é para demissão
            $nomeEncargoInformado = self::getNomeEncargoBySituacaoRetirada($situacaoRetirada);
            $idEncargoInformado = self::getIdEncargoByNomeEncargo($nomeEncargoInformado);
            $tipoIdEncargo = $objContratoConta->getTipoIdEncargoByIdEncargo($idEncargoInformado);
            // vamos verificar quanto tem de saldo para o encargo em questão.
            $saldoContratoContaPorTipoEncargo = $objContratoConta->getSaldoContratoContaPorTipoEncargoPorContratoTerceirizado($idContratoTerceirizado, $tipoIdEncargo);
            // início das verificações por encargo
            $valorMaximoRetirada = 0; // inicializar o valor máximo para retirada, que será alterado de acordo com o encargo informado.
            if( $nomeEncargoInformado == '13º (décimo terceiro) salário' ){
                // para 13o. salário, retirada máxima = ( salário + grupo A )
                $valorMaximoRetirada = ( $salario + $valorFatEmpresaGrupoA );
            } elseif( $nomeEncargoInformado == 'Férias e 1/3 (um terço) constitucional de férias' ){
                // para Férias, retirada máxima = ( salário + 1/3 do salário + grupo A)
                $valorMaximoRetirada = ( $salario + $valorFatEmpresaGrupoA + $umTercoSalario);
            }
            // fim das verificações por encargo
            // vamos verificar se o valor do fat empresa (grupo A) não é maior do que o saldo do grupo A (encargo)
            if($valorFatEmpresaGrupoA > $saldoEncargoGrupoA){
                \Alert::error('O valor calculado para o Grupo A é maior do que o saldo do encargo Grupo A.')->flash();
                return false;
            }
            // vamos verificar se o valor informado não é maior que o saldo para o encargo informado
            if( $valorInformadoRetirada > $saldoContratoContaPorTipoEncargo ){
                \Alert::error('O valor informado é maior do que o saldo do encargo.')->flash();
                return false;
            }
            // agora que já calculamos o valor máximo para retirada, pelo encargo informado, vamos verificar se o valor informado é possível.
            if( $valorMaximoRetirada < $valorRetirada ){
                \Alert::error('O valor da retirada supera o valor máximo permitido.')->flash();
                return false;
            }
            // gerar o lançamento para o encargo informado
            $objLancamento = new Lancamento();
            $objLancamento->contratoterceirizado_id = $idContratoTerceirizado;
            $objLancamento->encargo_id = $idEncargoInformado;
            $objLancamento->valor = $valorInformadoRetirada;
            $objLancamento->movimentacao_id = $idMovimentacao;
            if( !$objLancamento->save() ){
                $mensagem = 'Erro ao salvar o lançamento.';
                \Alert::error($mensagem)->flash();
                return false;
            }
            // Aqui vamos controlar os demais lançamentos, além do encargo selecionado pelo usuário
            if( $nomeEncargoInformado == '13º (décimo terceiro) salário' ){
                // GRUPO A
                // para 13 é necessário gerar lançamento para o Grupo A
                $objLancamento = new Lancamento();
                $objLancamento->contratoterceirizado_id = $idContratoTerceirizado;
                $objLancamento->encargo_id = $idEncargoGrupoA;
                $objLancamento->valor = $valorFatEmpresaGrupoA;
                $objLancamento->movimentacao_id = $idMovimentacao;
                if( !$objLancamento->save() ){
                    $mensagem = 'Erro ao salvar o lançamento para o Grupo A.';
                    \Alert::error($mensagem)->flash();
                    return false;
                }
            } elseif( $nomeEncargoInformado == 'Férias e 1/3 (um terço) constitucional de férias' ){
                // GRUPO A
                // para férias é necessário gerar lançamento para o Grupo A
                $objLancamento = new Lancamento();
                $objLancamento->contratoterceirizado_id = $idContratoTerceirizado;
                $objLancamento->encargo_id = $idEncargoGrupoA;
                $objLancamento->valor = $valorFatEmpresaGrupoA;
                $objLancamento->movimentacao_id = $idMovimentacao;
                if( !$objLancamento->save() ){
                    $mensagem = 'Erro ao salvar o lançamento para o Grupo A.';
                    \Alert::error($mensagem)->flash();
                    return false;
                }
            }
        }
        return $valorRetirada;
    }
    public function getNomeEncargoBySituacaoRetirada($situacaoRetirada){
        if($situacaoRetirada=='Décimo Terceiro'){return '13º (décimo terceiro) salário';}
        elseif($situacaoRetirada=='Demissão'){return 'Demissão';}
        elseif($situacaoRetirada=='Férias'){return 'Férias e 1/3 (um terço) constitucional de férias';}
    }
    public function store(StoreRequest $request)
    {
        $idContratoTerceirizado = $request->input('contratoterceirizado_id');
        $objContratoTerceirizado = \DB::table('contratoterceirizados')
            ->select('contratoterceirizados.*', 'contratos.numero')
            ->join('contratos', 'contratos.id', '=', 'contratoterceirizados.contrato_id')
            ->where('contratoterceirizados.id', '=', $idContratoTerceirizado)
            ->first();
        $idContratoConta = self::getIdContratoContaByIdContratoTerceirizado($idContratoTerceirizado);
        $numeroContrato = $objContratoTerceirizado->numero;
        $user_id = backpack_user()->id;
        $request->request->set('user_id', $user_id);

        $valorRetirada = $request->input('valor');
        $valorRetirada = str_replace('.', '', $valorRetirada);
        $valorRetirada = str_replace(',', '.', $valorRetirada);
        /**
         * Novos campos a partir da reunião com o Gabriel em 30/02/2021
         * onde foi mudada a forma de cálculo para demissão.
         */
        $valor_13_salario_para_demissao = $request->input('valor_13_salario_para_demissao');
        $valor_13_salario_para_demissao = str_replace('.', '', $valor_13_salario_para_demissao);
        $valor_13_salario_para_demissao = str_replace(',', '.', $valor_13_salario_para_demissao);

        $valor_ferias_para_demissao = $request->input('valor_ferias_para_demissao');
        $valor_ferias_para_demissao = str_replace('.', '', $valor_ferias_para_demissao);
        $valor_ferias_para_demissao = str_replace(',', '.', $valor_ferias_para_demissao);

        $valor_multa_demissao = $request->input('valor_multa_demissao');
        $valor_multa_demissao = str_replace('.', '', $valor_multa_demissao);
        $valor_multa_demissao = str_replace(',', '.', $valor_multa_demissao);

        $idContrato = $objContratoTerceirizado->contrato_id;
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
        // vamos verificar se no mês/ano de competência, o funcionário já tinha iniciado
        if(!self::verificarSeCompetenciaECompativelComDataInicio($request, $objContratoTerceirizado)){
            $mensagem = 'Para o contrato número '.$numeroContrato.' o mês / ano de competência são incompatíveis com mês / ano de início do empregado.';
            \Alert::error($mensagem)->flash();
            if( !self::excluirMovimentacao($idMovimentacao) ){
                \Alert::error('Problemas ao excluir a movimentação.')->flash();
            }
            return redirect()->back();
        }
        // vamos alterar o status da movimentação
        self::alterarStatusMovimentacao($idMovimentacao, 'Movimentação Em Andamento');
        // vamos verificar o saldo total da conta
        $objContratoConta = new Contratoconta();
        $saldoContratoConta = $objContratoConta->getSaldoContratoContaPorContratoTerceirizado($idContratoTerceirizado);
        // vamos verificar o saldo por encargo
        $situacaoRetirada = $request->input('situacao_retirada');
        $dataDemissao = $request->input('data_demissao');
        if( !$valorRetirada = self::verificarSeValorRetiradaEstaDentroDoPermitidoEGerarLancamentos($valor_13_salario_para_demissao, $valor_ferias_para_demissao, $valor_multa_demissao, $valorRetirada, $objContratoTerceirizado, $request, $idMovimentacao, $situacaoRetirada, $dataDemissao) ){
            // aqui quer dizer que não existe saldo para esta retirada - vamos excluir a movimentação
            self::excluirMovimentacao($idMovimentacao);
            \Alert::error('Problemas ao salvar a movimentação.')->flash();
            return redirect()->back();
        }
        // aqui os lançamentos já foram gerados. Vamos alterar o status da movimentação
        self::alterarStatusMovimentacao($idMovimentacao, 'Movimentação Finalizada');
        $mensagem = 'Lançamento de liberação gerado com sucesso!';
        \Alert::success($mensagem)->flash();
        $linkLocation = '/gescon/contrato/contratoconta/'.$idContratoConta.'/movimentacaocontratoconta';
        return redirect($linkLocation);
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
