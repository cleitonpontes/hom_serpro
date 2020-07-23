<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Contratohistorico;
use App\User;
use App\Models\Fornecedor;
use App\Models\Codigoitem;

use Illuminate\Http\Request;

class MigracaotseaguController extends Controller
{
    public function tratardadosmigracaotseagu(){
        set_time_limit(0);
        echo '<br><br>iniciando migração...<br><br>';
        self::migrarFornecedores();
        self::migrarUsersCpf();
        self::migrarUsersEmail();
        self::migrarCodigoitem();
        echo '<br><br>migração finalizada.';
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
                $idInvalido = $arrayIdsCodigoitemByDescricao[1]->id;
                echo ' ==> '.$idValido.' - '.$idInvalido;
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

                // foreach($arrayTabelas as $nomeTabela){
                //     $contParar++;
                //     echo '<br><br>Preparando para atualizar tabela : '.$nomeTabela;
                //     // aqui já sabemos quais tabelas possuem o fornecedor_id
                //     // vamos verificar se algum tem o fornecedor_id inválido
                //     self::atualizarIdInvalidoParaIdValido('categoria_id', $nomeTabela, $idInvalido, $idValido);

                //     self::atualizarIdInvalidoParaIdValido('categoria_id', $nomeTabela, $idInvalido, $idValido);
                //     self::atualizarIdInvalidoParaIdValido('categoria_id', $nomeTabela, $idInvalido, $idValido);
                //     self::atualizarIdInvalidoParaIdValido('categoria_id', $nomeTabela, $idInvalido, $idValido);

                //     self::atualizarIdInvalidoParaIdValido('categoria_id', $nomeTabela, $idInvalido, $idValido);
                //     self::atualizarIdInvalidoParaIdValido('categoria_id', $nomeTabela, $idInvalido, $idValido);
                //     self::atualizarIdInvalidoParaIdValido('categoria_id', $nomeTabela, $idInvalido, $idValido);


                // }
                // aqui já podemos excluir o registro com id inválido
                if(!self::excluirCodigoitemComIdInvalido($idInvalido)){echo 'erro(1)'; exit;}
            } else {
                echo '<br>Só retornou um.';
            }
        }
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
        // vamos buscar na tabela, onde o nome do campo for igual ao idInvalido e alterar para o idValido
        $query = "update  $nomeTabela set $nomeCampo = $idValido where $nomeCampo = $idInvalido";
        echo '<br>'.$query;
        $dados = DB::select($query);
        // return true;
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
    public function getIdCodigoitemByDescricao($descricao){
        $dados = Codigoitem::select('id')
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
    public function getIdUserByCpf($cpf){
        $dados = User::select('id')
        ->where('cpf', '=', $cpf)
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
    // retorna os cpf dos users que possuem email duplicado na base
    public function getEmailUsersComEmailDuplicado(){
        $dados = User::select('email')
        ->groupBy('email')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('email')
        ->get();
        return $dados;
    }
    // retorna os cpf dos users que possuem cpf duplicado na base
    public function getCpfUsersComCpfDuplicado(){
        $dados = User::select('cpf')
        ->groupBy('cpf')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('cpf')
        ->get();
        return $dados;
    }
    // retorna as descricoes dos codigoitem que possuem descricao duplicado na base
    public function getDescricaoCodigoitemComDescricaoDuplicado(){
        $dados = Codigoitem::select('descricao')
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao')
        ->get();
        return $dados;
    }
    // retorna os cpf dos fornecedores que possuem cpf duplicado na base
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
                echo '<br>Só retornou um.';
            }
        }
    }

}
