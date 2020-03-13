<?php

namespace App\Models;

use App\Http\Controllers\AdminController;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class MigracaoSistemaConta extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'migracaoSistemaConta';
//    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratos';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'numero',
        'fornecedor_id',
        'unidade_id',
        'tipo_id',
        'categoria_id',
        'subcategoria_id',
        'processo',
        'objeto',
        'info_complementar',
        'receita_despesa',
        'fundamento_legal',
        'modalidade_id',
        'licitacao_numero',
        'data_assinatura',
        'data_publicacao',
        'vigencia_inicio',
        'vigencia_fim',
        'valor_inicial',
        'valor_global',
        'num_parcelas',
        'valor_parcela',
        'valor_acumulado',
        'situacao_siasg',
        'situacao',
        'unidades_requisitantes',
    ];

    public function trataDadosMigracaoConta(array $dado)
    {
        $retorno = [];
        $base = new AdminController();
        $unidade = new Unidade();

        $contrato['numero'] = $base->formataContrato($dado['con_num']);
        $contrato['unidade_id'] = $unidade->buscaUnidadeExecutoraPorCodigo($dado['con_ug']);
        $contrato['tipo_id'] = 60;
        $contrato['categoria_id'] = 55;
        $contrato['processo'] = $base->formataProcesso($dado['con_processo']);
        $contrato['objeto'] = $dado['con_objeto'];
        $contrato['info_complementar'] = $dado['con_infocomple'];
        $contrato['receita_despesa'] = 'D';
        $contrato['fundamento_legal'] = '';
        $contrato['modalidade_id'] = $this->buscaModalidade($dado['con_tipolicitacao']);
        $contrato['licitacao_numero'] = $base->formataContrato($dado['con_licitnum']);
        $contrato['situacao'] = true;

        $dados_historico = [];
        foreach ($dado['historico'] as $item) {
            $dados_historico[] = $base->buscaDadosUrlMigracao($item);
        }
        $contrato_inserido = null;
        foreach ($dados_historico as $dado_historico) {
            if ($dado_historico['his_tipo'] == 'Contrato Inicial') {
                //contrato inicial
                $contrato['fornecedor_id'] = $this->buscaFornecedor($dado_historico['his_cnpj'], $dado_historico['his_fornnome']);
                $contrato['data_assinatura'] = $dado_historico['his_data'];
                $contrato['data_publicacao'] = $dado_historico['his_data'];
                $contrato['vigencia_inicio'] = $dado_historico['his_dtviginicio'];
                $contrato['vigencia_fim'] = $dado_historico['his_dtvigfim'];
                $contrato['valor_inicial'] = $dado_historico['his_vlrglobal'];
                $contrato['valor_global'] = $dado_historico['his_vlrglobal'];
                $contrato['num_parcelas'] = $dado_historico['his_parcelas'];
                $contrato['valor_parcela'] = $dado_historico['his_vlrparcial'];
                $contrato['valor_acumulado'] = $dado_historico['his_vlrglobal'];

                $cont = new Contrato();
                $contrato_inserido = $cont->inserirContratoMigracaoConta($contrato);
            } else {
                if (isset($contrato_inserido->id)) {

                    //historico

                    $con = $this->find($contrato_inserido->id);

                    $ano_historico = explode('-', $dado_historico['his_data']);

                    $his_num = str_pad($dado_historico['his_numero'], 4, "0", STR_PAD_LEFT) . '/' . $ano_historico[0];

                    $historico['numero'] = $his_num;
                    $historico['contrato_id'] = $con->id;

                    if ($dado_historico['his_cnpj'] != '' and $dado_historico['his_fornnome'] != '') {
                        $historico['fornecedor_id'] = $this->buscaFornecedor($dado_historico['his_cnpj'], $dado_historico['his_fornnome']);
                    } else {
                        $historico['fornecedor_id'] = $con->fornecedor_id;
                    }

                    $historico['unidade_id'] = $con->unidade_id;

                    $historico['tipo_id'] = ($dado_historico['his_tipo'] == 'Apostilamento') ? 68 : 65;
                    $historico['receita_despesa'] = 'D';
                    $historico['info_complementar'] = $dado['con_infocomple'];
                    $historico['data_assinatura'] = $dado_historico['his_data'];
                    $historico['data_publicacao'] = ($dado_historico['his_tipo'] == 'Apostilamento') ? null : $dado_historico['his_data'];
                    $historico['vigencia_inicio'] = ($dado_historico['his_tipo'] == 'Apostilamento') ? $con->vigencia_inicio : $dado_historico['his_dtviginicio'];
                    $historico['vigencia_fim'] = ($dado_historico['his_tipo'] == 'Apostilamento') ? $con->vigencia_fim : $dado_historico['his_dtvigfim'];

                    $historico['valor_inicial'] = $dado_historico['his_vlrglobal'];
                    $historico['valor_global'] = $dado_historico['his_vlrglobal'];
                    $historico['num_parcelas'] = $dado_historico['his_parcelas'];
                    $historico['valor_parcela'] = $dado_historico['his_vlrparcial'];

                    $historico['novo_valor_global'] = ($dado_historico['his_tipo'] == 'Apostilamento') ? $dado_historico['his_vlrglobal'] : null;
                    $historico['novo_num_parcelas'] = ($dado_historico['his_tipo'] == 'Apostilamento') ? $dado_historico['his_parcelas'] : null;
                    $historico['novo_valor_parcela'] = ($dado_historico['his_tipo'] == 'Apostilamento') ? $dado_historico['his_vlrparcial'] : null;
                    $historico['data_inicio_novo_valor'] = ($dado_historico['his_tipo'] == 'Apostilamento') ? $dado_historico['his_dtnovovalor'] : null;
                    $historico['observacao'] = $dado_historico['his_observacao'];
                    $historico['retroativo'] = ($dado_historico['his_retroativo'] == 'N') ? false : true;

                    if ($dado_historico['his_retroativo'] == 'S') {
                        $dtde = explode('-', $dado_historico['his_retrodtde']);
                        $dtate = explode('-', $dado_historico['his_retrodtate']);
                        $historico['retroativo_mesref_de'] = $dtde[1];
                        $historico['retroativo_anoref_de'] = $dtde[0];
                        $historico['retroativo_mesref_ate'] = $dtate[1];
                        $historico['retroativo_anoref_ate'] = $dtate[0];
                        $historico['retroativo_vencimento'] = ($dado_historico['his_retrovencimento']) ? $dado_historico['his_retrovencimento'] : $dado_historico['his_retrodtate'];
                        $historico['retroativo_valor'] = $dado_historico['his_retrovlr'];
                        $historico['retroativo_soma_subtrai'] = true;
                    }

                    $hist = new Contratohistorico();
                    $historico_inserido = $hist->inserirContratohistoricoMigracaoConta($historico);
                }
            }
        }

        if (isset($contrato_inserido->id)) {
            $con = $this->find($contrato_inserido->id);

            //responsaveis
            $dados_responsaveis = [];
            if (count($dado['responsaveis'])) {
                foreach ($dado['responsaveis'] as $item) {
                    $dados_responsaveis[] = $base->buscaDadosUrlMigracao($item);
                }
            }

            if (count($dados_responsaveis)) {
                foreach ($dados_responsaveis as $dado_responsavel) {

                    $user = explode('|', $dado_responsavel['qeq_user_cod']);

                    $cpf_user = $base->formataCnpjCpfTipo(str_pad($user[0], 11, "0", STR_PAD_LEFT), 'FISICA');

                    $usuario = BackpackUser::where('cpf', $cpf_user)
                        ->first();

                    if (!isset($usuario->id)) {

                        $array_user = [
                            'cpf' => $cpf_user,
                            'name' => $user[1],
                            'email' => $user[2],
                            'ugprimaria' => $con->unidade_id,
                            'password' => bcrypt(substr(str_pad($user[0], 11, "0", STR_PAD_LEFT), 0, 6)),
                            'perfil' => 'Responsável por Contrato',
                        ];

                        $usuario = $this->inserirUsuario($array_user);

                    }

                    if ($usuario->ugprimaria != $con->unidade_id) {
                        if (!$usuario->unidades()->where('unidade_id', $con->unidade_id)->first()) {
                            $usuario->unidades()->attach($con->unidade_id);
                        }
                    }

                    $responsavel['contrato_id'] = $con->id;
                    $responsavel['user_id'] = $usuario->id;
                    $responsavel['funcao_id'] = $this->buscaFuncaoResponsavel($dado_responsavel['qeq_funcao']);
                    $responsavel['instalacao_id'] = null;
                    $responsavel['portaria'] = $dado_responsavel['qeq_portaria'];
                    $responsavel['situacao'] = ($dado_responsavel['qeq_situacao'] == 'I') ? false : true;
                    $responsavel['data_inicio'] = date('Y-m-d');
                    $responsavel['data_fim'] = ($dado_responsavel['qeq_situacao'] == 'I') ? date('Y-m-d') : null;

                    $hist = new Contratoresponsavel();
                    $historico_inserido = $hist->inserirContratoresponsavelMigracaoConta($responsavel);

                }
            }

            //ocorrencias
            $dados_ocorrencias = [];
            foreach ($dado['ocorrencias'] as $item) {
                $dados_ocorrencias[] = $base->buscaDadosUrlMigracao($item);
            }

            if (count($dados_ocorrencias)) {
                foreach ($dados_ocorrencias as $dados_ocorrencia) {

                    $url_user = $base->buscaDadosUrlMigracao($dados_ocorrencia['oco_fiscal']);

                    $cpf_user = $base->formataCnpjCpfTipo(str_pad($url_user['login'], 11, "0", STR_PAD_LEFT), 'FISICA');


                    $situacao = Codigoitem::whereHas('codigo', function ($query) {
                        $query->where('descricao', 'Situação Ocorrência');
                    })
                        ->where('descricao', $dados_ocorrencia['oco_situacao'])
                        ->first();

                    if ($dados_ocorrencia['oco_situacao'] == 'Conclusiva') {
                        $novasituacao = Codigoitem::whereHas('codigo', function ($query) {
                            $query->where('descricao', 'Situação Ocorrência');
                        })
                            ->where('descricao', $dados_ocorrencia['oco_statusconclusao'])
                            ->first();

                        $oco_alterada = Contratoocorrencia::where('numero', $dados_ocorrencia['oco_idconclusao'])
                            ->where('contrato_id', $con->id)
                            ->first();
                    }

                    $usuario = BackpackUser::where('cpf', $cpf_user)
                        ->first();

                    if (!isset($usuario->id)) {
                        $array_user = [
                            'cpf' => $cpf_user,
                            'name' => $url_user['name'],
                            'email' => $url_user['email'],
                            'ugprimaria' => $con->unidade_id,
                            'password' => bcrypt(substr(str_pad($url_user['login'], 11, "0", STR_PAD_LEFT), 0, 6)),
                            'perfil' => 'Responsável por Contrato',
                        ];
                        $usuario = $this->inserirUsuario($array_user);
                    }

                    if ($usuario->ugprimaria != $con->unidade_id) {
                        if (!$usuario->unidades()->where('unidade_id', $con->unidade_id)->first()) {
                            $usuario->unidades()->attach($con->unidade_id);
                        }
                    }

                    $ocorrencia['numero'] = $dados_ocorrencia['oco_num'];
                    $ocorrencia['contrato_id'] = $con->id;
                    $ocorrencia['user_id'] = $usuario->id;
                    $ocorrencia['data'] = $dados_ocorrencia['oco_dtoco'];
                    $ocorrencia['ocorrencia'] = $dados_ocorrencia['oco_txtocorrencia'];
                    $ocorrencia['notificapreposto'] = ($dados_ocorrencia['oco_notificaprep'] == 'N') ? false : true;
                    $ocorrencia['emailpreposto'] = ($dados_ocorrencia['oco_notificaprep'] == 'N') ? '' : $dados_ocorrencia['oco_emailpreposto'];
                    $ocorrencia['numeroocorrencia'] = ($dados_ocorrencia['oco_situacao'] == 'Conclusiva') ? $oco_alterada->id : null;
                    $ocorrencia['novasituacao'] = ($dados_ocorrencia['oco_situacao'] == 'Conclusiva') ? $novasituacao->id : null;
                    $ocorrencia['situacao'] = $situacao->id;

                    $oco = new Contratoocorrencia();
                    $ocorrencia_inserida = $oco->inserirContratoocorrenciaMigracaoConta($ocorrencia);

                }
            }


            // terceirizados
            $dados_terceirizados = [];
            foreach ($dado['terceirizados'] as $item) {
                $dados_terceirizados[] = $base->buscaDadosUrlMigracao($item);
            }

            if (count($dados_terceirizados)) {
                foreach ($dados_terceirizados as $dados_terceirizado) {

                    $cpf_terceirizado = $base->formataCnpjCpfTipo(str_pad($dados_terceirizado['ter_cpf'], 11, "0", STR_PAD_LEFT), 'FISICA');

                    $funcao = Codigoitem::whereHas('codigo', function ($query) {
                        $query->where('descricao', 'Mão de Obra');
                    })
                        ->where('descricao', $dados_terceirizado['ter_funcao'])
                        ->first();

                    $escolaridade = Codigoitem::whereHas('codigo', function ($query) {
                        $query->where('descricao', 'Escolaridade');
                    })
                        ->where('descricao', $dados_terceirizado['ter_escolaridade'])
                        ->first();

                    $terceirizado['contrato_id'] = $con->id;
                    $terceirizado['cpf'] = $cpf_terceirizado;
                    $terceirizado['nome'] = $dados_terceirizado['ter_nome'];
                    $terceirizado['funcao_id'] = (!isset($funcao->id)) ? 98 : $funcao->id;
                    $terceirizado['jornada'] = $dados_terceirizado['ter_jornada'];
                    $terceirizado['unidade'] = $dados_terceirizado['ter_unidade'];
                    $terceirizado['salario'] = $dados_terceirizado['ter_salario'];
                    $terceirizado['custo'] = $dados_terceirizado['ter_custo'];
                    $terceirizado['escolaridade_id'] = (!isset($escolaridade->id)) ? 78 : $escolaridade->id;
                    $terceirizado['data_inicio'] = $dados_terceirizado['ter_dtinicio'];
                    $terceirizado['data_fim'] = $dados_terceirizado['ter_dtfim'];
                    $terceirizado['situacao'] = ($dados_terceirizado['ter_situacao'] == 'A') ? true : false;

                    $ter = new Contratoterceirizado();
                    $terceirizado_inserido = $ter->inserirContratoterceirizadoMigracaoConta($terceirizado);

                }
            }

            // empenhos
            $dados_empenhos = [];
            foreach ($dado['empenhos'] as $item) {
                $dados_empenhos[] = $base->buscaDadosUrlMigracao($item);
            }

            if (count($dados_empenhos)) {
                foreach ($dados_empenhos as $dados_empenho) {
                    $contratoempenho_inserido = $this->inserirEmpenho($dados_empenho, $con);
                }
            }

            $dados_faturas = [];
            foreach ($dado['faturas'] as $item) {
                $dados_faturas[] = $base->buscaDadosUrlMigracao($item);
            }

            if (count($dados_faturas)) {
                foreach ($dados_faturas as $dados_fatura) {
                    $contratofatura_inserido = $this->inserirFatura($dados_fatura, $con);
                }
            }

        }
        return $retorno;
    }

    private function inserirFatura($dados_fatura, $con)
    {
        $base = new AdminController();
        $tipolistafatura_id = $this->buscaTipoListaFatura($dados_fatura['fat_tli_id']);
        $justificativafatura_id = null;
        if($dados_fatura['fat_jus_id'] != ""){
            $justificativafatura_id = $this->buscaJustificativaFatura($dados_fatura['fat_jus_id']);
        }


        if($dados_fatura['fat_processo'] != ''){
            $processo = $base->formataProcesso($dados_fatura['fat_processo']);
        }else{
            $processo = '99999.999999/9999-99';
        }


        $fatura['contrato_id'] = $con->id;
        $fatura['tipolistafatura_id'] = $tipolistafatura_id;
        $fatura['justificativafatura_id'] = $justificativafatura_id;
        $fatura['numero'] = $dados_fatura['fat_num'];
        $fatura['emissao'] = $dados_fatura['fat_dtemissao'];
        $fatura['prazo'] = $dados_fatura['fat_prazo'];
        $fatura['vencimento'] = $dados_fatura['fat_dtvenc'];
        $fatura['valor'] = $dados_fatura['fat_valor'];
        $fatura['juros'] = 0;
        $fatura['multa'] = 0;
        $fatura['glosa'] = 0;
        $fatura['valorliquido'] = $dados_fatura['fat_valor'];
        $fatura['processo'] = $processo;
        $fatura['protocolo'] = $dados_fatura['fat_ateste'];
        $fatura['ateste'] = $dados_fatura['fat_ateste'];
        $fatura['repactuacao'] = false;
        $fatura['infcomplementar'] = $dados_fatura['fat_infocomp'];
        $fatura['mesref'] = ($dados_fatura['fat_mesref'] != '') ? $dados_fatura['fat_mesref'] : '99';
        $fatura['anoref'] = ($dados_fatura['fat_anoref'] != '') ? $dados_fatura['fat_anoref'] : '9999';
        $fatura['situacao'] = $dados_fatura['fat_situacao'];

        $fat = new Contratofatura();
        $contratosfatura_inserida = $fat->inserirContratoFaturaMigracaoConta($fatura);

        return $contratosfatura_inserida;

    }

    private function inserirEmpenho($dados_empenho, $con)
    {
        $emp_nomcredor = explode(' - ', $dados_empenho['emp_nomcredor']);
        $empenho_fornecedor_id = $this->buscaFornecedor($emp_nomcredor[0], $emp_nomcredor[1]);
        $emp_pi = explode(' - ', $dados_empenho['emp_pi']);
        $emp_natdesp = explode(' - ', $dados_empenho['emp_natdesp']);

        $desc_pi = (isset($emp_pi[2])) ? $emp_pi[1] . ' - ' . $emp_pi[2] : $emp_pi[1];
        $desc_nd = (isset($emp_natdesp[2])) ? $emp_natdesp[1] . ' - ' . $emp_natdesp[2] : $emp_natdesp[1];

        $planointerno_id = $this->buscaPlanoInterno($emp_pi[0], $desc_pi);
        $naturezadespesa_id = $this->buscaNaturezaDespesa($emp_natdesp[0], $desc_nd);

        $empenho['numero'] = $dados_empenho['emp_num'];
        $empenho['unidade_id'] = $con->unidade_id;
        $empenho['fornecedor_id'] = $empenho_fornecedor_id;
        $empenho['planointerno_id'] = $planointerno_id;
        $empenho['naturezadespesa_id'] = $naturezadespesa_id;
        $empenho['empenhado'] = $dados_empenho['emp_empenhado'];
        $empenho['aliquidar'] = $dados_empenho['emp_aliquidar'];
        $empenho['liquidado'] = $dados_empenho['emp_liquidado'];
        $empenho['pago'] = $dados_empenho['emp_pago'];
        $empenho['rpinscrito'] = $dados_empenho['emp_rpinscrito'];
        $empenho['rpaliquidar'] = $dados_empenho['emp_rpaliquidar'];
        $empenho['rpliquidado'] = $dados_empenho['emp_rpliquidado'];
        $empenho['rppago'] = $dados_empenho['emp_rppago'];


        $empenho_inserido = $this->buscaEmpenho($empenho);

        $array_con_emp = [
            'contrato_id' => $con->id,
            'fornecedor_id' => $empenho_fornecedor_id,
            'empenho_id' => $empenho_inserido->id,
        ];

        $con_emp = new Contratoempenho();
        $contratoempenho_inserido = $con_emp->inserirContratoEmpenhoMigracaoConta($array_con_emp);

        return $contratoempenho_inserido;

    }

    private function buscaEmpenho($dado)
    {
        $empenho = Empenho::where('numero', $dado['numero'])
            ->where('unidade_id', $dado['unidade_id'])
            ->first();

        if (!isset($empenho->id)) {
            $emp = new Empenho();
            $empenho = $emp->inserirEmpenhoMigracaoConta($dado);
        }

        return $empenho;
    }

    private function inserirUsuario(array $dados)
    {
        $usuario = BackpackUser::create([
            'cpf' => $dados['cpf'],
            'name' => $dados['name'],
            'email' => $dados['email'],
            'ugprimaria' => $dados['ugprimaria'],
            'password' => $dados['password'],
        ]);

        $usuario->assignRole('Responsável por Contrato');

        return $usuario;
    }

    private function buscaModalidade($dado)
    {
        $modalidade = $this->modalidade()->where('descricao', $dado)->first();

        if (!isset($modalidade->id)) {
            return 75;
        }

        return $modalidade->id;
    }

    private function buscaTipoListaFatura($dado)
    {
        $tipolista = Tipolistafatura::where('nome', $dado)->first();

        if(!isset($tipolista->id)){
            return 5;
        }

        return $tipolista->id;
    }

    private function buscaJustificativaFatura($dado)
    {
        $justificativa = Justificativafatura::where('nome', $dado)->first();

        if(!isset($justificativa->id)){
            return 7;
        }

        return $justificativa->id;
    }

    private function buscaPlanoInterno($codigo, $descricao)
    {
        $planointerno = Planointerno::where('codigo', $codigo)->first();

        if (!isset($planointerno->id)) {
            $planointerno = Planointerno::create([
                'codigo' => $codigo,
                'descricao' => $descricao,
                'situacao' => true,
            ]);
        }

        return $planointerno->id;
    }

    private function buscaNaturezaDespesa($codigo, $descricao)
    {
        $naturezadespesa = Naturezadespesa::where('codigo', $codigo)->first();

        if (!isset($naturezadespesa->id)) {
            $naturezadespesa = Naturezadespesa::create([
                'codigo' => $codigo,
                'descricao' => $descricao,
                'situacao' => true,
            ]);
        }

        return $naturezadespesa->id;
    }

    private function buscaFuncaoResponsavel($dado)
    {
        $funcao = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Função Contrato');
        })
            ->where('descricao', $dado)
            ->first();

        if (!isset($funcao->id)) {
            return 47;
        }

        return $funcao->id;
    }

    private function buscaFornecedor($cnpj, $nome)
    {
        $base = new AdminController();

        $tipo = 'JURIDICA';
        if (strlen($cnpj) == 11) {
            $tipo = 'FISICA';
        } elseif (strlen($cnpj) == 9) {
            $tipo = 'IDGENERICO';
        } elseif (strlen($cnpj) == 6) {
            $tipo = 'UG';
        };

        $cpf_cnpj_idgener = $base->formataCnpjCpfTipo($cnpj, $tipo);

        $fornecedor = Fornecedor::where('cpf_cnpj_idgener', '=', $cpf_cnpj_idgener)
            ->first();

        if (!isset($fornecedor->id)) {
            $fornecedor = Fornecedor::create([
                'tipo_fornecedor' => $tipo,
                'cpf_cnpj_idgener' => $cpf_cnpj_idgener,
                'nome' => strtoupper(trim($nome))
            ]);

        } elseif ($fornecedor->nome != strtoupper(trim($nome))) {
            $fornecedor->nome = strtoupper(trim($nome));
            $fornecedor->save();
        }

        return $fornecedor->id;
    }


    public function historico()
    {

        return $this->hasMany(Contratohistorico::class, 'contrato_id');

    }

    public function cronograma()
    {

        return $this->hasMany(Contratocronograma::class, 'contrato_id');

    }


    public function responsaveis()
    {

        return $this->hasMany(Contratoresponsavel::class, 'contrato_id');

    }

    public function garantias()
    {

        return $this->hasMany(Contratogarantia::class, 'contrato_id');

    }

    public function arquivos()
    {

        return $this->hasMany(Contratoarquivo::class, 'contrato_id');

    }

    public function empenhos()
    {

        return $this->hasMany(Contratoempenho::class, 'contrato_id');

    }

    public function faturas()
    {

        return $this->hasMany(Contratofatura::class, 'contrato_id');

    }

    public function ocorrencias()
    {

        return $this->hasMany(Contratoocorrencia::class, 'contrato_id');

    }

    public function terceirizados()
    {

        return $this->hasMany(Contratoterceirizado::class, 'contrato_id');

    }

    public function unidade()
    {

        return $this->belongsTo(Unidade::class, 'unidade_id');

    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Codigoitem::class, 'categoria_id');
    }

    public function modalidade()
    {
        return $this->belongsTo(Codigoitem::class, 'modalidade_id');
    }

    public function orgaosubcategoria()
    {
        return $this->belongsTo(OrgaoSubcategoria::class, 'subcategoria_id');
    }


}
