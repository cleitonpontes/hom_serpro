<?php

namespace App\Models;

use App\Http\Controllers\AdminController;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class MigracaoComprasnetContratos extends Model
{
    use CrudTrait;
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logName = 'migracaoComprasnetContratos';
//    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'migracaosistemaconta';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'orgao_id',
    ];
    public function tratarDadosFornecedor($dadosFornecedor){
        $partes = explode("|", $dadosFornecedor);
        $cnpjFornecedor = $partes[0];
        $nomeFornecedor = $partes[1];
        return $arrayRetorno = array('cnpj' => $cnpjFornecedor, 'nome' => $nomeFornecedor);
    }

    // método chamado em MigracaoSistemaContaJob.php
    public function trataDadosMigracaoConta(array $dado)
    {
        set_time_limit(0);

        $retorno = [];
        $base = new AdminController();
        $unidade = new Unidade();

        $contrato['numero'] = $dado['numero']; // já está vindo formatado da agu

        $dadosFornecedor = $dado['fornecedor_id'];
        $dadosFornecedorTratados = self::tratarDadosFornecedor($dadosFornecedor);
        $cnpjFornecedor = $dadosFornecedorTratados['cnpj'];
        $nomeFornecedor = $dadosFornecedorTratados['nome'];
        $contrato['fornecedor_id'] = $this->buscaFornecedor($cnpjFornecedor, $nomeFornecedor);
        $contrato['unidade_id'] = $unidade->buscaUnidadeExecutoraPorCodigo($dado['unidade_id']);
        $contrato['tipo_id'] = $this->buscarTipoId($dado);
        $contrato['categoria_id'] = $this->buscarCategoriaId($dado);
        $contrato['receita_despesa'] = $dado['receita_despesa'];

        $contrato['processo'] = $dado['processo']; // já está vindo formatado da agu
        $contrato['objeto'] = $dado['objeto'];
        $contrato['info_complementar'] = $dado['info_complementar'];
        $contrato['fundamento_legal'] = $dado['fundamento_legal'];
        $contrato['modalidade_id'] = $this->buscarModalidadeId($dado);
        $contrato['licitacao_numero'] = $dado['licitacao_numero']; // já está vindo formatado da agu
        $contrato['data_assinatura'] = $dado['data_assinatura'];
        $contrato['data_publicacao'] = $dado['data_publicacao'];
        $contrato['vigencia_inicio'] = $dado['vigencia_inicio'];
        $contrato['vigencia_fim'] = $dado['vigencia_fim'];
        $contrato['valor_inicial'] = $dado['valor_inicial'];
        $contrato['valor_global'] = $dado['valor_global'];
        $contrato['num_parcelas'] = $dado['num_parcelas'];
        $contrato['valor_parcela'] = $dado['valor_parcela'];
        // valor acumulado é calculado
        $contrato['situacao_siasg'] = $dado['situacao_siasg'];
        $contrato['situacao'] = $dado['situacao'];
        $contrato['unidades_requisitantes'] = $dado['unidades_requisitantes'];
        $contrato['total_despesas_acessorias'] = $dado['total_despesas_acessorias'];

        // verificar se algum contrato veio sem histórico
        $quantidadeHistoricos = $dado['contratohistoricos'];
        $dados_historico = [];
        foreach ($dado['contratohistoricos'] as $item) {
            $dados_historico[] = $base->buscaDadosUrlMigracao($item);
        }

        $quantidadeHistoricos = count($dados_historico);

        $contrato_inserido = null;
        foreach ($dados_historico as $dado_historico) {

            $tipoHistorico = $dado_historico['tipo_id'];

            // if ($dado_historico['tipo_id'] == 'Contrato') {
            if ($dado_historico['tipo_id'] != 'Termo Aditivo' && $dado_historico['tipo_id'] != 'Termo de Apostilamento' && $dado_historico['tipo_id'] != 'Termo Rescisão') {

                $dadosFornecedor = $dado_historico['fornecedor_id'];
                $dadosFornecedorTratados = self::tratarDadosFornecedor($dadosFornecedor);
                $cnpjFornecedor = $dadosFornecedorTratados['cnpj'];
                $nomeFornecedor = $dadosFornecedorTratados['nome'];

                $contrato['fornecedor_id'] = $this->buscaFornecedor($cnpjFornecedor, $nomeFornecedor);
                $contrato['data_assinatura'] = $dado_historico['data_assinatura'];
                $contrato['data_publicacao'] = $dado_historico['data_publicacao'];
                $contrato['vigencia_inicio'] = $dado_historico['vigencia_inicio'];
                $contrato['vigencia_fim'] = $dado_historico['vigencia_fim'];
                $contrato['valor_inicial'] = $dado_historico['valor_inicial'];
                $contrato['valor_global'] = $dado_historico['valor_global'];
                $contrato['num_parcelas'] = $dado_historico['num_parcelas'];
                $contrato['valor_parcela'] = $dado_historico['valor_parcela'];

                $cont = new Contrato();
                $contrato_inserido = $cont->inserirContratoMigracaoConta($contrato);

            } else {
                if (isset($contrato_inserido->id)) {
                    //historico
                    $con = Contrato::find($contrato_inserido->id);

                    $ano_historico = explode('-', $dado_historico['data_assinatura']);
                    $his_num = $dado_historico['numero'];
                    $historico['numero'] = $his_num;
                    $historico['contrato_id'] = $con->id;

                    $dadosFornecedor = $dado_historico['fornecedor_id'];
                    $dadosFornecedorTratados = self::tratarDadosFornecedor($dadosFornecedor);
                    $cnpjFornecedor = $dadosFornecedorTratados['cnpj'];
                    $nomeFornecedor = $dadosFornecedorTratados['nome'];

                    $historico['fornecedor_id'] = $this->buscaFornecedor($cnpjFornecedor, $nomeFornecedor);
                    $historico['unidade_id'] = $unidade->buscaUnidadeExecutoraPorCodigo($dado_historico['unidade_id']);

                    $tipoId = $this->buscarTipoId($dado);
                    $historico['tipo_id'] = ($tipoId == 'Apostilamento') ? 68 : 65;

                    $historico['receita_despesa'] = $dado_historico['receita_despesa'];
                    $historico['info_complementar'] = $dado_historico['info_complementar'];
                    $historico['data_assinatura'] = $dado_historico['data_assinatura'];
                    $historico['data_publicacao'] = ($dado_historico['tipo_id'] == 'Apostilamento') ? null : $dado_historico['data_assinatura'];
                    $historico['vigencia_inicio'] = ($dado_historico['tipo_id'] == 'Apostilamento') ? $con->vigencia_inicio : $dado_historico['vigencia_inicio'];
                    $historico['vigencia_fim'] = ($dado_historico['tipo_id'] == 'Apostilamento') ? $con->vigencia_fim : $dado_historico['vigencia_fim'];
                    $historico['valor_inicial'] = $dado_historico['valor_inicial'];
                    $historico['valor_global'] = $dado_historico['valor_global'];
                    $historico['num_parcelas'] = $dado_historico['num_parcelas'];
                    $historico['valor_parcela'] = $dado_historico['valor_parcela'];
                    $historico['novo_valor_global'] = ($dado_historico['tipo_id'] == 'Apostilamento') ? $dado_historico['novo_valor_global'] : null;
                    $historico['novo_num_parcelas'] = ($dado_historico['tipo_id'] == 'Apostilamento') ? $dado_historico['novo_num_parcelas'] : null;
                    $historico['novo_valor_parcela'] = ($dado_historico['tipo_id'] == 'Apostilamento') ? $dado_historico['novo_valor_parcela'] : null;
                    $historico['data_inicio_novo_valor'] = ($dado_historico['tipo_id'] == 'Apostilamento') ? $dado_historico['data_inicio_novo_valor'] : null;
                    $historico['observacao'] = $dado_historico['observacao'];
                    $historico['retroativo'] = ($dado_historico['retroativo'] == 'N') ? false : true;
                    if ($dado_historico['retroativo'] == 'S') {
                        $dtde = explode('-', $dado_historico['retroativo_mesref_de']);
                        $dtate = explode('-', $dado_historico['retroativo_mesref_ate']);
                        $historico['retroativo_mesref_de'] = $dtde[1];
                        $historico['retroativo_anoref_de'] = $dtde[0];
                        $historico['retroativo_mesref_ate'] = $dtate[1];
                        $historico['retroativo_anoref_ate'] = $dtate[0];
                        $historico['retroativo_vencimento'] = ($dado_historico['retroativo_vencimento']) ? $dado_historico['retroativo_vencimento'] : $dado_historico['retroativo_mesref_ate'];
                        $historico['retroativo_valor'] = $dado_historico['retroativo_valor'];
                        $historico['retroativo_soma_subtrai'] = $dado_historico['retroativo_soma_subtrai'];
                    }
                    $hist = new Contratohistorico();

                    $historico_inserido = $hist->inserirContratohistoricoMigracaoConta($historico);

                }
            }
        }

        if (isset($contrato_inserido->id)) {

            $con = Contrato::find($contrato_inserido->id);
            $quantidadeContratoResponsaveis = (is_array($dado['contratoresponsaveis']) ? count($dado['contratoresponsaveis']) : 0);

            //responsaveis
            $dados_responsaveis = [];
            if ($quantidadeContratoResponsaveis > 0) {
                foreach ($dado['contratoresponsaveis'] as $item) {
                    $dados_responsaveis[] = $base->buscaDadosUrlMigracao($item);
                }
            }
            if (count($dados_responsaveis)) {

                foreach ($dados_responsaveis as $dado_responsavel) {

                    $user = explode('|', $dado_responsavel['user_id']);
                    $cpf_user = $user[0];
                    $usuario = BackpackUser::where('cpf', $cpf_user)
                        ->first();

                    // vamos verificar se não possui deleted_at
                    $deleted_at = $dado_responsavel['deleted_at'];

                    if( $deleted_at == null ){

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
                        $responsavel['funcao_id'] = $this->buscaFuncaoResponsavel($dado_responsavel['funcao_id']);
                        $responsavel['instalacao_id'] = null;
                        $responsavel['portaria'] = $dado_responsavel['portaria'];
                        $responsavel['situacao'] = $dado_responsavel['situacao'];
                        $responsavel['data_inicio'] = $dado_responsavel['data_inicio'];
                        $responsavel['data_fim'] = $dado_responsavel['data_fim'];

                        $hist = new Contratoresponsavel();
                        $historico_inserido = $hist->inserirContratoresponsavelMigracaoConta($responsavel);

                    }
                }
            }

            //ocorrencias
            $dados_ocorrencias = [];
            $quantidadeContratoOcorrencias = (is_array($dado['contratoocorrencias']) ? count($dado['contratoocorrencias']) : 0);
            if ($quantidadeContratoOcorrencias > 0) {
                foreach ($dado['contratoocorrencias'] as $item) {
                    $dados_ocorrencias[] = $base->buscaDadosUrlMigracao($item);
                }
            }


            // terceirizados
            $dados_terceirizados = [];
            $quantidadeContratoTerceirizados = (is_array($dado['contratoterceirizados']) ? count($dado['contratoterceirizados']) : 0);
            if ($quantidadeContratoTerceirizados > 0) {
                foreach ($dado['contratoterceirizados'] as $item) {
                    $dados_terceirizados[] = $base->buscaDadosUrlMigracao($item);
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
        if ($dados_fatura['fat_jus_id'] != "") {
            $justificativafatura_id = $this->buscaJustificativaFatura($dados_fatura['fat_jus_id']);
        }


        if ($dados_fatura['fat_processo'] != '') {
            $processo = $base->formataProcesso($dados_fatura['fat_processo']);
        } else {
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

    private function buscaTipoListaFatura($dado)
    {
        $tipolista = Tipolistafatura::where('nome', $dado)->first();

        if (!isset($tipolista->id)) {
            return 5;
        }

        return $tipolista->id;
    }

    private function buscaJustificativaFatura($dado)
    {
        $justificativa = Justificativafatura::where('nome', $dado)->first();

        if (!isset($justificativa->id)) {
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

        // verificar se o cnpj precisa ser formatado
        $quantidadeCaracteresCnpj = strlen($cnpj);
        if($quantidadeCaracteresCnpj < 18){
            $cpf_cnpj_idgener = $base->formataCnpjCpfTipo($cnpj, $tipo);
        } else {
            $cpf_cnpj_idgener = $cnpj;
        }


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


    public function orgao()
    {
        return $this->belongsTo(Orgao::class, 'orgao_id');
    }

    private function buscaModalidade($dado)
    {
        $contrato = new Contrato();
        $modalidade = $contrato->modalidade()->where('descricao', $dado)->first();

        if (!isset($modalidade->id)) {
            return 75;
        }

        return $modalidade->id;
    }

    //
    public function buscarModalidadeId($dado)
    {
        $idDado = $dado['modalidade_id'];

        $objeto = Codigoitem::where('descricao', $idDado)->first();
        if ($objeto == null) {
            return config('migracao.modalidade_padrao');
        } else {
            return $id = $objeto->id;
        }
    }

    public function buscarTipoId($dado)
    {
        $tipoIdDado = $dado['tipo_id'];
        $objeto = Codigoitem::where('descricao', $tipoIdDado)->first();
        if ($objeto == null) {
            return config('migracao.tipo_contrato_padrao');
        } else {
            return $id = $objeto->id;
        }
    }

    public function buscarCategoriaId($dado)
    {
        $categoriaIdDado = $dado['categoria_id'];
        $objeto = Codigoitem::where('descricao', $categoriaIdDado)->first();

        if ($objeto == null) {
            return config('migracao.categoria_padrao');
        } else {
            return $id = $objeto->id;
        }
    }
}
