<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;
class Retiradacontratoconta extends Model
{
    use CrudTrait;
    use LogsActivity;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'movimentacaocontratocontas';
    protected $fillable = [
        'contratoconta_id',
        'tipo_id',
        'mes_competencia',
        'ano_competencia',
        'valor_total_mes_ano',
        'situacao_movimentacao',
        'user_id'
    ];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getTipoMovimentacao(){
        $objCodigoItem = Codigoitem::find($this->tipo_id);
        return $descricao= $objCodigoItem->descricao;
    }
    public function formatValor(){
        return number_format($this->valor, 2, ',', '.');
    }
    public function getContratosTerceirizadosParaCombo($contrato_id){
        return $arrayContratosTerceirizados = Contratoterceirizado::where('contrato_id','=',$contrato_id)->pluck('nome', 'id')->toArray();
    }
    public function getEncargosParaCombo(){
        // Os dados da combo serão fixos - grupo A não entra.
        return $arrayObjetosEncargoParaCombo = array(
            'Décimo Terceiro' => 'Décimo Terceiro',
            'Demissão' => 'Demissão',
            'Férias' => 'Férias',
        );
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
    public function demitirContratoTerceirizado($request){
        $idContratoTerceirizado = $request->input('contratoterceirizado_id');
        $objContratoTerceirizado = \DB::table('contratoterceirizados')
            ->select('contratoterceirizados.*', 'contratos.numero')
            ->join('contratos', 'contratos.id', '=', 'contratoterceirizados.contrato_id')
            ->where('contratoterceirizados.id', '=', $idContratoTerceirizado)
            ->first();
        $idContratoConta = $request->input('contratoconta_id');
        $idContrato = $request->input('contrato_id');
        $numeroContrato = $objContratoTerceirizado->numero;
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
        $dataDemissao = $request->input('data_encerramento');
        $valorRetirada = self::verificarSeValorRetiradaEstaDentroDoPermitidoEGerarLancamentos(0, $objContratoTerceirizado, $request, $idMovimentacao, $situacaoRetirada, $dataDemissao);
        // aqui os lançamentos já foram gerados. Vamos alterar o status da movimentação
        self::alterarStatusMovimentacao($idMovimentacao, 'Movimentação Finalizada');
        return true;
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
    public function encerrarContaVinculada($request){
        $contrato_id = $request->input('contrato_id');
        // buscar todos os funcionários do contrato e para cada um, demitir.
        $arrayContratosTerceirizados = Contratoterceirizado::where('contrato_id','=',$contrato_id)
            ->join('contratos', 'contratos.id', '=', 'contratoterceirizados.contrato_id')
            ->select('contratoterceirizados.*', 'contratos.numero')
            ->get();
        // para cada funcionário, demitir
        $contDemissoes = 0;
        foreach($arrayContratosTerceirizados as $objContratoTerceirizadoDemitir){
            $situacaoFuncionario = $objContratoTerceirizadoDemitir->situacao;
            if( $situacaoFuncionario ){
                $contDemissoes++;
                $idContratoTerceirizado = $objContratoTerceirizadoDemitir->id;
                $request->request->set('contratoterceirizado_id', $idContratoTerceirizado);
                $this->demitirContratoTerceirizado($request);
            }
        }
        return true;
    }
    public function verificarSeValorRetiradaEstaDentroDoPermitidoEGerarLancamentos($valorInformadoRetirada, $objContratoTerceirizado, $request, $idMovimentacao, $situacaoRetirada, $dataDemissao){
        // vamos buscar o saldo do encargo grupo A sobre 13 salario e férias
        $idContratoTerceirizado = $objContratoTerceirizado->id;
        $objContratoConta = new Contratoconta();
        // $nomeGrupoA = 'Grupo "A" sobre 13o. Salário e Férias';
        $nomeGrupoA = 'Incidência do Submódulo 2.2 sobre férias, 1/3 (um terço) constitucional de férias e 13o (décimo terceiro) salário';
        $idEncargoGrupoA = Encargo::getIdEncargoByNomeEncargo($nomeGrupoA);
        $idGrupoA = Encargo::getIdEncargoByNomeEncargo($nomeGrupoA);
        $saldoEncargoGrupoA = $objContratoConta->getSaldoContratoContaPorTipoEncargoPorContratoTerceirizado($idContratoTerceirizado, $idGrupoA);
        $situacaoFuncionario = $objContratoTerceirizado->situacao;
        // Grupo A - vamos calcular o Grupo A, que é o percentual fat_empresa sobre o valor informado para retirada.
        $fat_empresa = $request->input('fat_empresa');  // Cáculo do grupo A, que é o fat_empresa da tab contratocontas
        $valorFatEmpresaGrupoA = ( $valorInformadoRetirada * $fat_empresa ) / 100 ;
        // vamos atualizar o valor da retirada, somando com o percentual do fat_empresa, que é o grupo A
        $valorRetirada = ( $valorInformadoRetirada + $valorFatEmpresaGrupoA );
        // vamos verificar quanto o funcionário tem de saldo para o encargo informado.
        $salario = $objContratoTerceirizado->salario;
        $umTercoSalario = ( $salario / 3 );
        if($situacaoRetirada=='Demissão'){
            // aqui o usuário informou que a retirada é para demissão
            // verificar se o funcionário já não é demitido
            if( !$situacaoFuncionario ){
                $mensagem = 'Este empregado já está demitido.';
                \Alert::error($mensagem)->flash();
                return false;
            }
            // verificar se informou a data de demissão
            if( $dataDemissao=='' ){
                $mensagem = 'Favor informar a data de demissão.';
                \Alert::error($mensagem)->flash();
                return false;
            }
            // buscar os saldos dos encargos e gerar um lançamento de retirada pra cada.
            $nomeEncargo13ParaDemissao = '13º (décimo terceiro) salário';
            $idEncargo13ParaDemissao = Encargo::getIdEncargoByNomeEncargo($nomeEncargo13ParaDemissao);
            $saldoDecimoTerceiroParaDemissao = $objContratoConta->getSaldoContratoContaPorIdEncargoPorContratoTerceirizado($idContratoTerceirizado, $idEncargo13ParaDemissao);
            $nomeEncargoFeriasParaDemissao = 'Férias e 1/3 (um terço) constitucional de férias';
            $idEncargoFeriasParaDemissao = Encargo::getIdEncargoByNomeEncargo($nomeEncargoFeriasParaDemissao);
            $saldoFeriasParaDemissao = $objContratoConta->getSaldoContratoContaPorIdEncargoPorContratoTerceirizado($idContratoTerceirizado, $idEncargoFeriasParaDemissao);
            $nomeEncargoRescisaoParaDemissao = 'Multa sobre o FGTS para as rescisões sem justa causa';
            $idEncargoRescisaoParaDemissao = Encargo::getIdEncargoByNomeEncargo($nomeEncargoRescisaoParaDemissao);
            $saldoRescisaoParaDemissao = $objContratoConta->getSaldoContratoContaPorIdEncargoPorContratoTerceirizado($idContratoTerceirizado, $idEncargoRescisaoParaDemissao);
            // $nomeEncargoGrupoAParaDemissao = 'Grupo "A" sobre 13o. Salário e Férias';
            $nomeEncargoGrupoAParaDemissao = 'Incidência do Submódulo 2.2 sobre férias, 1/3 (um terço) constitucional de férias e 13o (décimo terceiro) salário';
            $idEncargoGrupoAParaDemissao = Encargo::getIdEncargoByNomeEncargo($nomeEncargoGrupoAParaDemissao);
            $saldoGrupoAParaDemissao = $objContratoConta->getSaldoContratoContaPorIdEncargoPorContratoTerceirizado($idContratoTerceirizado, $idEncargoGrupoAParaDemissao);
            $valorMaximoRetirada = ( $saldoDecimoTerceiroParaDemissao + $saldoFeriasParaDemissao + $saldoRescisaoParaDemissao + $saldoGrupoAParaDemissao );
            $valorRetirada = $valorMaximoRetirada;
            if($valorMaximoRetirada == 0){
                $mensagem = 'Não existe saldo para retirada.';
                \Alert::error($mensagem)->flash();
                return false;
            }
            if($saldoDecimoTerceiroParaDemissao>0){
                // lançamento para o 13
                $objLancamento = new Lancamento();
                $objLancamento->contratoterceirizado_id = $idContratoTerceirizado;
                $objLancamento->encargo_id = $idEncargo13ParaDemissao;
                $objLancamento->valor = $saldoDecimoTerceiroParaDemissao;
                $objLancamento->movimentacao_id = $idMovimentacao;
                $objLancamento->salario_atual = $salario;   //após reunião com Gabriel em 10/05/2021
                if( !$objLancamento->save() ){
                    $mensagem = 'Erro ao salvar o lançamento para Décimo Terceiro.';
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
                $objLancamento->salario_atual = $salario;   //após reunião com Gabriel em 10/05/2021
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
                $objLancamento->salario_atual = $salario;   //após reunião com Gabriel em 10/05/2021
                if( !$objLancamento->save() ){
                    $mensagem = 'Erro ao salvar o lançamento para rescisão e adicional fgts.';
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
                $objLancamento->salario_atual = $salario;   //após reunião com Gabriel em 10/05/2021
                if( !$objLancamento->save() ){
                    $mensagem = 'Erro ao salvar o lançamento para grupo A sobre Décimo Terceiro e Férias.';
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
            $nomeEncargoInformado = Encargo::getNomeEncargoBySituacaoRetirada($situacaoRetirada);
            $idEncargoInformado = Encargo::getIdEncargoByNomeEncargo($nomeEncargoInformado);
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
            $objLancamento->salario_atual = $salario;   //após reunião com Gabriel em 10/05/2021
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
                $objLancamento->salario_atual = $salario;   //após reunião com Gabriel em 10/05/2021
                if( !$objLancamento->save() ){
                    $mensagem = 'Erro ao salvar o lançamento para o Grupo A.';
                    \Alert::error($mensagem)->flash();
                    return false;
                }
            } elseif( $nomeEncargoInformado == 'Férias e 1/3 (um terço) constitucional de férias' ){
                // GRUPO A
                // para 13 é necessário gerar lançamento para o Grupo A
                $objLancamento = new Lancamento();
                $objLancamento->contratoterceirizado_id = $idContratoTerceirizado;
                $objLancamento->encargo_id = $idEncargoGrupoA;
                $objLancamento->valor = $valorFatEmpresaGrupoA;
                $objLancamento->movimentacao_id = $idMovimentacao;
                $objLancamento->salario_atual = $salario;   //após reunião com Gabriel em 10/05/2021
                if( !$objLancamento->save() ){
                    $mensagem = 'Erro ao salvar o lançamento para o Grupo A.';
                    \Alert::error($mensagem)->flash();
                    return false;
                }
            }
        }
        // se chegou aqui é porque está tudo certo
        return $valorRetirada;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

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
}
