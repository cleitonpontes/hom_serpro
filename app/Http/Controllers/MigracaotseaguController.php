<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

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

use Illuminate\Http\Request;

class MigracaotseaguController extends Controller
{
    public function tratardadosmigracaotseagu(){
        set_time_limit(0);
        echo '<br><br>iniciando migração...<br><br>';
        // self::migrarFornecedores();
        // self::migrarUsersCpf();
        // self::migrarUsersEmail();
        self::migrarCodigoitem();
        // self::migrarAppVersion();
        // self::migrarCentrocusto();
        // self::migrarCodigo();
        // self::migrarJustificativafatura();
        // self::migrarTipolistafatura();
        // self::migrarNaturezadespesa();
        // self::migrarNaturezasubitem();
        // self::migrarOrgaosuperior();
        echo '<br><br>migração finalizada.';
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
        //buscar aonde o id = idValido e verificar se a unidade é igual a unidade
        if($nomeTabela=='unidadesusers'){
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
    public function getEmailUsersComEmailDuplicado(){
        $dados = User::select('email')
        ->groupBy('email')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('email')
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
}
