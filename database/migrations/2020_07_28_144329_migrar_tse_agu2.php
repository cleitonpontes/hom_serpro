<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Spatie\Permission\Models\Role;
use App\Models\Contratohistorico;
use App\User;
use App\Models\Fornecedor;
use App\Models\Codigoitem;
use App\Models\AppVersion;
use App\Models\Centrocusto;
use App\Models\Codigo;
use App\Models\Justificativafatura;
use App\Models\Tipolistafatura;
use App\Models\Naturezadespesa;
use App\Models\Naturezasubitem;
use App\Models\Orgaosuperior;
use App\Models\Rhrubrica;

class MigrarTseAgu2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        set_time_limit(0);
        echo '<br><br>iniciando tratamento de dados...<br><br>';
        self::migrarFornecedores();
        self::migrarUsersCpf();
        self::migrarUsersEmail();
        self::migrarCodigoitem();
        self::migrarAppVersion();
        self::migrarCentrocusto();
        self::migrarCodigo();
        self::migrarJustificativafatura();
        self::migrarTipolistafatura();
        self::migrarNaturezadespesa();
        self::migrarNaturezasubitem();
        self::migrarOrgaosuperior();
        self::migrarRhrubrica();
        self::migrarRoles();
        self::rodarScript2();
        echo '<br><br>tratamento finalizado.';
    }

    public function rodarScript2(){
        // -- desabilitar trigger
        DB::select( DB::raw("SELECT fc_habilitar_triggers('public', FALSE );") );
        // -- INÍCIO INCREMENTO SEQUENCE
        DB::select( DB::raw("select setval('activity_log_expurgo_id_seq',  (select nextval('activity_log_expurgo_id_seq') + 55000000 ));") );
        DB::select( DB::raw("select setval('activity_log_id_seq',          (select nextval('activity_log_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('app_version_id_seq',  (select nextval('app_version_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('apropriacoes_fases_id_seq',  (select nextval('apropriacoes_fases_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('apropriacoes_id_seq',  (select nextval('apropriacoes_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('apropriacoes_importacao_id_seq',  (select nextval('apropriacoes_importacao_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('apropriacoes_nota_empenho_id_seq',  (select nextval('apropriacoes_nota_empenho_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('apropriacoes_situacao_id_seq',  (select nextval('apropriacoes_situacao_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('calendarevents_id_seq',  (select nextval('calendarevents_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('catmatseratualizacao_id_seq',  (select nextval('catmatseratualizacao_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('catmatsergrupos_id_seq',  (select nextval('catmatsergrupos_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('catmatseritens_id_seq',  (select nextval('catmatseritens_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('centrocusto_id_seq',  (select nextval('centrocusto_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('codigoitens_id_seq',  (select nextval('codigoitens_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('codigos_id_seq',  (select nextval('codigos_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('comunica_id_seq',  (select nextval('comunica_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('contrato_arquivos_id_seq',  (select nextval('contrato_arquivos_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('contratocronograma_id_seq',  (select nextval('contratocronograma_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('contratodespesaacessoria_id_seq',  (select nextval('contratodespesaacessoria_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('contratoempenhos_id_seq',  (select nextval('contratoempenhos_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('contratofaturas_id_seq',  (select nextval('contratofaturas_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('contratogarantias_id_seq',  (select nextval('contratogarantias_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('contratohistorico_id_seq',  (select nextval('contratohistorico_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('contratoitens_id_seq',  (select nextval('contratoitens_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('contratoocorrencias_id_seq',  (select nextval('contratoocorrencias_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('contratopreposto_id_seq',  (select nextval('contratopreposto_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('contratoresponsaveis_id_seq',  (select nextval('contratoresponsaveis_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('contratos_id_seq',  (select nextval('contratos_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('contratoterceirizados_id_seq',  (select nextval('contratoterceirizados_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('documentosiafi_id_seq',  (select nextval('documentosiafi_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('empenhodetalhado_id_seq',  (select nextval('empenhodetalhado_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('empenhos_id_seq',  (select nextval('empenhos_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('execsfsituacao_id_seq',  (select nextval('execsfsituacao_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('failed_jobs_id_seq',  (select nextval('failed_jobs_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('fornecedores_id_seq',  (select nextval('fornecedores_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('importacoes_id_seq',  (select nextval('importacoes_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('instalacoes_id_seq',  (select nextval('instalacoes_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('jobs_id_seq',  (select nextval('jobs_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('justificativafatura_id_seq',  (select nextval('justificativafatura_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('migracaosistemaconta_id_seq',  (select nextval('migracaosistemaconta_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('migrations_id_seq',  (select nextval('migrations_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('naturezadespesa_id_seq',  (select nextval('naturezadespesa_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('naturezasubitem_id_seq',  (select nextval('naturezasubitem_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('orgaoconfiguracao_id_seq',  (select nextval('orgaoconfiguracao_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('orgaos_id_seq',  (select nextval('orgaos_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('orgaossuperiores_id_seq',  (select nextval('orgaossuperiores_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('orgaosubcategorias_id_seq',  (select nextval('orgaosubcategorias_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('permissions_id_seq',  (select nextval('permissions_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('planointerno_id_seq',  (select nextval('planointerno_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('rhddp_id_seq',  (select nextval('rhddp_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('rhddpdetalhado_id_seq',  (select nextval('rhddpdetalhado_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('rhrubrica_id_seq',  (select nextval('rhrubrica_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('rhsituacao_id_seq',  (select nextval('rhsituacao_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('roles_id_seq',  (select nextval('roles_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('saldohistoricoitens_id_seq',  (select nextval('saldohistoricoitens_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfacrescimo_id_seq',  (select nextval('sfacrescimo_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfcentrocusto_id_seq',  (select nextval('sfcentrocusto_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfcertificado_id_seq',  (select nextval('sfcertificado_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfdadosbasicos_id_seq',  (select nextval('sfdadosbasicos_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfdeducao_encargo_dadospagto_id_seq',  (select nextval('sfdeducao_encargo_dadospagto_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfdespesaanular_id_seq',  (select nextval('sfdespesaanular_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfdespesaanularitem_id_seq',  (select nextval('sfdespesaanularitem_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfdocorigem_id_seq',  (select nextval('sfdocorigem_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfdomiciliobancario_id_seq',  (select nextval('sfdomiciliobancario_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfitemrecolhimento_id_seq',  (select nextval('sfitemrecolhimento_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfnonce_id_seq',  (select nextval('sfnonce_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfoutroslanc_id_seq',  (select nextval('sfoutroslanc_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfpadrao_id_seq',  (select nextval('sfpadrao_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfpco_id_seq',  (select nextval('sfpco_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfpcoitem_id_seq',  (select nextval('sfpcoitem_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfpredoc_id_seq',  (select nextval('sfpredoc_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfpso_id_seq',  (select nextval('sfpso_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfpsoitem_id_seq',  (select nextval('sfpsoitem_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfrelitemded_id_seq',  (select nextval('sfrelitemded_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfrelitemdespanular_id_seq',  (select nextval('sfrelitemdespanular_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('sfrelitemvlrcc_id_seq',  (select nextval('sfrelitemvlrcc_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('subrogacoes_id_seq',  (select nextval('subrogacoes_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('tipolistafatura_id_seq',  (select nextval('tipolistafatura_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('unidadeconfiguracao_id_seq',  (select nextval('unidadeconfiguracao_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('unidades_id_seq',  (select nextval('unidades_id_seq') + 55000000));") );
        DB::select( DB::raw("select setval('users_id_seq',  (select nextval('users_id_seq') + 55000000));") );
        // -- habilitar trigger
        DB::select( DB::raw("SELECT fc_habilitar_triggers('public', TRUE );") );
        // -- rodar após script php
        DB::select( DB::raw("delete from fornecedores where deleted_at is not null;") );
        DB::select( DB::raw("delete from users where deleted_at is not null;") );
        DB::select( DB::raw("delete from codigos where deleted_at is not null;") );
        DB::select( DB::raw("ALTER TABLE fornecedores ADD CONSTRAINT fornecedores_cpf_cnpj_idgener_unique UNIQUE (cpf_cnpj_idgener);") );
        DB::select( DB::raw("ALTER TABLE users ADD CONSTRAINT users_cpf_unique UNIQUE (cpf);") );
        DB::select( DB::raw("ALTER TABLE users ADD CONSTRAINT users_email_unique UNIQUE (email);") );
    }
    public function excluirRolesComIdInvalido($idExcluir){
        echo '<br>Preparando para excluir roles id = '.$idExcluir;
        if(Role::where('id', $idExcluir)->delete()){return true;}
        else{return false;}
    }
    public function excluirRhrubricaComIdInvalido($idExcluir){
        echo '<br>Preparando para excluir rhrubrica id = '.$idExcluir;
        if(Rhrubrica::where('id', $idExcluir)->delete()){return true;}
        else{return false;}
    }
    public function excluirOrgaosuperiorComIdInvalido($idExcluir){
        echo '<br>Preparando para excluir orgaosuperior id = '.$idExcluir;
        if(Orgaosuperior::where('id', $idExcluir)->delete()){return true;}
        else{return false;}
    }
    public function excluirNaturezasubitemComIdInvalido($idExcluir){
        echo '<br>Preparando para excluir naturezasubitem id = '.$idExcluir;
        if(Naturezasubitem::where('id', $idExcluir)->delete()){return true;}
        else{return false;}
    }
    public function excluirNaturezadespesaComIdInvalido($idExcluir){
        echo '<br>Preparando para excluir naturezadespesa id = '.$idExcluir;
        if(Naturezadespesa::where('id', $idExcluir)->delete()){return true;}
        else{return false;}
    }
    public function excluirTipolistafaturaComIdInvalido($idExcluir){
        echo '<br>Preparando para excluir tipolistafatura id = '.$idExcluir;
        if(Tipolistafatura::where('id', $idExcluir)->delete()){return true;}
        else{return false;}
    }
    public function excluirJustificativafaturaComIdInvalido($idExcluir){
        echo '<br>Preparando para excluir justificativafatura id = '.$idExcluir;
        if(Justificativafatura::where('id', $idExcluir)->delete()){return true;}
        else{return false;}
    }
    public function excluirCodigoComIdInvalido($idExcluir){
        echo '<br>Preparando para excluir codigo id = '.$idExcluir;
        if(Codigo::where('id', $idExcluir)->delete()){return true;}
        else{return false;}
    }
    public function excluirCentrocustoComIdInvalido($idExcluir){
        echo '<br>Preparando para excluir centrocusto id = '.$idExcluir;
        if(Centrocusto::where('id', $idExcluir)->delete()){return true;}
        else{return false;}
    }
    public function excluirAppVersionComIdInvalido($idExcluir){
        echo '<br>Preparando para excluir app version id = '.$idExcluir;
        if(AppVersion::where('id', $idExcluir)->delete()){return true;}
        else{return false;}
    }
    public function excluirUserComIdInvalido($idExcluir){
        echo '<br>Preparando para excluir user id = '.$idExcluir;
        if(User::where('id', $idExcluir)->delete()){return true;}
        else{return false;}
    }
    public function excluirCodigoitemComIdInvalido($idExcluir){
        echo '<br>Preparando para excluir codigoitem id = '.$idExcluir;
        if(Codigoitem::where('id', $idExcluir)->delete()){return true;}
        else{return false;}
    }
    public function excluirFornecedorComIdInvalido($idExcluir){
        echo '<br>Preparando para excluir fornecedor id = '.$idExcluir;
        if(Fornecedor::where('id', $idExcluir)->delete()){return true;}
        else{return false;}
    }
    public function atualizarIdInvalidoParaIdValido($nomeCampo, $nomeTabela, $idInvalido, $idValido){
        echo '<br>Entrou em atualizarIdInvalidoParaIdValido... tabela: '.$nomeTabela.' id valido: '.$idValido.'id inválido: '.$idInvalido;

        //buscar aonde o id = idValido e verificar se a unidade é igual a unidade
        if($nomeTabela=='rhsituacao_rhrubrica'){
            // precisamos saber se, ao excluírmos, a chave composta não será repetida, o que ocasionará erro
            $arrayDadosVerificar = DB::select("select * from rhsituacao_rhrubrica where rhrubrica_id = $idInvalido");
            foreach($arrayDadosVerificar as $objDadosVerificar){
                $rhrubrica_id = $objDadosVerificar->rhrubrica_id;
                $rhsituacao_id = $objDadosVerificar->rhsituacao_id;
                // vamos verificar se ja tem o idValido e a situacao
                $arrayDadosVerificar = DB::select("
                    select count(*) as quantidade from rhsituacao_rhrubrica where rhrubrica_id = $idValido and rhsituacao_id = $rhsituacao_id
                ");
                $quantidade = $arrayDadosVerificar[0]->quantidade;

                if($quantidade==0){
                    echo '<br>Vai atualizar...';
                    $query = "update  $nomeTabela set $nomeCampo = $idValido where $nomeCampo = $idInvalido";
                    // vamos buscar na tabela, onde o nome do campo for igual ao idInvalido e alterar para o idValido
                    $dados = DB::select($query);
                } else {
                    echo '<br><br>Não vai atualizar. Vai excluir o registro que viraria a chave composta que já existe.';
                    echo '<br><br>Chave composta que já existe: rhrubrica_id: '.$idValido.' rhsituacao_id: '.$rhsituacao_id;
                    echo '<br>nome campo: '.$nomeCampo;
                    echo '<br>nome tabela: '.$nomeTabela;
                    echo '<br>id inválido: '.$idInvalido;
                    echo '<br>id valido: '.$idValido;
                    echo '<br>rhsituacao id: '.$rhsituacao_id;
                    $query = "delete from rhsituacao_rhrubrica where rhrubrica_id = $idInvalido and rhsituacao_id = $rhsituacao_id";
                    $dados = DB::select($query);
                }
                echo '<br>Rodou a seguinte query: ';
                echo '<br>'.$query;

            }
        }elseif($nomeTabela=='unidadesusers'){
            // precisamos saber se, ao excluírmos, a chave composta não será repetida, o que ocasionará erro
            $arrayDadosVerificar = DB::select("select * from unidadesusers where user_id = $idInvalido");
            foreach($arrayDadosVerificar as $objDadosVerificar){
                $userId = $objDadosVerificar->user_id;
                $unidadeId = $objDadosVerificar->unidade_id;
                // vamos verificar se ja tem o idValido e a unidade
                $arrayDadosVerificar = DB::select("
                    select count(*) as quantidade from unidadesusers where user_id = $idValido and unidade_id = $unidadeId
                ");
                $quantidade = $arrayDadosVerificar[0]->quantidade;
                if($quantidade==0){
                    // vamos buscar na tabela, onde o nome do campo for igual ao idInvalido e alterar para o idValido
                    $query = "update  $nomeTabela set $nomeCampo = $idValido where $nomeCampo = $idInvalido";
                    echo '<br>'.$query;
                    $dados = DB::select($query);
                } else {
                    echo '<br>A chave composta já existe.';
                    echo '<br>nome campo: '.$nomeCampo;
                    echo '<br>nome tabela: '.$nomeTabela;
                    echo '<br>id inválido: '.$idInvalido;
                    echo '<br>id valido: '.$idValido;
                    echo '<br>unidade id: '.$unidadeId;

                    $query = "update  $nomeTabela set $nomeCampo = $idValido where $nomeCampo = $idInvalido";
                    echo '<br>'.$query;
                    DB::select("delete from unidadesusers where user_id = $idInvalido and unidade_id = $unidadeId");

                }
            }
        } else {
            // vamos buscar na tabela, onde o nome do campo for igual ao idInvalido e alterar para o idValido
            $query = "update  $nomeTabela set $nomeCampo = $idValido where $nomeCampo = $idInvalido";
            echo '<br>'.$query;
            $dados = DB::select($query);
        }
    }
    public function getTodosNomesTabelas(){
        return $dados = DB::select("
            SELECT table_name
            FROM information_schema.columns
        ");
    }
    public function getNomesTabelasComByCampo($campo){
        return $dados = DB::select("
            SELECT table_name
            FROM information_schema.columns
            WHERE column_name = '$campo'
        ");
    }
    public function getIdRolesByNome($buscar){
        $dados = Role::select('id')
        ->where('name', '=', $buscar)
        ->orderBy('id')
        ->get();
        return $dados;
    }
    public function getIdOrgaosuperiorByNome($buscar){
        $dados = Orgaosuperior::select('id')
        ->where('nome', '=', $buscar)
        ->orderBy('id')
        ->get();
        return $dados;
    }
    public function getIdNaturezasubitemByDescricao($descricao){
        $dados = Naturezasubitem::select('id')
        ->where('descricao', '=', $descricao)
        ->orderBy('id')
        ->get();
        return $dados;
    }
    public function getIdRhrubricaByDescricao($descricao){
        $dados = Rhrubrica::select('id')
        ->where('descricao', '=', $descricao)
        ->orderBy('id')
        ->get();
        return $dados;
    }
    public function getIdNaturezadespesaByDescricao($descricao){
        $dados = Naturezadespesa::select('id')
        ->where('descricao', '=', $descricao)
        ->orderBy('id')
        ->get();
        return $dados;
    }
    public function getIdCodigoByDescricao($descricao){
        $dados = Codigo::select('id')
        ->where('descricao', '=', $descricao)
        ->orderBy('id')
        ->get();
        return $dados;
    }
    public function getIdCodigoitemByDescricao($descricao){
        $dados = Codigoitem::select('id', 'codigo_id')
        ->where('descricao', '=', $descricao)
        ->orderBy('id')
        ->get();
        return $dados;
    }
    public function getIdUserByEmail($email){
        $dados = User::select('id')
        ->where('email', '=', $email)
        ->orderBy('id')
        ->get();
        return $dados;
    }
    public function getIdAppVersionByPatch($patch){
        $dados = AppVersion::select('id')
        ->where('patch', '=', $patch)
        ->orderBy('id')
        ->get();
        return $dados;
    }
    public function getIdTipolistafaturaByNome($buscar){
        $dados = Tipolistafatura::select('id')
        ->where('nome', '=', $buscar)
        ->orderBy('id')
        ->get();
        return $dados;
    }
    public function getIdUserByCpf($cpf){
        $dados = User::select('id')
        ->where('cpf', '=', $cpf)
        ->orderBy('id')
        ->get();
        return $dados;
    }
    public function getIdJustificativafaturaByDescricao($buscar){
        $dados = Justificativafatura::select('id')
        ->where('descricao', '=', $buscar)
        ->orderBy('id')
        ->get();
        return $dados;
    }
    public function getIdCentrocustoByDescricao($buscar){
        $dados = Centrocusto::select('id')
        ->where('descricao', '=', $buscar)
        ->orderBy('id')
        ->get();
        return $dados;
    }
    public function getIdFornecedorByCpf($cpf){
        $dados = Fornecedor::select('id')
        ->where('cpf_cnpj_idgener', '=', $cpf)
        ->orderBy('id')
        ->get();
        return $dados;
    }
    public function getNomeOrgaosuperiorComNomeDuplicado(){
        $dados = Orgaosuperior::select('nome')
        ->groupBy('nome')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('nome')
        ->get();
        return $dados;
    }
    public function getNomeTipolistafaturaComNomeDuplicado(){
        $dados = Tipolistafatura::select('nome')
        ->groupBy('nome')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('nome')
        ->get();
        return $dados;
    }
    public function getNomeRolesComNomeDuplicado(){
        $dados = Role::select('name')
        ->groupBy('name')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('name')
        ->get();
        return $dados;
    }
    public function getEmailUsersComEmailDuplicado(){
        $dados = User::select('email')
        ->groupBy('email')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('email')
        ->get();
        return $dados;
    }
    public function getDescricaoRhrubricaComDescricaoDuplicada(){
        $dados = Rhrubrica::select('descricao')
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao')
        ->get();
        return $dados;
    }
    public function getDescricaoJustificativafaturaComDescricaoDuplicada(){
        $dados = Justificativafatura::select('descricao')
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao')
        ->get();
        return $dados;
    }
    public function getDescricaoNaturezasubitemComDescricaoDuplicada(){
        $dados = Naturezasubitem::select('descricao')
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao')
        ->get();
        return $dados;
    }
    public function getDescricaoNaturezadespesaComDescricaoDuplicada(){
        $dados = Naturezadespesa::select('descricao')
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao')
        ->get();
        return $dados;
    }
    public function getDescricaoCodigoComDescricaoDuplicada(){
        $dados = Codigo::select('descricao')
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao')
        ->get();
        return $dados;
    }
    public function getDescricaoCentrocustoComDescricaoDuplicada(){
        $dados = Centrocusto::select('descricao')
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao')
        ->get();
        return $dados;
    }
    public function getFaseApropriacaofasesComFaseDuplicada(){
        $dados = Apropriacaofases::select('fase')
        ->groupBy('fase')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('fase')
        ->get();
        return $dados;
    }
    public function getPatchAppVersionComPatchDuplicado(){
        $dados = AppVersion::select('patch')
        ->groupBy('patch')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('patch')
        ->get();
        return $dados;
    }
    public function getCpfUsersComCpfDuplicado(){
        $dados = User::select('cpf')
        ->groupBy('cpf')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('cpf')
        ->get();
        return $dados;
    }
    public function getDescricaoCodigoitemComDescricaoDuplicado(){
        $dados = Codigoitem::select('descricao')
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao')
        ->get();
        return $dados;
    }
    public function getCpfFornecedoresComCpfDuplicado(){
        $dados = Fornecedor::select('cpf_cnpj_idgener')
        ->groupBy('cpf_cnpj_idgener')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('cpf_cnpj_idgener')
        ->get();
        return $dados;
    }
    public function migrarFornecedores(){
        echo '<br>Preparando para tratar fornecedores...';
        // vamos buscar os fornecedores com cpf duplicado
        $fornecedoresComCpfDuplicado = self::getCpfFornecedoresComCpfDuplicado();
        $quantidadeFornecedoresComCpfDuplicado = count($fornecedoresComCpfDuplicado);
        echo '<br>Qtd encontrada: '.$quantidadeFornecedoresComCpfDuplicado;
        echo '<br>Atenção! Caso busque diretamente na base, lembrar do deleted at.';
        $cont = 0;
        foreach($fornecedoresComCpfDuplicado as $fornecedor){
            $cont++;
            $cpfDuplicado = $fornecedor->cpf_cnpj_idgener;
            echo '<br><br>'.$cont.' -> '.$cpfDuplicado.'<br>';
            //aqui já temos os cpf duplicados
            // para cada cpf vamos buscar o id invalido e o id válido
            $arrayIdsFornecedorByCpf = self::getIdFornecedorByCpf($cpfDuplicado);
            $quantidadeIds = count($arrayIdsFornecedorByCpf);
            if($quantidadeIds > 1){
                $idFornecedorValido = $arrayIdsFornecedorByCpf[0]->id;
                $idFornecedorInvalido = $arrayIdsFornecedorByCpf[1]->id;
                echo ' ==> '.$idFornecedorValido.' - '.$idFornecedorInvalido;
                if($idFornecedorInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm fornecedor_id
                    $arrayTabelasComFornecedorId = self::getNomesTabelasComByCampo('fornecedor_id');
                    echo '<br><br>Vai atualizar as seguintes tabelas: ';
                    foreach($arrayTabelasComFornecedorId as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br>'.$nomeTabela;
                    }
                    $contParar = 0;
                    foreach($arrayTabelasComFornecedorId as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br><br>Preparando para atualizar tabela : '.$nomeTabela;
                        // aqui já sabemos quais tabelas possuem o fornecedor_id
                        // vamos verificar se algum tem o fornecedor_id inválido
                        self::atualizarIdInvalidoParaIdValido('fornecedor_id', $nomeTabela, $idFornecedorInvalido, $idFornecedorValido);
                    }
                    // aqui já podemos excluir o fornecedor com id inválido
                    if(!self::excluirFornecedorComIdInvalido($idFornecedorInvalido)){echo 'erro(1)'; exit;}
                } else {
                    echo '<br>Não fez nada, pois o idInválido não era > 55000000.';
                }

            } else {
                echo '<br>Só retornou um.';
            }
        }
    }
    public function migrarUsersEmail(){
        echo '<br><br><br>Preparando para tratar users com emails duplicados...';
        // vamos buscar os users com email duplicado
        $usersComEmailDuplicado = self::getEmailUsersComEmailDuplicado();
        $quantidadeUsersComEmailDuplicado = count($usersComEmailDuplicado);
        echo '<br>Qtd encontrada: '.$quantidadeUsersComEmailDuplicado;
        echo '<br>Atenção! Caso busque diretamente na base, lembrar do deleted at.';
        $cont = 0;
        foreach($usersComEmailDuplicado as $user){
            $cont++;
            $emailDuplicado = $user->email;
            echo '<br><br>'.$cont.' -> '.$emailDuplicado.'<br>';
            //aqui já temos os emails duplicados
            // para cada eamil vamos buscar o id invalido e o id válido
            $arrayIdsUserByEmail = self::getIdUserByEmail($emailDuplicado);
            $quantidadeIds = count($arrayIdsUserByEmail);
            if($quantidadeIds > 1){
                $idUserValido = $arrayIdsUserByEmail[0]->id;
                $idUserInvalido = $arrayIdsUserByEmail[1]->id;
                echo ' ==> '.$idUserValido.' - '.$idUserInvalido;
                if($idUserInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm user_id
                    $arrayTabelasComUserId = self::getNomesTabelasComByCampo('user_id');
                    echo '<br><br>Vai atualizar as seguintes tabelas: ';
                    foreach($arrayTabelasComUserId as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br>'.$nomeTabela;
                    }
                    $contParar = 0;
                    foreach($arrayTabelasComUserId as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br><br>Preparando para atualizar tabela : '.$nomeTabela;
                        // aqui já sabemos quais tabelas possuem o fornecedor_id
                        // vamos verificar se algum tem o fornecedor_id inválido
                        self::atualizarIdInvalidoParaIdValido('user_id', $nomeTabela, $idUserInvalido, $idUserValido);
                    }
                    // aqui já podemos excluir o fornecedor com id inválido
                    if(!self::excluirUserComIdInvalido($idUserInvalido)){echo 'erro(1)'; exit;}
                } else {
                    echo '<br>Não fez nada, pois o idInválido não era > 55000000.';
                }
            } else {
                echo '<br>Só retornou um.';
            }
        }
    }
    public function migrarUsersCpf(){
        echo '<br><br><br>Preparando para tratar users com cpfs duplicados...';
        // vamos buscar os users com cpf duplicado
        $usersComCpfDuplicado = self::getCpfUsersComCpfDuplicado();
        $quantidadeUsersComCpfDuplicado = count($usersComCpfDuplicado);
        echo '<br>Qtd encontrada: '.$quantidadeUsersComCpfDuplicado;
        echo '<br>Atenção! Caso busque diretamente na base, lembrar do deleted at.';
        $cont = 0;
        foreach($usersComCpfDuplicado as $user){
            $cont++;
            $cpfDuplicado = $user->cpf;
            echo '<br><br>'.$cont.' -> '.$cpfDuplicado.'<br>';
            //aqui já temos os cpf duplicados
            // para cada cpf vamos buscar o id invalido e o id válido
            $arrayIdsUserByCpf = self::getIdUserByCpf($cpfDuplicado);
            $quantidadeIds = count($arrayIdsUserByCpf);
            if($quantidadeIds > 1){
                $idUserValido = $arrayIdsUserByCpf[0]->id;
                $idUserInvalido = $arrayIdsUserByCpf[1]->id;
                echo ' ==> '.$idUserValido.' - '.$idUserInvalido;
                if($idUserInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm user_id
                    $arrayTabelasComUserId = self::getNomesTabelasComByCampo('user_id');
                    echo '<br><br>Vai atualizar as seguintes tabelas: ';
                    foreach($arrayTabelasComUserId as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br>'.$nomeTabela;
                    }
                    $contParar = 0;
                    foreach($arrayTabelasComUserId as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br><br>Preparando para atualizar tabela : '.$nomeTabela;
                        // aqui já sabemos quais tabelas possuem o fornecedor_id
                        // vamos verificar se algum tem o fornecedor_id inválido
                        self::atualizarIdInvalidoParaIdValido('user_id', $nomeTabela, $idUserInvalido, $idUserValido);
                    }
                    // aqui já podemos excluir o fornecedor com id inválido
                    if(!self::excluirUserComIdInvalido($idUserInvalido)){echo 'erro(1)'; exit;}
                } else {
                    echo '<br>Não fez nada, pois o idInválido não era > 55000000.';
                }
            } else {
                echo '<br>Só retornou um.';
            }
        }
    }
    // várias verificações
    public function migrarCodigoitem(){
        echo '<br><br>Preparando para tratar codigoitem...';
        // vamos buscar os codigoitens com descricao duplicads
        $codigoItemComDescricaoDuplicada = self::getDescricaoCodigoitemComDescricaoDuplicado();
        $quantidade = count($codigoItemComDescricaoDuplicada);
        echo '<br>Qtd encontrada: '.$quantidade;
        echo '<br>Atenção! Caso busque diretamente na base, lembrar do deleted at.';
        $cont = 0;
        foreach($codigoItemComDescricaoDuplicada as $codigoitem){
            $cont++;
            $descricaoDuplicada = $codigoitem->descricao;
            echo '<br><br>'.$cont.' -> '.$descricaoDuplicada.'<br>';
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIdsCodigoitemByDescricao = self::getIdCodigoitemByDescricao($descricaoDuplicada);
            $quantidadeIds = count($arrayIdsCodigoitemByDescricao);
            if($quantidadeIds > 1){
                $idValido = $arrayIdsCodigoitemByDescricao[0]->id;
                $codigoIdValido = $arrayIdsCodigoitemByDescricao[0]->codigo_id;

                $idInvalido = $arrayIdsCodigoitemByDescricao[1]->id;
                $codigoIdInvalido = $arrayIdsCodigoitemByDescricao[1]->codigo_id;

                echo '<br>Dados válidos: id = '.$idValido.' codigoIdValido = '.$codigoIdValido;
                echo '<br>Dados inválidos: id = '.$idInvalido.' codigoIdInvalido = '.$codigoIdInvalido;

                if($idInvalido > 55000000 && ($codigoIdInvalido == $codigoIdValido) ){
                    echo '<br>Vai alterar...';
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm fornecedor_id
                    // $arrayTabelasComFornecedorId = self::getNomesTabelasComByCampo('fornecedor_id');

                    $arrayTabelas = array('orgaosubcategorias', 'contratohistorico', 'contratos');

                    echo '<br><br>Vai atualizar as seguintes tabelas: ';
                    foreach($arrayTabelas as $nomeTabela){
                        // $nomeTabela = $objDadosTabela->nomeTabela;
                        echo '<br>'.$nomeTabela;
                    }
                    $contParar = 0;
                    echo '<br>Atualizando tabela orgaosubcategorias...';
                    self::atualizarIdInvalidoParaIdValido('categoria_id', 'orgaosubcategorias', $idInvalido, $idValido);
                    echo '<br>Atualizando tabela contratohistorico...';
                    self::atualizarIdInvalidoParaIdValido('tipo_id', 'contratohistorico', $idInvalido, $idValido);
                    self::atualizarIdInvalidoParaIdValido('categoria_id', 'contratohistorico', $idInvalido, $idValido);
                    self::atualizarIdInvalidoParaIdValido('modalidade_id', 'contratohistorico', $idInvalido, $idValido);
                    echo '<br>Atualizando tabela contratos...';
                    self::atualizarIdInvalidoParaIdValido('categoria_id', 'contratos', $idInvalido, $idValido);
                    self::atualizarIdInvalidoParaIdValido('tipo_id', 'contratos', $idInvalido, $idValido);
                    self::atualizarIdInvalidoParaIdValido('modalidade_id', 'contratos', $idInvalido, $idValido);
                    // aqui já podemos excluir o registro com id inválido
                    if(!self::excluirCodigoitemComIdInvalido($idInvalido)){echo 'erro(1)'; exit;}
                } else if($idInvalido > 55000000 && ($codigoIdInvalido != $codigoIdValido) ){
                    echo '<br>Apesar do id ser > 55000000, o codigo_id é diferente - Não vai alterar.';
                } else{
                    echo '<br>O código é < 55000000.';
                }


            } else {
                echo '<br>Só retornou um.';
            }
        }
    }
    // app version não trata tabelas, só exclui
    public function migrarAppVersion(){
        echo '<br><br>Preparando para tratar app version...';
        // vamos buscar os duplicados
        $arrayDuplicados = self::getPatchAppVersionComPatchDuplicado();
        $quantidadeDuplicados = count($arrayDuplicados);
        echo '<br>Qtd encontrada: '.$quantidadeDuplicados;
        echo '<br>Atenção! Caso busque diretamente na base, lembrar do deleted at.';
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->patch;
            echo '<br><br>'.$cont.' -> '.$duplicado.'<br>';
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdAppVersionByPatch($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                echo ' ==> '.$idValido.' - '.$idInvalido;
                if($idInvalido > 55000000){
                    if(!self::excluirAppVersionComIdInvalido($idInvalido)){echo 'erro(1)'; exit;}
                } else {
                    echo '<br>Não fez nada, pois o idInválido não era > 55000000.';
                }
            } else {
                echo '<br>Só retornou um.';
            }
        }
    }
    public function migrarCentrocusto(){
        echo '<br><br>Preparando para tratar centrocusto...';
        // vamos buscar os duplicados
        $arrayDuplicados = self::getDescricaoCentrocustoComDescricaoDuplicada();
        $quantidadeDuplicados = count($arrayDuplicados);
        echo '<br>Qtd encontrada: '.$quantidadeDuplicados;
        echo '<br>Atenção! Caso busque diretamente na base, lembrar do deleted at.';
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->descricao;
            echo '<br><br>'.$cont.' -> '.$duplicado.'<br>';
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdCentrocustoByDescricao($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                echo ' ==> '.$idValido.' - '.$idInvalido;
                if($idInvalido > 55000000){
                    if(!self::excluirCentrocustoComIdInvalido($idInvalido)){echo 'erro(1)'; exit;}
                } else {
                    echo '<br>Não fez nada, pois o idInválido não era > 55000000.';
                }
            } else {
                echo '<br>Só retornou um.';
            }
        }
    }
    public function migrarCodigo(){
        echo '<br><br>Preparando para tratar codigo...';
        // vamos buscar os duplicados
        $arrayDuplicados = self::getDescricaoCodigoComDescricaoDuplicada();
        $quantidadeDuplicados = count($arrayDuplicados);
        echo '<br>Qtd encontrada: '.$quantidadeDuplicados;
        echo '<br>Atenção! Caso busque diretamente na base, lembrar do deleted at.';
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->descricao;
            echo '<br><br>'.$cont.' -> '.$duplicado.'<br>';
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdCodigoByDescricao($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                echo ' ==> '.$idValido.' - '.$idInvalido;
                if($idInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm codigo_id
                    $arrayTabelasComFornecedorId = self::getNomesTabelasComByCampo('codigo_id');
                    echo '<br><br>Vai atualizar as seguintes tabelas: ';
                    foreach($arrayTabelasComFornecedorId as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br>'.$nomeTabela;
                    }
                    $contParar = 0;
                    foreach($arrayTabelasComFornecedorId as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br><br>Preparando para atualizar tabela : '.$nomeTabela;
                        // aqui já sabemos quais tabelas possuem o codigo_id
                        // vamos verificar se algum tem o codigo_id inválido
                        self::atualizarIdInvalidoParaIdValido('codigo_id', $nomeTabela, $idInvalido, $idValido);
                    }
                    if(!self::excluirCodigoComIdInvalido($idInvalido)){echo 'erro(1)'; exit;}
                } else {
                    echo '<br>Não fez nada, pois o idInválido não era > 55000000.';
                }
            } else {
                echo '<br>Só retornou um.';
            }
        }
    }
    public function migrarJustificativafatura(){
        echo '<br><br>Preparando para tratar justificativa fatura...';
        // vamos buscar os duplicados
        $arrayDuplicados = self::getDescricaoJustificativafaturaComDescricaoDuplicada();
        $quantidadeDuplicados = count($arrayDuplicados);
        echo '<br>Qtd encontrada: '.$quantidadeDuplicados;
        echo '<br>Atenção! Caso busque diretamente na base, lembrar do deleted at.';
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->descricao;
            echo '<br><br>'.$cont.' -> '.$duplicado.'<br>';
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdJustificativafaturaByDescricao($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                echo ' ==> '.$idValido.' - '.$idInvalido;
                if($idInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm codigo_id
                    $arrayTabelas = self::getNomesTabelasComByCampo('justificativafatura_id');
                    echo '<br><br>Vai atualizar as seguintes tabelas: ';
                    foreach($arrayTabelas as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br>'.$nomeTabela;
                    }
                    $contParar = 0;
                    foreach($arrayTabelas as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br><br>Preparando para atualizar tabela : '.$nomeTabela;
                        // aqui já sabemos quais tabelas possuem o justificativafatura_id
                        // vamos verificar se algum tem o justificativafatura_id inválido
                        self::atualizarIdInvalidoParaIdValido('justificativafatura_id', $nomeTabela, $idInvalido, $idValido);
                    }
                    if(!self::excluirJustificativafaturaComIdInvalido($idInvalido)){echo 'erro(1)'; exit;}
                } else {
                    echo '<br>Não fez nada, pois o idInválido não era > 55000000.';
                }
            } else {
                echo '<br>Só retornou um.';
            }
        }
    }
    public function migrarTipolistafatura(){
        echo '<br><br>Preparando para tratar tipolistafatura...';
        // vamos buscar os duplicados
        $arrayDuplicados = self::getNomeTipolistafaturaComNomeDuplicado();
        $quantidadeDuplicados = count($arrayDuplicados);
        echo '<br>Qtd encontrada: '.$quantidadeDuplicados;
        echo '<br>Atenção! Caso busque diretamente na base, lembrar do deleted at.';
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->nome;
            echo '<br><br>'.$cont.' -> '.$duplicado.'<br>';
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdTipolistafaturaByNome($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                echo ' ==> '.$idValido.' - '.$idInvalido;
                if($idInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm codigo_id
                    $arrayTabelas = self::getNomesTabelasComByCampo('justificativafatura_id');
                    echo '<br><br>Vai atualizar as seguintes tabelas: ';
                    foreach($arrayTabelas as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br>'.$nomeTabela;
                    }
                    $contParar = 0;
                    foreach($arrayTabelas as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br><br>Preparando para atualizar tabela : '.$nomeTabela;
                        // aqui já sabemos quais tabelas possuem o justificativafatura_id
                        // vamos verificar se algum tem o justificativafatura_id inválido
                        self::atualizarIdInvalidoParaIdValido('tipolistafatura_id', $nomeTabela, $idInvalido, $idValido);
                    }
                    if(!self::excluirTipolistafaturaComIdInvalido($idInvalido)){echo 'erro(1)'; exit;}
                } else {
                    echo '<br>Não fez nada, pois o idInválido não era > 55000000.';
                }
            } else {
                echo '<br>Só retornou um.';
            }
        }
    }
    public function migrarNaturezadespesa(){
        echo '<br><br>Preparando para tratar naturezadespesa...';
        // vamos buscar os duplicados
        $arrayDuplicados = self::getDescricaoNaturezadespesaComDescricaoDuplicada();
        $quantidadeDuplicados = count($arrayDuplicados);
        echo '<br>Qtd encontrada: '.$quantidadeDuplicados;
        echo '<br>Atenção! Caso busque diretamente na base, lembrar do deleted at.';
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->descricao;
            echo '<br><br>'.$cont.' -> '.$duplicado.'<br>';
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdNaturezadespesaByDescricao($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                echo ' ==> '.$idValido.' - '.$idInvalido;
                if($idInvalido > 55000000){
                        // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm codigo_id
                    $arrayTabelas = self::getNomesTabelasComByCampo('naturezadespesa_id');
                    echo '<br><br>Vai atualizar as seguintes tabelas: ';
                    foreach($arrayTabelas as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br>'.$nomeTabela;
                    }
                    $contParar = 0;
                    foreach($arrayTabelas as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br><br>Preparando para atualizar tabela : '.$nomeTabela;
                        // aqui já sabemos quais tabelas possuem o justificativafatura_id
                        // vamos verificar se algum tem o justificativafatura_id inválido
                        self::atualizarIdInvalidoParaIdValido('naturezadespesa_id', $nomeTabela, $idInvalido, $idValido);
                    }

                    if(!self::excluirNaturezadespesaComIdInvalido($idInvalido)){echo 'erro(1)'; exit;}
                } else {
                    echo '<br>Não fez nada, pois o idInválido não era > 55000000.';
                }

            } else {
                echo '<br>Só retornou um.';
            }
        }
    }
    public function migrarNaturezasubitem(){
        echo '<br><br>Preparando para tratar naturezasubitem...';
        // vamos buscar os duplicados
        $arrayDuplicados = self::getDescricaoNaturezasubitemComDescricaoDuplicada();
        $quantidadeDuplicados = count($arrayDuplicados);
        echo '<br>Qtd encontrada: '.$quantidadeDuplicados;
        echo '<br>Atenção! Caso busque diretamente na base, lembrar do deleted at.';
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->descricao;
            echo '<br><br>'.$cont.' -> '.$duplicado.'<br>';
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdNaturezasubitemByDescricao($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                echo ' ==> '.$idValido.' - '.$idInvalido;
                if($idInvalido > 55000000){
                        // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm codigo_id
                    $arrayTabelas = self::getNomesTabelasComByCampo('naturezasubitem_id');
                    echo '<br><br>Vai atualizar as seguintes tabelas: ';
                    foreach($arrayTabelas as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br>'.$nomeTabela;
                    }
                    $contParar = 0;
                    foreach($arrayTabelas as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br><br>Preparando para atualizar tabela : '.$nomeTabela;
                        // aqui já sabemos quais tabelas possuem o id
                        // vamos verificar se algum tem o id inválido
                        self::atualizarIdInvalidoParaIdValido('naturezasubitem_id', $nomeTabela, $idInvalido, $idValido);
                    }

                    if(!self::excluirNaturezasubitemComIdInvalido($idInvalido)){echo 'erro(1)'; exit;}
                } else {
                    echo '<br>Não fez nada, pois o idInválido não era > 55000000.';
                }

            } else {
                echo '<br>Só retornou um.';
            }
        }
    }
    public function migrarOrgaosuperior(){
        echo '<br><br>Preparando para tratar orgaosuperior...';
        // vamos buscar os duplicados
        $arrayDuplicados = self::getNomeOrgaosuperiorComNomeDuplicado();
        $quantidadeDuplicados = count($arrayDuplicados);
        echo '<br>Qtd encontrada: '.$quantidadeDuplicados;
        echo '<br>Atenção! Caso busque diretamente na base, lembrar do deleted at.';
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->nome;
            echo '<br><br>'.$cont.' -> '.$duplicado.'<br>';
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdOrgaosuperiorByNome($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                echo ' ==> '.$idValido.' - '.$idInvalido;
                if($idInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm codigo_id
                    $arrayTabelas = self::getNomesTabelasComByCampo('orgaosuperior_id');
                    echo '<br><br>Vai atualizar as seguintes tabelas: ';
                    foreach($arrayTabelas as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br>'.$nomeTabela;
                    }
                    $contParar = 0;
                    foreach($arrayTabelas as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br><br>Preparando para atualizar tabela : '.$nomeTabela;
                        // aqui já sabemos quais tabelas possuem o id
                        // vamos verificar se algum tem o id inválido
                        self::atualizarIdInvalidoParaIdValido('orgaosuperior_id', $nomeTabela, $idInvalido, $idValido);
                    }

                    if(!self::excluirOrgaosuperiorComIdInvalido($idInvalido)){echo 'erro(1)'; exit;}
                } else {
                    echo '<br>Não fez nada, pois o idInválido não era > 55000000.';
                }

            } else {
                echo '<br>Só retornou um.';
            }
        }
    }
    public function migrarRhrubrica(){
        echo '<br><br>Preparando para tratar rhrubrica...';
        $nomeChaveEstrangeira = 'rhrubrica_id';
        // vamos buscar os duplicados
        $arrayDuplicados = self::getDescricaoRhrubricaComDescricaoDuplicada();
        $quantidadeDuplicados = count($arrayDuplicados);
        echo '<br>Qtd encontrada: '.$quantidadeDuplicados;
        echo '<br>Atenção! Caso busque diretamente na base, lembrar do deleted at.';
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->descricao;
            echo '<br><br>'.$cont.' -> '.$duplicado.'<br>';
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdRhrubricaByDescricao($duplicado);
            $quantidadeIds = count($arrayIds);
            echo '<br>Quantidade ids: '.$quantidadeIds;
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                if($idValido < 55000000 && $idInvalido < 55000000 && $quantidadeIds > 2){
                    while($quantidadeIds > 0 && $idInvalido < 55000000){
                        $idInvalido = $arrayIds[$quantidadeIds - 1]->id;
                        $quantidadeIds --;
                        echo '<br>id invalido = '.$idInvalido.' quantidade = '.$quantidadeIds;
                        // exit;
                    }
                }
                echo ' ==> '.$idValido.' - '.$idInvalido;
                if($idInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm codigo_id
                    $arrayTabelas = self::getNomesTabelasComByCampo($nomeChaveEstrangeira);
                    echo '<br><br>Vai atualizar as seguintes tabelas: ';
                    foreach($arrayTabelas as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br>'.$nomeTabela;
                    }
                    // exit;
                    $contParar = 0;
                    foreach($arrayTabelas as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br><br>Preparando para atualizar tabela : '.$nomeTabela;
                        // aqui já sabemos quais tabelas possuem o id
                        // vamos verificar se algum tem o id inválido
                        self::atualizarIdInvalidoParaIdValido($nomeChaveEstrangeira, $nomeTabela, $idInvalido, $idValido);
                    }

                    if(!self::excluirRhrubricaComIdInvalido($idInvalido)){echo 'erro(1)'; exit;}
                } else {
                    echo '<br>Não fez nada, pois o idInválido não era > 55000000.';
                }

            } else {
                echo '<br>Só retornou um.';
            }
        }
    }
    public function migrarRoles(){
        echo '<br><br>Preparando para tratar roles...';
        // vamos buscar os duplicados
        $arrayDuplicados = self::getNomeRolesComNomeDuplicado();
        $quantidadeDuplicados = count($arrayDuplicados);
        echo '<br>Qtd encontrada: '.$quantidadeDuplicados;
        echo '<br>Atenção! Caso busque diretamente na base, lembrar do deleted at.';
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->name;
            echo '<br><br>'.$cont.' -> '.$duplicado.'<br>';
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdRolesByNome($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                echo ' ==> '.$idValido.' - '.$idInvalido;
                if($idInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm codigo_id
                    $arrayTabelas = self::getNomesTabelasComByCampo('role_id');
                    echo '<br><br>Vai atualizar as seguintes tabelas: ';
                    foreach($arrayTabelas as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br>'.$nomeTabela;
                    }
                    $contParar = 0;
                    foreach($arrayTabelas as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        echo '<br><br>Preparando para atualizar tabela : '.$nomeTabela;
                        // aqui já sabemos quais tabelas possuem o codigo_id
                        // vamos verificar se algum tem o codigo_id inválido
                        self::atualizarIdInvalidoParaIdValido('role_id', $nomeTabela, $idInvalido, $idValido);
                    }
                    if(!self::excluirRolesComIdInvalido($idInvalido)){echo 'erro(1)'; exit;}
                } else {
                    echo '<br>Não fez nada, pois o idInválido não era > 55000000.';
                }
            } else {
                echo '<br>Só retornou um.';
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
