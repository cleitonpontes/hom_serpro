<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
use App\Models\Rhrubrica;
use App\Models\Contrato;

use Spatie\Permission\Models\Role;



class tratarDadosMigracaoTseAgu extends Command
{
    private $quantidadeExclusoes = 0;
    private $quantidadeVerificacoes = 0;
    //
    private $quantidadeExclusoesFornecedores = 0;
    private $quantidadeFornecedoresComCpfDuplicado = 0;

    private $quantidadeExclusoesUsers = 0;
    private $quantidadeUsersComEmailDuplicado = 0;
    private $quantidadeUsersComCpfDuplicado = 0;

    private $quantidadeExclusoesCodigoitem = 0;
    private $quantidadeCodigoitemEncontrado = 0;

    private $quantidadeExclusoesAppversion = 0;
    private $quantidadeAppversionDuplicados = 0;

    private $quantidadeExclusoesCentrocusto = 0;
    private $quantidadeCentrocustoDuplicados = 0;

    private $quantidadeExclusoesCodigo = 0;
    private $quantidadeCodigoDuplicados = 0;

    private $quantidadeExclusoesJustificativafatura = 0;
    private $quantidadeJustificativafaturaDuplicados = 0;

    private $quantidadeExclusoesTipolistafatura = 0;
    private $quantidadeTipolistafaturaDuplicados = 0;

    private $quantidadeExclusoesNaturezadespesa = 0;
    private $quantidadeNaturezadespesaDuplicados = 0;

    private $quantidadeExclusoesNaturezasubitem = 0;
    private $quantidadeNaturezasubitemDuplicados = 0;

    private $quantidadeExclusoesOrgaosuperior = 0;
    private $quantidadeOrgaosuperiorDuplicados = 0;

    private $quantidadeExclusoesRhrubrica = 0;
    private $quantidadeRhrubricaDuplicados = 0;

    private $quantidadeExclusoesRoles = 0;
    private $quantidadeRolesDuplicados = 0;

    private $quantidadeExclusoesContrato = 0;
    private $quantidadeContratoDuplicados = 0;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:tratarDadosMigracaoTseAgu {nomeBancoDeDados}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trata os dados da base, após sua migração';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0);
        // DB::beginTransaction();

        $nomeBancoDeDados = $this->argument('nomeBancoDeDados');

        $this->line('-----------------------------------------------------------------------');
        $this->error('Iniciando transação para tratamento dos dados para o banco '.$nomeBancoDeDados.'...');
        $this->line('-----------------------------------------------------------------------');
        self::rodarScript1($nomeBancoDeDados);

            // fornecedores
            $this->line('-----------------------------------------------------------------------');
            $this->line('Iniciando verificação de fornecedores...');
            $this->line('-----------------------------------------------------------------------');
            while( $this->quantidadeExclusoesFornecedores != 0 || $this->quantidadeVerificacoes==0 ){
                $this->quantidadeVerificacoes++;
                $this->quantidadeExclusoesFornecedores = 0;
                $this->line('Verificação '.$this->quantidadeVerificacoes);
                self::migrarFornecedores();
            }
            $this->quantidadeVerificacoes = 0;
            // users
            $this->line('-----------------------------------------------------------------------');
            $this->line('Iniciando verificação de users...');
            $this->line('-----------------------------------------------------------------------');
            while( $this->quantidadeExclusoesUsers != 0 || $this->quantidadeVerificacoes==0 ){
                $this->quantidadeVerificacoes++;
                $this->quantidadeExclusoesUsers = 0;
                $this->line('Verificação '.$this->quantidadeVerificacoes);
                self::migrarUsersCpf();
                self::migrarUsersEmail();
            }
            $this->quantidadeVerificacoes = 0;
            // codigoitem
            $this->line('-----------------------------------------------------------------------');
            $this->line('Iniciando verificação de codigoitem...');
            $this->line('-----------------------------------------------------------------------');
            while( $this->quantidadeExclusoesCodigoitem != 0 || $this->quantidadeVerificacoes==0 ){
                $this->quantidadeVerificacoes++;
                $this->quantidadeExclusoesCodigoitem = 0;
                $this->line('Verificação '.$this->quantidadeVerificacoes);
                self::migrarCodigoitem();
            }
            $this->quantidadeVerificacoes = 0;
            // appversion
            $this->line('-----------------------------------------------------------------------');
            $this->line('Iniciando verificação de appversion...');
            $this->line('-----------------------------------------------------------------------');
            while( $this->quantidadeExclusoesAppversion != 0 || $this->quantidadeVerificacoes==0 ){
                $this->quantidadeVerificacoes++;
                $this->quantidadeExclusoesAppversion = 0;
                $this->line('Verificação '.$this->quantidadeVerificacoes);
                self::migrarAppVersion();
            }
            $this->quantidadeVerificacoes = 0;
            // centrocusto
            $this->line('-----------------------------------------------------------------------');
            $this->line('Iniciando verificação de centrocusto...');
            $this->line('-----------------------------------------------------------------------');
            while( $this->quantidadeExclusoesCentrocusto != 0 || $this->quantidadeVerificacoes==0 ){
                $this->quantidadeVerificacoes++;
                $this->quantidadeExclusoesCentrocusto = 0;
                $this->line('Verificação '.$this->quantidadeVerificacoes);
                self::migrarCentrocusto();
            }
            $this->quantidadeVerificacoes = 0;
            // codigo
            $this->line('-----------------------------------------------------------------------');
            $this->line('Iniciando verificação de codigo...');
            $this->line('-----------------------------------------------------------------------');
            while( $this->quantidadeExclusoesCodigo != 0 || $this->quantidadeVerificacoes==0 ){
                $this->quantidadeVerificacoes++;
                $this->quantidadeExclusoesCodigo = 0;
                $this->line('Verificação '.$this->quantidadeVerificacoes);
                self::migrarCodigo();
            }
            $this->quantidadeVerificacoes = 0;
            // Justificativafatura
            $this->line('-----------------------------------------------------------------------');
            $this->line('Iniciando verificação de Justificativafatura...');
            $this->line('-----------------------------------------------------------------------');
            while( $this->quantidadeExclusoesJustificativafatura != 0 || $this->quantidadeVerificacoes==0 ){
                $this->quantidadeVerificacoes++;
                $this->quantidadeExclusoesJustificativafatura = 0;
                $this->line('Verificação '.$this->quantidadeVerificacoes);
                self::migrarJustificativafatura();
            }
            $this->quantidadeVerificacoes = 0;
            // Tipolistafatura
            $this->line('-----------------------------------------------------------------------');
            $this->line('Iniciando verificação de Tipolistafatura...');
            $this->line('-----------------------------------------------------------------------');
            while( $this->quantidadeExclusoesTipolistafatura != 0 || $this->quantidadeVerificacoes==0 ){
                $this->quantidadeVerificacoes++;
                $this->quantidadeExclusoesTipolistafatura = 0;
                $this->line('Verificação '.$this->quantidadeVerificacoes);
                self::migrarTipolistafatura();
            }
            $this->quantidadeVerificacoes = 0;
            // Naturezadespesa
            $this->line('-----------------------------------------------------------------------');
            $this->line('Iniciando verificação de Naturezadespesa...');
            $this->line('-----------------------------------------------------------------------');
            while( $this->quantidadeExclusoesNaturezadespesa != 0 || $this->quantidadeVerificacoes==0 ){
                $this->quantidadeVerificacoes++;
                $this->quantidadeExclusoesNaturezadespesa = 0;
                $this->line('Verificação '.$this->quantidadeVerificacoes);
                self::migrarNaturezadespesa();
            }
            $this->quantidadeVerificacoes = 0;
            // Naturezasubitem
            $this->line('-----------------------------------------------------------------------');
            $this->line('Iniciando verificação de Naturezasubitem...');
            $this->line('-----------------------------------------------------------------------');
            while( $this->quantidadeExclusoesNaturezasubitem != 0 || $this->quantidadeVerificacoes==0 ){
                $this->quantidadeVerificacoes++;
                $this->quantidadeExclusoesNaturezasubitem = 0;
                $this->line('Verificação '.$this->quantidadeVerificacoes);
                self::migrarNaturezasubitem();
            }
            $this->quantidadeVerificacoes = 0;
            // Orgaosuperior
            $this->line('-----------------------------------------------------------------------');
            $this->line('Iniciando verificação de Orgaosuperior...');
            $this->line('-----------------------------------------------------------------------');
            while( $this->quantidadeExclusoesOrgaosuperior != 0 || $this->quantidadeVerificacoes==0 ){
                $this->quantidadeVerificacoes++;
                $this->quantidadeExclusoesOrgaosuperior = 0;
                $this->line('Verificação '.$this->quantidadeVerificacoes);
                self::migrarOrgaosuperior();
            }
            $this->quantidadeVerificacoes = 0;
            // Rhrubrica
            $this->line('-----------------------------------------------------------------------');
            $this->line('Iniciando verificação de Rhrubrica...');
            $this->line('-----------------------------------------------------------------------');
            while( $this->quantidadeExclusoesRhrubrica != 0 || $this->quantidadeVerificacoes==0 ){
                $this->quantidadeVerificacoes++;
                $this->quantidadeExclusoesRhrubrica = 0;
                $this->line('Verificação '.$this->quantidadeVerificacoes);
                self::migrarRhrubrica();
            }
            $this->quantidadeVerificacoes = 0;
            // Roles
            $this->line('-----------------------------------------------------------------------');
            $this->line('Iniciando verificação de Roles...');
            $this->line('-----------------------------------------------------------------------');
            while( $this->quantidadeExclusoesRoles != 0 || $this->quantidadeVerificacoes==0 ){
                $this->quantidadeVerificacoes++;
                $this->quantidadeExclusoesRoles = 0;
                $this->line('Verificação '.$this->quantidadeVerificacoes);
                self::migrarRoles();
            }
            $this->quantidadeVerificacoes = 0;
            // Contrato
            $this->line('-----------------------------------------------------------------------');
            $this->line('Iniciando verificação de Contrato...');
            $this->line('-----------------------------------------------------------------------');
            while( $this->quantidadeExclusoesContrato != 0 || $this->quantidadeVerificacoes==0 ){
                $this->quantidadeVerificacoes++;
                $this->quantidadeExclusoesContrato = 0;
                $this->line('Verificação '.$this->quantidadeVerificacoes);
                self::migrarContrato();
            }
            $this->quantidadeVerificacoes = 0;

        //
        $this->line('---------------------------------Script 3--------------------------------------');
        self::rodarScript3($nomeBancoDeDados);
        $this->line('----------------------------------------------------------------------------');
        // DB::commit();
        $this->error('Processamento Finalizado.');
        $this->line('----------------------------------------------------------------------------');

    }

    public function rodarScript1($nomeBancoDeDados){
        // alterado.
        $this->line('***************************copiar arquivos seed...******************************');
        exec('cp -rf database/migracao_tse_agu/seeders_empacotados/* database/seeds/');
        $this->line('***************************instalar composer...******************************');
        exec('curl -s https://getcomposer.org/installer | php');
        $this->line('***************************instalar dependências...******************************');
        exec('php -d memory_limit=-1 composer.phar install');
        $this->line('***************************gerar chave...******************************');
        exec('php artisan key:generate');
        $this->line('***************************gerar autoload...******************************');
        exec('php composer.phar dump-autoload');
        $this->info('*************************************script 1******************************************');
        // exec('psql -U postgres -d '.$nomeBancoDeDados.' -1 -f database/migracao_tse_agu/script1_producao.sql');
        $sql = file_get_contents('database/migracao_tse_agu/script1_producao.sql');
        DB::unprepared($sql);
        $this->info('**********************************script 1_2*********************************************');
        // exec('psql -U postgres -d '.$nomeBancoDeDados.' -1 -f database/migracao_tse_agu/script1_2_producao.sql');
        $sql = file_get_contents('database/migracao_tse_agu/script1_2_producao.sql');
        DB::unprepared($sql);
        $this->info('*********************************script 1_3**********************************************');
        // exec('psql -U postgres -d '.$nomeBancoDeDados.' -1 -f database/migracao_tse_agu/script1_3_producao.sql');
        $sql = file_get_contents('database/migracao_tse_agu/script1_3_producao.sql');
        DB::unprepared($sql);
        $this->info('*********************************** seed ********************************************');
        exec('php artisan db:seed');
        $this->info('Aguardando conclusão do seed...');
        sleep(35);
        $this->info('************************************ script 2 *******************************************');
        // exec('psql -U postgres -d '.$nomeBancoDeDados.' -1 -f database/migracao_tse_agu/script2_producao.sql');
        $sql = file_get_contents('database/migracao_tse_agu/script2_producao.sql');
        DB::unprepared($sql);
    }


    public function rodarScript3($nomeBancoDeDados){
        //exec('psql -U postgres -d '.$nomeBancoDeDados.' -1 -f database/migracao_tse_agu/script3_producao.sql');
        $sql = file_get_contents('database/migracao_tse_agu/script3_producao.sql');
        DB::unprepared($sql);
    }

    //
    public function excluirRolesComIdInvalido($idExcluir){
        $this->info('Preparando para excluir roles id = '.$idExcluir);
        if(Role::where('id', $idExcluir)->delete()){$this->quantidadeExclusoesRoles++; return true;}
        else{return false;}
    }
    public function excluirRhrubricaComIdInvalido($idExcluir){
        $this->info('Preparando para excluir rhrubrica id = '.$idExcluir);
        if(Rhrubrica::where('id', $idExcluir)->delete()){$this->quantidadeExclusoesRhrubrica++; return true;}
        else{return false;}
    }
    public function excluirOrgaosuperiorComIdInvalido($idExcluir){
        $this->info('Preparando para excluir orgaosuperior id = '.$idExcluir);
        if(Orgaosuperior::where('id', $idExcluir)->delete()){$this->quantidadeExclusoesOrgaosuperior++; return true;}
        else{return false;}
    }
    public function excluirNaturezasubitemComIdInvalido($idExcluir){
        $this->info('Preparando para excluir naturezasubitem id = '.$idExcluir);
        if(Naturezasubitem::where('id', $idExcluir)->delete()){$this->quantidadeExclusoesNaturezasubitem++; return true;}
        else{return false;}
    }
    public function excluirNaturezadespesaComIdInvalido($idExcluir){
        $this->info('Preparando para excluir naturezadespesa id = '.$idExcluir);
        if(Naturezadespesa::where('id', $idExcluir)->delete()){$this->quantidadeExclusoesNaturezadespesa++; return true;}
        else{return false;}
    }
    public function excluirTipolistafaturaComIdInvalido($idExcluir){
        $this->info('Preparando para excluir tipolistafatura id = '.$idExcluir);
        if(Tipolistafatura::where('id', $idExcluir)->delete()){$this->quantidadeExclusoesTipolistafatura++; return true;}
        else{return false;}
    }
    public function excluirJustificativafaturaComIdInvalido($idExcluir){
        $this->info('Preparando para excluir justificativafatura id = '.$idExcluir);
        if(Justificativafatura::where('id', $idExcluir)->delete()){$this->quantidadeExclusoesJustificativafatura++; return true;}
        else{return false;}
    }
    public function excluirCodigoComIdInvalido($idExcluir){
        $this->info('Preparando para excluir codigo id = '.$idExcluir);
        if(Codigo::where('id', $idExcluir)->delete()){$this->quantidadeExclusoesCodigoitem++; return true;}
        else{return false;}
    }
    public function excluirCentrocustoComIdInvalido($idExcluir){
        $this->info('Preparando para excluir centrocusto id = '.$idExcluir);
        if(Centrocusto::where('id', $idExcluir)->delete()){$this->quantidadeExclusoesCentrocusto++; return true;}
        else{return false;}
    }
    public function excluirAppVersionComIdInvalido($idExcluir){
        $this->info('Preparando para excluir app version id = '.$idExcluir);
        if(AppVersion::where('id', $idExcluir)->delete()){$this->quantidadeExclusoesAppversion++; return true;}
        else{return false;}
    }
    public function excluirUserComIdInvalido($idExcluir){
        $this->info('Preparando para excluir user id = '.$idExcluir);
        if(User::where('id', $idExcluir)->delete()){$this->quantidadeExclusoesUsers++; return true;}
        else{return false;}
    }
    public function excluirCodigoitemComIdInvalido($idExcluir){
        $this->info('Preparando para excluir codigoitem id = '.$idExcluir);
        if(Codigoitem::where('id', $idExcluir)->delete()){$this->quantidadeExclusoesCodigo++; return true;}
        else{return false;}
    }
    public function excluirContratoComIdInvalido($idExcluir){
        $this->info('Preparando para excluir contrato id = '.$idExcluir);
        if(Contrato::where('id', $idExcluir)->delete()){$this->quantidadeExclusoesContrato++; return true;}
        else{return false;}
    }
    public function excluirFornecedorComIdInvalido($idExcluir){
        $this->info('Preparando para excluir fornecedor id = '.$idExcluir);
        if(Fornecedor::where('id', $idExcluir)->delete()){$this->quantidadeExclusoesFornecedores++; return true;}
        else{return false;}
    }

    //
    public function atualizarIdInvalidoParaIdValido($nomeCampo, $nomeTabela, $idInvalido, $idValido){
        $this->info( 'Entrou em atualizarIdInvalidoParaIdValido... tabela: '.$nomeTabela.' id valido: '.$idValido.'id inválido: '.$idInvalido);

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
                    $this->info( 'Vai atualizar...');
                    $query = "update  $nomeTabela set $nomeCampo = $idValido where $nomeCampo = $idInvalido";
                    // vamos buscar na tabela, onde o nome do campo for igual ao idInvalido e alterar para o idValido
                    $dados = DB::select($query);
                } else {
                    $this->info( 'Não vai atualizar. Vai excluir o registro que viraria a chave composta que já existe.');
                    $this->info( 'Chave composta que já existe: rhrubrica_id: '.$idValido.' rhsituacao_id: '.$rhsituacao_id);
                    $this->info( 'nome campo: '.$nomeCampo);
                    $this->info( 'nome tabela: '.$nomeTabela);
                    $this->info( 'id inválido: '.$idInvalido);
                    $this->info( 'id valido: '.$idValido);
                    $this->info( 'rhsituacao id: '.$rhsituacao_id);
                    $query = "delete from rhsituacao_rhrubrica where rhrubrica_id = $idInvalido and rhsituacao_id = $rhsituacao_id";
                    $dados = DB::select($query);
                }
                $this->info( 'Rodou a seguinte query: ');
                $this->info( $query);

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
                    $this->info( $query);
                    $dados = DB::select($query);
                } else {
                    $this->info( 'A chave composta já existe.');
                    $this->info( 'nome campo: '.$nomeCampo);
                    $this->info( 'nome tabela: '.$nomeTabela);
                    $this->info( 'id inválido: '.$idInvalido);
                    $this->info( 'id valido: '.$idValido);
                    $this->info( 'unidade id: '.$unidadeId);

                    $query = "update  $nomeTabela set $nomeCampo = $idValido where $nomeCampo = $idInvalido";
                    $this->info($query);
                    DB::select("delete from unidadesusers where user_id = $idInvalido and unidade_id = $unidadeId");

                }
            }
        } else {
            // vamos buscar na tabela, onde o nome do campo for igual ao idInvalido e alterar para o idValido
            $query = "update  $nomeTabela set $nomeCampo = $idValido where $nomeCampo = $idInvalido";
            $this->info($query);
            $dados = DB::select($query);
        }
    }
    public function getTodosNomesTabelas(){
        return $dados = DB::select("
            SELECT table_name
            FROM information_schema.columns
        ");
    }

    //
    public function getNomesTabelasComByCampo($campo){
        return $dados = DB::select("
            SELECT table_name
            FROM information_schema.columns
            WHERE column_name = '$campo'
        ");
    }

    //
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
    // retorna vários campos para várias verificações
    public function getIdContratoByProcesso($buscar){
        $dados = Contrato::select('id', 'numero', 'fornecedor_id', 'unidade_id', 'tipo_id', 'categoria_id', 'processo', 'objeto', 'licitacao_numero', 'valor_global', 'data_assinatura')
        ->where('processo', '=', $buscar)
        ->orderBy('id')
        ->get();
        return $dados;
    }
    // retorna vários campos para várias verificações
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
    public function getIdRolesByName($buscar){
        $dados = Role::select('id')
        ->where('name', '=', $buscar)
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

    //
    public function getProcessoContratoComProcessoDuplicadoDesc(){
        $this->info('Buscar por desc...');
        $dados = Contrato::select('processo')
        // ->where('id', '>', 50000000)
        ->groupBy('processo')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('processo', 'desc')
        ->get();
        return $dados;
    }
    public function getProcessoContratoComProcessoDuplicado(){
        $dados = Contrato::select('processo')
        // ->where('id', '>', 50000000)
        ->groupBy('processo')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('processo')
        ->get();
        return $dados;
    }
    public function getNomeRolesComNomeDuplicadoDesc(){
        $this->info('Buscar por desc...');
        $dados = Role::select('name')
        // ->where('id', '>', 50000000)
        ->groupBy('name')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('name', 'desc')
        ->get();
        return $dados;
    }
    public function getNomeRolesComNomeDuplicado(){
        $dados = Role::select('name')
        // ->where('id', '>', 50000000)
        ->groupBy('name')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('name')
        ->get();
        return $dados;
    }
    public function getNomeOrgaosuperiorComNomeDuplicadoDesc(){
        $this->info('Buscar por desc...');
        $dados = Orgaosuperior::select('nome')
        // ->where('id', '>', 50000000)
        ->groupBy('nome')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('nome', 'desc')
        ->get();
        return $dados;
    }
    public function getNomeOrgaosuperiorComNomeDuplicado(){
        $dados = Orgaosuperior::select('nome')
        // ->where('id', '>', 50000000)
        ->groupBy('nome')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('nome')
        ->get();
        return $dados;
    }
    public function getNomeTipolistafaturaComNomeDuplicadoDesc(){
        $this->info('Buscar por desc...');
        $dados = Tipolistafatura::select('nome')
        // ->where('id', '>', 50000000)
        ->groupBy('nome')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('nome', 'desc')
        ->get();
        return $dados;
    }
    public function getNomeTipolistafaturaComNomeDuplicado(){
        $dados = Tipolistafatura::select('nome')
        // ->where('id', '>', 50000000)
        ->groupBy('nome')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('nome')
        ->get();
        return $dados;
    }
    public function getEmailUsersComEmailDuplicadoDesc(){
        $this->info('Buscar por desc...');
        $dados = User::select('email')
        // ->where('id', '>', 50000000)
        ->groupBy('email')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('email', 'desc')
        ->get();
        return $dados;
    }
    public function getEmailUsersComEmailDuplicado(){
        $dados = User::select('email')
        // ->where('id', '>', 50000000)
        ->groupBy('email')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('email')
        ->get();
        return $dados;
    }
    public function getFaseApropriacaofasesComFaseDuplicadaDesc(){
        $this->info('Buscar por desc...');
        $dados = Apropriacaofases::select('fase')
        // ->where('id', '>', 50000000)
        ->groupBy('fase')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('fase', 'desc')
        ->get();
        return $dados;
    }
    public function getFaseApropriacaofasesComFaseDuplicada(){
        $dados = Apropriacaofases::select('fase')
        // ->where('id', '>', 50000000)
        ->groupBy('fase')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('fase')
        ->get();
        return $dados;
    }
    public function getDescricaoRhrubricaComDescricaoDuplicadaDesc(){
        $this->info('Buscar por desc...');
        $dados = Rhrubrica::select('descricao')
        // ->where('id', '>', 50000000)
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao', 'desc')
        ->get();
        return $dados;
    }
    public function getDescricaoRhrubricaComDescricaoDuplicada(){
        $dados = Rhrubrica::select('descricao')
        // ->where('id', '>', 50000000)
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao')
        ->get();
        return $dados;
    }
    public function getDescricaoJustificativafaturaComDescricaoDuplicadaDesc(){
        $this->info('Buscar por desc...');
        $dados = Justificativafatura::select('descricao')
        // ->where('id', '>', 50000000)
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao', 'desc')
        ->get();
        return $dados;
    }
    public function getDescricaoJustificativafaturaComDescricaoDuplicada(){
        $dados = Justificativafatura::select('descricao')
        // ->where('id', '>', 50000000)
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao')
        ->get();
        return $dados;
    }
    public function getDescricaoNaturezasubitemComDescricaoDuplicadaDesc(){
        $this->info('Buscar por desc...');
        $dados = Naturezasubitem::select('descricao')
        // ->where('id', '>', 50000000)
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao', 'desc')
        ->get();
        return $dados;
    }
    public function getDescricaoNaturezasubitemComDescricaoDuplicada(){
        $dados = Naturezasubitem::select('descricao')
        // ->where('id', '>', 50000000)
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao')
        ->get();
        return $dados;
    }
    public function getDescricaoNaturezadespesaComDescricaoDuplicadaDesc(){
        $this->info('Buscar por desc...');
        $dados = Naturezadespesa::select('descricao')
        // ->where('id', '>', 50000000)
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao', 'desc')
        ->get();
        return $dados;
    }
    public function getDescricaoNaturezadespesaComDescricaoDuplicada(){
        $dados = Naturezadespesa::select('descricao')
        // ->where('id', '>', 50000000)
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao')
        ->get();
        return $dados;
    }
    public function getDescricaoCodigoComDescricaoDuplicadaDesc(){
        $this->info('Buscar por desc...');
        $dados = Codigo::select('descricao')
        // ->where('id', '>', 50000000)
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao', 'desc')
        ->get();
        return $dados;
    }
    public function getDescricaoCodigoComDescricaoDuplicada(){
        $dados = Codigo::select('descricao')
        // ->where('id', '>', 50000000)
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao')
        ->get();
        return $dados;
    }
    public function getDescricaoCentrocustoComDescricaoDuplicadaDesc(){
        $this->info('Buscar por desc...');
        $dados = Centrocusto::select('descricao')
        // ->where('id', '>', 50000000)
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao', 'desc')
        ->get();
        return $dados;
    }
    public function getDescricaoCentrocustoComDescricaoDuplicada(){
        $dados = Centrocusto::select('descricao')
        // ->where('id', '>', 50000000)
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao')
        ->get();
        return $dados;
    }
    public function getPatchAppVersionComPatchDuplicadoDesc(){
        $this->info('Buscar por desc...');
        $dados = AppVersion::select('patch')
        // ->where('id', '>', 50000000)
        ->groupBy('patch')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('patch', 'desc')
        ->get();
        return $dados;
    }
    public function getPatchAppVersionComPatchDuplicado(){
        $dados = AppVersion::select('patch')
        // ->where('id', '>', 50000000)
        ->groupBy('patch')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('patch')
        ->get();
        return $dados;
    }
    public function getCpfUsersComCpfDuplicadoDesc(){
        $this->info('Buscar por desc...');
        $dados = User::select('cpf')
        // ->where('id', '>', 50000000)
        ->groupBy('cpf')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('cpf', 'desc')
        ->get();
        return $dados;
    }
    public function getCpfUsersComCpfDuplicado(){
        $dados = User::select('cpf')
        // ->where('id', '>', 50000000)
        ->groupBy('cpf')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('cpf')
        ->get();
        return $dados;
    }
    public function getDescricaoCodigoitemComDescricaoDuplicadoDesc(){
        $this->info('Buscar por desc...');
        $dados = Codigoitem::select('descricao')
        // ->where('id', '>', 50000000)
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao', 'desc')
        ->get();
        return $dados;
    }
    public function getDescricaoCodigoitemComDescricaoDuplicado(){
        $dados = Codigoitem::select('descricao')
        // ->where('id', '>', 50000000)
        ->groupBy('descricao')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('descricao')
        ->get();
        return $dados;
    }
    public function getCpfFornecedoresComCpfDuplicadoDesc(){
        $this->info('Buscar por desc...');
        $dados = Fornecedor::select('cpf_cnpj_idgener')
        // ->where('id', '>', 50000000)
        ->groupBy('cpf_cnpj_idgener')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('cpf_cnpj_idgener', 'desc')
        ->get();
        return $dados;
    }
    public function getCpfFornecedoresComCpfDuplicado(){
        $dados = Fornecedor::select('cpf_cnpj_idgener')
        // ->where('id', '>', 50000000)
        ->groupBy('cpf_cnpj_idgener')
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('cpf_cnpj_idgener')
        ->get();
        return $dados;
    }




    public function migrarFornecedores(){
        $this->info('Preparando para tratar fornecedores...');
        // vamos buscar os fornecedores com cpf duplicado
        $this->info('Quantidade aux: '.$this->quantidadeFornecedoresComCpfDuplicado);
        if($this->quantidadeFornecedoresComCpfDuplicado>0){$fornecedoresComCpfDuplicado = self::getCpfFornecedoresComCpfDuplicadoDesc();}
        else{$fornecedoresComCpfDuplicado = self::getCpfFornecedoresComCpfDuplicado();}

        $this->quantidadeFornecedoresComCpfDuplicado = count($fornecedoresComCpfDuplicado);
        $this->info('Qtd encontrada: '.$this->quantidadeFornecedoresComCpfDuplicado);
        $this->info('Atenção! Caso busque diretamente na base, lembrar do deleted at.');
        $cont = 0;
        foreach($fornecedoresComCpfDuplicado as $fornecedor){
            $cont++;
            $cpfDuplicado = $fornecedor->cpf_cnpj_idgener;
            $this->info($cont.' -> '.$cpfDuplicado);
            //aqui já temos os cpf duplicados
            // para cada cpf vamos buscar o id invalido e o id válido
            $arrayIdsFornecedorByCpf = self::getIdFornecedorByCpf($cpfDuplicado);
            $quantidadeIds = count($arrayIdsFornecedorByCpf);
            if($quantidadeIds > 1){
                $idFornecedorValido = $arrayIdsFornecedorByCpf[0]->id;
                $idFornecedorInvalido = $arrayIdsFornecedorByCpf[1]->id;
                $this->info( ' ==> '.$idFornecedorValido.' - '.$idFornecedorInvalido);
                if($idFornecedorInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm fornecedor_id
                    $arrayTabelasComFornecedorId = self::getNomesTabelasComByCampo('fornecedor_id');
                    $this->info( 'Vai atualizar as seguintes tabelas: ');
                    foreach($arrayTabelasComFornecedorId as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info($nomeTabela);
                    }
                    $contParar = 0;
                    foreach($arrayTabelasComFornecedorId as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info('Preparando para atualizar tabela : '.$nomeTabela);
                        // aqui já sabemos quais tabelas possuem o fornecedor_id
                        // vamos verificar se algum tem o fornecedor_id inválido
                        self::atualizarIdInvalidoParaIdValido('fornecedor_id', $nomeTabela, $idFornecedorInvalido, $idFornecedorValido);
                    }
                    // aqui já podemos excluir o fornecedor com id inválido
                    if(!self::excluirFornecedorComIdInvalido($idFornecedorInvalido)){$this->info( 'erro(1)'); exit;}
                } else {
                    $this->info( 'Não fez nada, pois o idInválido não era > 55000000.');
                }

            } else {
                $this->info( 'Só retornou um.');
            }
        }
    }
    public function migrarUsersEmail(){
        $this->info('Preparando para tratar users com emails duplicados...');
        // vamos buscar os users com email duplicado
        $this->info('Quantidade aux: '.$this->quantidadeUsersComEmailDuplicado);
        if($this->quantidadeUsersComEmailDuplicado>0){$usersComEmailDuplicado = self::getEmailUsersComEmailDuplicadoDesc();}
        else{$usersComEmailDuplicado = self::getEmailUsersComEmailDuplicado();}
        $this->quantidadeUsersComEmailDuplicado = count($usersComEmailDuplicado);
        $this->info('Qtd encontrada: '.$this->quantidadeUsersComEmailDuplicado);
        $this->info('Atenção! Caso busque diretamente na base, lembrar do deleted at.');
        $cont = 0;
        foreach($usersComEmailDuplicado as $user){
            $cont++;
            $emailDuplicado = $user->email;
            $this->info($cont.' -> '.$emailDuplicado);
            //aqui já temos os emails duplicados
            // para cada eamil vamos buscar o id invalido e o id válido
            $arrayIdsUserByEmail = self::getIdUserByEmail($emailDuplicado);
            $quantidadeIds = count($arrayIdsUserByEmail);
            if($quantidadeIds > 1){
                $idUserValido = $arrayIdsUserByEmail[0]->id;
                $idUserInvalido = $arrayIdsUserByEmail[1]->id;
                $this->info(' ==> '.$idUserValido.' - '.$idUserInvalido);
                if($idUserInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm user_id
                    $arrayTabelasComUserId = self::getNomesTabelasComByCampo('user_id');
                    $this->info('Vai atualizar as seguintes tabelas: ');
                    foreach($arrayTabelasComUserId as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info($nomeTabela);
                    }
                    $contParar = 0;
                    foreach($arrayTabelasComUserId as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info('Preparando para atualizar tabela : '.$nomeTabela);
                        // aqui já sabemos quais tabelas possuem o fornecedor_id
                        // vamos verificar se algum tem o fornecedor_id inválido
                        self::atualizarIdInvalidoParaIdValido('user_id', $nomeTabela, $idUserInvalido, $idUserValido);
                    }
                    // aqui já podemos excluir o fornecedor com id inválido
                    if(!self::excluirUserComIdInvalido($idUserInvalido)){$this->info('erro(1)'); exit;}
                } else {
                    $this->info('Não fez nada, pois o idInválido não era > 55000000.');
                }
            } else {
                $this->info('Só retornou um.');
            }
        }
    }
    public function migrarUsersCpf(){
        $this->info('Preparando para tratar users com cpfs duplicados...');
        // vamos buscar os users com cpf duplicado
        $this->info('Quantidade aux: '.$this->quantidadeUsersComCpfDuplicado);
        if($this->quantidadeUsersComCpfDuplicado>0){$usersComCpfDuplicado = self::getCpfUsersComCpfDuplicadoDesc();}
        else{$usersComCpfDuplicado = self::getCpfUsersComCpfDuplicado();}
        $this->quantidadeUsersComCpfDuplicado = count($usersComCpfDuplicado);
        $this->info('Qtd encontrada: '.$this->quantidadeUsersComCpfDuplicado);
        $this->info('Atenção! Caso busque diretamente na base, lembrar do deleted at.');
        $cont = 0;
        foreach($usersComCpfDuplicado as $user){
            $cont++;
            $cpfDuplicado = $user->cpf;
            $this->info($cont.' -> '.$cpfDuplicado);
            //aqui já temos os cpf duplicados
            // para cada cpf vamos buscar o id invalido e o id válido
            $arrayIdsUserByCpf = self::getIdUserByCpf($cpfDuplicado);
            $quantidadeIds = count($arrayIdsUserByCpf);
            if($quantidadeIds > 1){
                $idUserValido = $arrayIdsUserByCpf[0]->id;
                $idUserInvalido = $arrayIdsUserByCpf[1]->id;
                $this->info(' ==> '.$idUserValido.' - '.$idUserInvalido);
                if($idUserInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm user_id
                    $arrayTabelasComUserId = self::getNomesTabelasComByCampo('user_id');
                    $this->info('Vai atualizar as seguintes tabelas: ');
                    foreach($arrayTabelasComUserId as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info($nomeTabela);
                    }
                    $contParar = 0;
                    foreach($arrayTabelasComUserId as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info('Preparando para atualizar tabela : '.$nomeTabela);
                        // aqui já sabemos quais tabelas possuem o fornecedor_id
                        // vamos verificar se algum tem o fornecedor_id inválido
                        self::atualizarIdInvalidoParaIdValido('user_id', $nomeTabela, $idUserInvalido, $idUserValido);
                    }
                    // aqui já podemos excluir o fornecedor com id inválido
                    if(!self::excluirUserComIdInvalido($idUserInvalido)){$this->info('erro(1)'); exit;}
                } else {
                    $this->info('Não fez nada, pois o idInválido não era > 55000000.');
                }
            } else {
                $this->info('Só retornou um.');
            }
        }
    }
    // várias verificações - no getId, na verdade pega vários campos pra verificar
    public function migrarCodigoitem(){
        $this->info('Preparando para tratar codigoitem...');
        // vamos buscar os codigoitens com descricao duplicads
        $this->info('Quantidade aux: '.$this->quantidadeCodigoitemEncontrado);
        if($this->quantidadeCodigoitemEncontrado>0){$codigoItemComDescricaoDuplicada = self::getDescricaoCodigoitemComDescricaoDuplicadoDesc();}
        else{$codigoItemComDescricaoDuplicada = self::getDescricaoCodigoitemComDescricaoDuplicado();}
        $this->quantidadeCodigoitemEncontrado = count($codigoItemComDescricaoDuplicada);
        $this->info('Qtd encontrada: '.$this->quantidadeCodigoitemEncontrado);
        $this->info('Atenção! Caso busque diretamente na base, lembrar do deleted at.');
        $cont = 0;
        foreach($codigoItemComDescricaoDuplicada as $codigoitem){
            $cont++;
            $descricaoDuplicada = $codigoitem->descricao;
            $this->info($cont.' -> '.$descricaoDuplicada);
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIdsCodigoitemByDescricao = self::getIdCodigoitemByDescricao($descricaoDuplicada);
            $quantidadeIds = count($arrayIdsCodigoitemByDescricao);
            if($quantidadeIds > 1){
                $idValido = $arrayIdsCodigoitemByDescricao[0]->id;
                $codigoIdValido = $arrayIdsCodigoitemByDescricao[0]->codigo_id;

                $idInvalido = $arrayIdsCodigoitemByDescricao[1]->id;
                $codigoIdInvalido = $arrayIdsCodigoitemByDescricao[1]->codigo_id;

                $this->info('Dados válidos: id = '.$idValido.' codigoIdValido = '.$codigoIdValido);
                $this->info('Dados inválidos: id = '.$idInvalido.' codigoIdInvalido = '.$codigoIdInvalido);

                if($idInvalido > 55000000 && ($codigoIdInvalido == $codigoIdValido) ){
                    $this->info('Vai alterar...');
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm fornecedor_id
                    // $arrayTabelasComFornecedorId = self::getNomesTabelasComByCampo('fornecedor_id');

                    $arrayTabelas = array('orgaosubcategorias', 'contratohistorico', 'contratos');

                    $this->info('Vai atualizar as seguintes tabelas: ');
                    foreach($arrayTabelas as $nomeTabela){
                        // $nomeTabela = $objDadosTabela->nomeTabela;
                        $this->info($nomeTabela);
                    }
                    $contParar = 0;
                    $this->info('Atualizando tabela orgaosubcategorias...');
                    self::atualizarIdInvalidoParaIdValido('categoria_id', 'orgaosubcategorias', $idInvalido, $idValido);
                    $this->info('Atualizando tabela contratohistorico...');
                    self::atualizarIdInvalidoParaIdValido('tipo_id', 'contratohistorico', $idInvalido, $idValido);
                    self::atualizarIdInvalidoParaIdValido('categoria_id', 'contratohistorico', $idInvalido, $idValido);
                    self::atualizarIdInvalidoParaIdValido('modalidade_id', 'contratohistorico', $idInvalido, $idValido);
                    $this->info('Atualizando tabela contratos...');
                    self::atualizarIdInvalidoParaIdValido('categoria_id', 'contratos', $idInvalido, $idValido);
                    self::atualizarIdInvalidoParaIdValido('tipo_id', 'contratos', $idInvalido, $idValido);
                    self::atualizarIdInvalidoParaIdValido('modalidade_id', 'contratos', $idInvalido, $idValido);
                    // aqui já podemos excluir o registro com id inválido
                    if(!self::excluirCodigoitemComIdInvalido($idInvalido)){$this->info('erro(1)'); exit;}
                } else if($idInvalido > 55000000 && ($codigoIdInvalido != $codigoIdValido) ){
                    $this->info('Apesar do id ser > 55000000, o codigo_id é diferente - Não vai alterar.');
                } else{
                    $this->info('O código é < 55000000.');
                }


            } else {
                $this->info('Só retornou um.');
            }
        }
    }
    // app version não trata tabelas, só exclui
    public function migrarAppVersion(){
        $this->info('Preparando para tratar app version...');
        // vamos buscar os duplicados
        $this->info('Quantidade aux: '.$this->quantidadeAppversionDuplicados);
        if($this->quantidadeAppversionDuplicados>0){$arrayDuplicados = self::getPatchAppVersionComPatchDuplicadoDesc();}
        else{$arrayDuplicados = self::getPatchAppVersionComPatchDuplicado();}
        $this->quantidadeAppversionDuplicados = count($arrayDuplicados);
        $this->info('Qtd encontrada: '.$this->quantidadeAppversionDuplicados);
        $this->info('Atenção! Caso busque diretamente na base, lembrar do deleted at.');
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->patch;
            $this->info($cont.' -> '.$duplicado);
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdAppVersionByPatch($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                // início teste
                if($idValido < 55000000 && $idInvalido < 55000000 && $quantidadeIds > 2){
                    while($quantidadeIds > 0 && $idInvalido < 55000000){
                        $idInvalido = $arrayIds[$quantidadeIds - 1]->id;
                        $quantidadeIds --;
                        $this->info('id invalido = '.$idInvalido.' quantidade = '.$quantidadeIds);
                        // exit;
                    }
                }
                // fim teste
                $this->info(' ==> '.$idValido.' - '.$idInvalido);
                if($idInvalido > 55000000){
                    if(!self::excluirAppVersionComIdInvalido($idInvalido)){$this->info('erro(1)'); exit;}
                } else {
                    $this->info('Não fez nada, pois o idInválido não era > 55000000.');
                }
            } else {
                $this->info('Só retornou um.');
            }
        }
    }
    public function migrarCentrocusto(){
        $this->info('Preparando para tratar centrocusto...');
        // vamos buscar os duplicados
        $this->info('Quantidade aux: '.$this->quantidadeCentrocustoDuplicados);
        if($this->quantidadeCentrocustoDuplicados>0){$arrayDuplicados = self::getDescricaoCentrocustoComDescricaoDuplicadaDesc();}
        else{$arrayDuplicados = self::getDescricaoCentrocustoComDescricaoDuplicada();}
        $this->quantidadeCentrocustoDuplicados = count($arrayDuplicados);
        $this->info('Qtd encontrada: '.$this->quantidadeCentrocustoDuplicados);
        $this->info('Atenção! Caso busque diretamente na base, lembrar do deleted at.');
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->descricao;
            $this->info($cont.' -> '.$duplicado);
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdCentrocustoByDescricao($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                // início teste
                if($idValido < 55000000 && $idInvalido < 55000000 && $quantidadeIds > 2){
                    while($quantidadeIds > 0 && $idInvalido < 55000000){
                        $idInvalido = $arrayIds[$quantidadeIds - 1]->id;
                        $quantidadeIds --;
                        $this->info('id invalido = '.$idInvalido.' quantidade = '.$quantidadeIds);
                        // exit;
                    }
                }
                // fim teste
                $this->info(' ==> '.$idValido.' - '.$idInvalido);
                if($idInvalido > 55000000){
                    if(!self::excluirCentrocustoComIdInvalido($idInvalido)){$this->info('erro(1)'); exit;}
                } else {
                    $this->info('Não fez nada, pois o idInválido não era > 55000000.');
                }
            } else {
                $this->info('Só retornou um.');
            }
        }
    }
    public function migrarCodigo(){
        $this->info('Preparando para tratar codigo...');
        // vamos buscar os duplicados
        $this->info('Quantidade aux: '.$this->quantidadeCodigoDuplicados);
        if($this->quantidadeCodigoDuplicados>0){$arrayDuplicados = self::getDescricaoCodigoComDescricaoDuplicadaDesc();}
        else{$arrayDuplicados = self::getDescricaoCodigoComDescricaoDuplicada();}
        $this->quantidadeCodigoDuplicados = count($arrayDuplicados);
        $this->info('Qtd encontrada: '.$this->quantidadeCodigoDuplicados);
        $this->info('Atenção! Caso busque diretamente na base, lembrar do deleted at.');
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->descricao;
            $this->info($cont.' -> '.$duplicado);
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdCodigoByDescricao($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                // início teste
                if($idValido < 55000000 && $idInvalido < 55000000 && $quantidadeIds > 2){
                    while($quantidadeIds > 0 && $idInvalido < 55000000){
                        $idInvalido = $arrayIds[$quantidadeIds - 1]->id;
                        $quantidadeIds --;
                        $this->info('id invalido = '.$idInvalido.' quantidade = '.$quantidadeIds);
                        // exit;
                    }
                }
                // fim teste
                $this->info(' ==> '.$idValido.' - '.$idInvalido);
                if($idInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm codigo_id
                    $arrayTabelasComFornecedorId = self::getNomesTabelasComByCampo('codigo_id');
                    $this->info('Vai atualizar as seguintes tabelas: ');
                    foreach($arrayTabelasComFornecedorId as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info($nomeTabela);
                    }
                    $contParar = 0;
                    foreach($arrayTabelasComFornecedorId as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info('Preparando para atualizar tabela : '.$nomeTabela);
                        // aqui já sabemos quais tabelas possuem o codigo_id
                        // vamos verificar se algum tem o codigo_id inválido
                        self::atualizarIdInvalidoParaIdValido('codigo_id', $nomeTabela, $idInvalido, $idValido);
                    }
                    if(!self::excluirCodigoComIdInvalido($idInvalido)){$this->info('erro(1)'); exit;}
                } else {
                    $this->info('Não fez nada, pois o idInválido não era > 55000000.');
                }
            } else {
                $this->info('Só retornou um.');
            }
        }
    }
    public function migrarJustificativafatura(){
        $this->info('Preparando para tratar justificativa fatura...');
        // vamos buscar os duplicados
        $this->info('Quantidade aux: '.$this->quantidadeJustificativafaturaDuplicados);
        if($this->quantidadeJustificativafaturaDuplicados>0){$arrayDuplicados = self::getDescricaoJustificativafaturaComDescricaoDuplicadaDesc();}
        else{$arrayDuplicados = self::getDescricaoJustificativafaturaComDescricaoDuplicada();}
        $this->quantidadeJustificativafaturaDuplicados = count($arrayDuplicados);
        $this->info('Qtd encontrada: '.$this->quantidadeJustificativafaturaDuplicados);
        $this->info('Atenção! Caso busque diretamente na base, lembrar do deleted at.');
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->descricao;
            $this->info($cont.' -> '.$duplicado);
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdJustificativafaturaByDescricao($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                // início teste
                if($idValido < 55000000 && $idInvalido < 55000000 && $quantidadeIds > 2){
                    while($quantidadeIds > 0 && $idInvalido < 55000000){
                        $idInvalido = $arrayIds[$quantidadeIds - 1]->id;
                        $quantidadeIds --;
                        $this->info('id invalido = '.$idInvalido.' quantidade = '.$quantidadeIds);
                        // exit;
                    }
                }
                // fim teste
                $this->info(' ==> '.$idValido.' - '.$idInvalido);
                if($idInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm codigo_id
                    $arrayTabelas = self::getNomesTabelasComByCampo('justificativafatura_id');
                    $this->info('Vai atualizar as seguintes tabelas: ');
                    foreach($arrayTabelas as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info($nomeTabela);
                    }
                    $contParar = 0;
                    foreach($arrayTabelas as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info('Preparando para atualizar tabela : '.$nomeTabela);
                        // aqui já sabemos quais tabelas possuem o justificativafatura_id
                        // vamos verificar se algum tem o justificativafatura_id inválido
                        self::atualizarIdInvalidoParaIdValido('justificativafatura_id', $nomeTabela, $idInvalido, $idValido);
                    }
                    if(!self::excluirJustificativafaturaComIdInvalido($idInvalido)){$this->info('erro(1)'); exit;}
                } else {
                    $this->info('Não fez nada, pois o idInválido não era > 55000000.');
                }
            } else {
                $this->info('Só retornou um.');
            }
        }
    }
    public function migrarTipolistafatura(){
        $this->info('Preparando para tratar tipolistafatura...');
        // vamos buscar os duplicados
        $this->info('Quantidade aux: '.$this->quantidadeTipolistafaturaDuplicados);
        if($this->quantidadeTipolistafaturaDuplicados>0){$arrayDuplicados = self::getNomeTipolistafaturaComNomeDuplicadoDesc();}
        else{$arrayDuplicados = self::getNomeTipolistafaturaComNomeDuplicado();}
        $this->quantidadeTipolistafaturaDuplicados = count($arrayDuplicados);
        $this->info('Qtd encontrada: '.$this->quantidadeTipolistafaturaDuplicados);
        $this->info('Atenção! Caso busque diretamente na base, lembrar do deleted at.');
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->nome;
            $this->info($cont.' -> '.$duplicado);
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdTipolistafaturaByNome($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                // início teste
                if($idValido < 55000000 && $idInvalido < 55000000 && $quantidadeIds > 2){
                    while($quantidadeIds > 0 && $idInvalido < 55000000){
                        $idInvalido = $arrayIds[$quantidadeIds - 1]->id;
                        $quantidadeIds --;
                        $this->info('id invalido = '.$idInvalido.' quantidade = '.$quantidadeIds);
                        // exit;
                    }
                }
                // fim teste
                $this->info(' ==> '.$idValido.' - '.$idInvalido);
                if($idInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm codigo_id
                    $arrayTabelas = self::getNomesTabelasComByCampo('justificativafatura_id');
                    $this->info('Vai atualizar as seguintes tabelas: ');
                    foreach($arrayTabelas as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info($nomeTabela);
                    }
                    $contParar = 0;
                    foreach($arrayTabelas as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info('Preparando para atualizar tabela : '.$nomeTabela);
                        // aqui já sabemos quais tabelas possuem o justificativafatura_id
                        // vamos verificar se algum tem o justificativafatura_id inválido
                        self::atualizarIdInvalidoParaIdValido('tipolistafatura_id', $nomeTabela, $idInvalido, $idValido);
                    }
                    if(!self::excluirTipolistafaturaComIdInvalido($idInvalido)){$this->info('erro(1)'); exit;}
                } else {
                    $this->info('Não fez nada, pois o idInválido não era > 55000000.');
                }
            } else {
                $this->info('Só retornou um.');
            }
        }
    }
    public function migrarNaturezadespesa(){
        $this->info('Preparando para tratar naturezadespesa...');
        // vamos buscar os duplicados
        $this->info('Quantidade aux: '.$this->quantidadeNaturezadespesaDuplicados);
        if($this->quantidadeNaturezadespesaDuplicados>0){$arrayDuplicados = self::getDescricaoNaturezadespesaComDescricaoDuplicadaDesc();}
        else{$arrayDuplicados = self::getDescricaoNaturezadespesaComDescricaoDuplicada();}
        $this->quantidadeNaturezadespesaDuplicados = count($arrayDuplicados);
        $this->info('Qtd encontrada: '.$this->quantidadeNaturezadespesaDuplicados);
        $this->info('Atenção! Caso busque diretamente na base, lembrar do deleted at.');
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->descricao;
            $this->info($cont.' -> '.$duplicado);
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdNaturezadespesaByDescricao($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                // início teste
                if($idValido < 55000000 && $idInvalido < 55000000 && $quantidadeIds > 2){
                    while($quantidadeIds > 0 && $idInvalido < 55000000){
                        $idInvalido = $arrayIds[$quantidadeIds - 1]->id;
                        $quantidadeIds --;
                        $this->info('id invalido = '.$idInvalido.' quantidade = '.$quantidadeIds);
                        // exit;
                    }
                }
                // fim teste
                $this->info(' ==> '.$idValido.' - '.$idInvalido);
                if($idInvalido > 55000000){
                        // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm codigo_id
                    $arrayTabelas = self::getNomesTabelasComByCampo('naturezadespesa_id');
                    $this->info('Vai atualizar as seguintes tabelas: ');
                    foreach($arrayTabelas as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info($nomeTabela);
                    }
                    $contParar = 0;
                    foreach($arrayTabelas as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info('Preparando para atualizar tabela : '.$nomeTabela);
                        // aqui já sabemos quais tabelas possuem o justificativafatura_id
                        // vamos verificar se algum tem o justificativafatura_id inválido
                        self::atualizarIdInvalidoParaIdValido('naturezadespesa_id', $nomeTabela, $idInvalido, $idValido);
                    }

                    if(!self::excluirNaturezadespesaComIdInvalido($idInvalido)){$this->info('erro(1)'); exit;}
                } else {
                    $this->info('Não fez nada, pois o idInválido não era > 55000000.');
                }

            } else {
                $this->info('Só retornou um.');
            }
        }
    }
    public function migrarNaturezasubitem(){
        $this->info('Preparando para tratar naturezasubitem...');
        // vamos buscar os duplicados
        $this->info('Quantidade aux: '.$this->quantidadeNaturezasubitemDuplicados);
        if($this->quantidadeNaturezasubitemDuplicados>0){$arrayDuplicados = self::getDescricaoNaturezasubitemComDescricaoDuplicadaDesc();}
        else{$arrayDuplicados = self::getDescricaoNaturezasubitemComDescricaoDuplicada();}
        $this->quantidadeNaturezasubitemDuplicados = count($arrayDuplicados);
        $this->info('Qtd encontrada: '.$this->quantidadeNaturezasubitemDuplicados);
        $this->info('Atenção! Caso busque diretamente na base, lembrar do deleted at.');
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->descricao;
            $this->info($cont.' -> '.$duplicado);
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdNaturezasubitemByDescricao($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                // início teste
                if($idValido < 55000000 && $idInvalido < 55000000 && $quantidadeIds > 2){
                    while($quantidadeIds > 0 && $idInvalido < 55000000){
                        $idInvalido = $arrayIds[$quantidadeIds - 1]->id;
                        $quantidadeIds --;
                        $this->info('id invalido = '.$idInvalido.' quantidade = '.$quantidadeIds);
                        // exit;
                    }
                }
                // fim teste
                $this->info(' ==> '.$idValido.' - '.$idInvalido);
                if($idInvalido > 55000000){
                        // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm codigo_id
                    $arrayTabelas = self::getNomesTabelasComByCampo('naturezasubitem_id');
                    $this->info('Vai atualizar as seguintes tabelas: ');
                    foreach($arrayTabelas as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info($nomeTabela);
                    }
                    $contParar = 0;
                    foreach($arrayTabelas as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info('Preparando para atualizar tabela : '.$nomeTabela);
                        // aqui já sabemos quais tabelas possuem o id
                        // vamos verificar se algum tem o id inválido
                        self::atualizarIdInvalidoParaIdValido('naturezasubitem_id', $nomeTabela, $idInvalido, $idValido);
                    }

                    if(!self::excluirNaturezasubitemComIdInvalido($idInvalido)){$this->info('erro(1)'); exit;}
                } else {
                    $this->info('Não fez nada, pois o idInválido não era > 55000000.');
                }

            } else {
                $this->info('Só retornou um.');
            }
        }
    }
    public function migrarRhrubrica(){
        $this->info('Preparando para tratar rhrubrica...');
        $nomeChaveEstrangeira = 'rhrubrica_id';
        // vamos buscar os duplicados
        $this->info('Quantidade aux: '.$this->quantidadeRhrubricaDuplicados);
        if($this->quantidadeRhrubricaDuplicados>0){$arrayDuplicados = self::getDescricaoRhrubricaComDescricaoDuplicadaDesc();}
        else{$arrayDuplicados = self::getDescricaoRhrubricaComDescricaoDuplicada();}
        $this->quantidadeRhrubricaDuplicados = count($arrayDuplicados);
        $this->info('Qtd encontrada: '.$this->quantidadeRhrubricaDuplicados);
        $this->info('Atenção! Caso busque diretamente na base, lembrar do deleted at.');
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->descricao;
            $this->info($cont.' -> '.$duplicado);
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdRhrubricaByDescricao($duplicado);
            $quantidadeIds = count($arrayIds);
            $this->info('Quantidade ids: '.$quantidadeIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                if($idValido < 55000000 && $idInvalido < 55000000 && $quantidadeIds > 2){
                    while($quantidadeIds > 0 && $idInvalido < 55000000){
                        $idInvalido = $arrayIds[$quantidadeIds - 1]->id;
                        $quantidadeIds --;
                        $this->info('id invalido = '.$idInvalido.' quantidade = '.$quantidadeIds);
                        // exit;
                    }
                }
                $this->info(' ==> '.$idValido.' - '.$idInvalido);
                if($idInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm codigo_id
                    $arrayTabelas = self::getNomesTabelasComByCampo($nomeChaveEstrangeira);
                    $this->info('Vai atualizar as seguintes tabelas: ');
                    foreach($arrayTabelas as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info($nomeTabela);
                    }
                    // exit;
                    $contParar = 0;
                    foreach($arrayTabelas as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info('Preparando para atualizar tabela : '.$nomeTabela);
                        // aqui já sabemos quais tabelas possuem o id
                        // vamos verificar se algum tem o id inválido
                        self::atualizarIdInvalidoParaIdValido($nomeChaveEstrangeira, $nomeTabela, $idInvalido, $idValido);
                    }

                    if(!self::excluirRhrubricaComIdInvalido($idInvalido)){$this->info('erro(1)'); exit;}
                } else {
                    $this->info('Não fez nada, pois o idInválido não era > 55000000.');
                }

            } else {
                $this->info('Só retornou um.');
            }
        }
    }
    public function migrarRoles(){
        $this->info('Preparando para tratar roles...');
        // vamos buscar os duplicados
        $this->info('Quantidade aux: '.$this->quantidadeRolesDuplicados);
        if($this->quantidadeRolesDuplicados>0){$arrayDuplicados = self::getNomeRolesComNomeDuplicadoDesc();}
        else{$arrayDuplicados = self::getNomeRolesComNomeDuplicado();}
        $this->quantidadeRolesDuplicados = count($arrayDuplicados);
        $this->info('Qtd encontrada: '.$this->quantidadeRolesDuplicados);
        $this->info('Atenção! Caso busque diretamente na base, lembrar do deleted at.');
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->name;
            $this->info($cont.' -> '.$duplicado);
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdRolesByName($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                // início teste
                if($idValido < 55000000 && $idInvalido < 55000000 && $quantidadeIds > 2){
                    while($quantidadeIds > 0 && $idInvalido < 55000000){
                        $idInvalido = $arrayIds[$quantidadeIds - 1]->id;
                        $quantidadeIds --;
                        $this->info('id invalido = '.$idInvalido.' quantidade = '.$quantidadeIds);
                        // exit;
                    }
                }
                // fim teste
                $this->info(' ==> '.$idValido.' - '.$idInvalido);
                if($idInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm codigo_id
                    $arrayTabelas = self::getNomesTabelasComByCampo('role_id');
                    $this->info('Vai atualizar as seguintes tabelas: ');
                    foreach($arrayTabelas as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info($nomeTabela);
                    }
                    $contParar = 0;
                    foreach($arrayTabelas as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info('Preparando para atualizar tabela : '.$nomeTabela);
                        // aqui já sabemos quais tabelas possuem o justificativafatura_id
                        // vamos verificar se algum tem o justificativafatura_id inválido
                        self::atualizarIdInvalidoParaIdValido('role_id', $nomeTabela, $idInvalido, $idValido);
                    }
                    if(!self::excluirRolesComIdInvalido($idInvalido)){$this->info('erro(1)'); exit;}
                } else {
                    $this->info('Não fez nada, pois o idInválido não era > 55000000.');
                }
            } else {
                $this->info('Só retornou um.');
            }
        }
    }
    public function migrarOrgaosuperior(){
        $this->info('Preparando para tratar orgaosuperior...');
        // vamos buscar os duplicados
        $this->info('Quantidade aux: '.$this->quantidadeRolesDuplicados);
        if($this->quantidadeOrgaosuperiorDuplicados>0){$arrayDuplicados = self::getNomeOrgaosuperiorComNomeDuplicadoDesc();}
        else{$arrayDuplicados = self::getNomeOrgaosuperiorComNomeDuplicado();}
        $this->quantidadeOrgaosuperiorDuplicados = count($arrayDuplicados);
        $this->info('Qtd encontrada: '.$this->quantidadeOrgaosuperiorDuplicados);
        $this->info('Atenção! Caso busque diretamente na base, lembrar do deleted at.');
        $cont = 0;
        foreach($arrayDuplicados as $itemDuplicado){
            $cont++;
            $duplicado = $itemDuplicado->nome;
            $this->info($cont.' -> '.$duplicado);
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdOrgaosuperiorByNome($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $idInvalido = $arrayIds[1]->id;
                // início teste
                if($idValido < 55000000 && $idInvalido < 55000000 && $quantidadeIds > 2){
                    while($quantidadeIds > 0 && $idInvalido < 55000000){
                        $idInvalido = $arrayIds[$quantidadeIds - 1]->id;
                        $quantidadeIds --;
                        $this->info('id invalido = '.$idInvalido.' quantidade = '.$quantidadeIds);
                        // exit;
                    }
                }
                // fim teste
                $this->info(' ==> '.$idValido.' - '.$idInvalido);
                if($idInvalido > 55000000){
                    // aqui já temos os ids válidos e inválidos
                    // vamos buscar as tabelas que têm codigo_id
                    $arrayTabelas = self::getNomesTabelasComByCampo('orgaosuperior_id');
                    $this->info('Vai atualizar as seguintes tabelas: ');
                    foreach($arrayTabelas as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info($nomeTabela);
                    }
                    $contParar = 0;
                    foreach($arrayTabelas as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info('Preparando para atualizar tabela : '.$nomeTabela);
                        // aqui já sabemos quais tabelas possuem o id
                        // vamos verificar se algum tem o id inválido
                        self::atualizarIdInvalidoParaIdValido('orgaosuperior_id', $nomeTabela, $idInvalido, $idValido);
                    }

                    if(!self::excluirOrgaosuperiorComIdInvalido($idInvalido)){$this->info('erro(1)'); exit;}
                } else {
                    $this->info('Não fez nada, pois o idInválido não era > 55000000.');
                }

            } else {
                $this->info('Só retornou um.');
            }
        }
    }
    // várias verificações - no getProcesso..., na verdade pega vários campos pra verificar
    public function migrarContrato(){
        $this->info('Preparando para tratar contrato...');
        // vamos buscar os duplicados
        $this->info('Quantidade aux: '.$this->quantidadeContratoDuplicados);
        if($this->quantidadeContratoDuplicados>0){$duplicados = self::getProcessoContratoComProcessoDuplicadoDesc();}
        else{$duplicados = self::getProcessoContratoComProcessoDuplicado();}
        $this->quantidadeContratoDuplicados = count($duplicados);
        $this->info('Qtd encontrada: '.$this->quantidadeContratoDuplicados);
        $this->info('Atenção! Caso busque diretamente na base, lembrar do deleted at.');
        $cont = 0;
        foreach($duplicados as $item){
            $cont++;
            $duplicado = $item->processo;
            $this->info($cont.' -> '.$duplicado);
            //aqui já temos os duplicados
            // para cada um vamos buscar o id invalido e o id válido
            $arrayIds = self::getIdContratoByProcesso($duplicado);
            $quantidadeIds = count($arrayIds);
            if($quantidadeIds > 1){
                $idValido = $arrayIds[0]->id;
                $numeroValido = $arrayIds[0]->numero;
                $fornecedor_idValido = $arrayIds[0]->fornecedor_id;
                $unidade_idValido = $arrayIds[0]->unidade_id;
                $tipo_idValido = $arrayIds[0]->tipo_id;
                $categoria_idValido = $arrayIds[0]->categoria_id;
                $processoValido = $arrayIds[0]->processo;
                $objetoValido = $arrayIds[0]->objeto;
                $licitacao_numeroValido = $arrayIds[0]->licitacao_numero;
                $valor_globalValido = $arrayIds[0]->valor_global;
                $data_assinaturaValido = $arrayIds[0]->data_assinatura;
                //
                $idInvalido = $arrayIds[1]->id;
                $numeroInvalido = $arrayIds[1]->numero;
                $fornecedor_idInvalido = $arrayIds[1]->fornecedor_id;
                $unidade_idInvalido = $arrayIds[1]->unidade_id;
                $tipo_idInvalido = $arrayIds[1]->tipo_id;
                $categoria_idInvalido = $arrayIds[1]->categoria_id;
                $processoInvalido = $arrayIds[1]->processo;
                $objetoInvalido = $arrayIds[1]->objeto;
                $licitacao_numeroInvalido = $arrayIds[1]->licitacao_numero;
                $valor_globalInvalido = $arrayIds[1]->valor_global;
                $data_assinaturaInvalido = $arrayIds[1]->data_assinatura;
                // início teste
                if($idValido < 55000000 && $idInvalido < 55000000 && $quantidadeIds > 2){
                    while($quantidadeIds > 0 && $idInvalido < 55000000){
                        $idInvalido = $arrayIds[$quantidadeIds - 1]->id;
                        $quantidadeIds --;
                        $this->info('id invalido = '.$idInvalido.' quantidade = '.$quantidadeIds);
                        // exit;
                    }
                }
                // fim teste

                $this->info('Dados válidos: id = '.$idValido.' valor global = '.$valor_globalValido);
                $this->info('Dados inválidos: id = '.$idInvalido.' valor global = '.$valor_globalInvalido);

                if($idInvalido > 55000000 && (
                    $numeroInvalido == $numeroValido &&
                    $fornecedor_idInvalido == $fornecedor_idValido &&
                    $unidade_idInvalido == $unidade_idValido &&
                    $tipo_idInvalido == $tipo_idValido &&
                    $categoria_idInvalido == $categoria_idValido &&
                    $processoInvalido == $processoValido &&
                    $objetoInvalido == $objetoValido &&
                    $licitacao_numeroInvalido == $licitacao_numeroValido &&
                    $valor_globalInvalido == $valor_globalValido &&
                    $data_assinaturaInvalido == $data_assinaturaValido
                    ) ){
                    $this->info('Vai alterar...');
                    // aqui já temos os ids válidos e inválidos
                    $arrayTabelas = self::getNomesTabelasComByCampo('contrato_id');
                    $this->info('Vai atualizar as seguintes tabelas: ');
                    foreach($arrayTabelas as $objDadosTabela){
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info($nomeTabela);
                    }
                    $contParar = 0;
                    foreach($arrayTabelas as $objDadosTabela){
                        $contParar++;
                        $nomeTabela = $objDadosTabela->table_name;
                        $this->info('Preparando para atualizar tabela : '.$nomeTabela);
                        // aqui já sabemos quais tabelas possuem o id
                        // vamos verificar se algum tem o id inválido
                        self::atualizarIdInvalidoParaIdValido('contrato_id', $nomeTabela, $idInvalido, $idValido);
                    }
                    // aqui já podemos excluir o registro com id inválido
                    if(!self::excluirContratoComIdInvalido($idInvalido)){$this->error('erro(1)'); exit;}
                } else if($idInvalido > 55000000 ){
                    $this->info('Apesar do id ser > 55000000, outros dados não bateram - Não vai alterar.');
                } else{
                    $this->info('O código é < 55000000.');
                }
            } else {
                $this->info('Só retornou um.');
            }
        }
    }
}
