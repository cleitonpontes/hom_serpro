# Instalando as Aplicações

Antes de instalar verifique se possui os [requisitos](requisitos.md) necessários.

## Baixando as aplicações do Gitlab.com.

Entre na pasta root do servidor Web (www) e execute os seguintes comandos:

1.	git clone https://gitlab.com/sistema-conta/sta.git ;
2.	git clone https://gitlab.com/sistema-conta/sc.git .

Após o termino da execução, terá duas pastas:

- Pasta “sc” (Sistema Comprasnet Contrato);
- Pasta “sta” (API do STA – Sistema de Transferência de Arquivos / STN).

## Instalação do Sistema Comprasnet Contrato.

Entre no caminho da pasta “sc” (exemplo de comando: cd /var/www/sc), e execute o comando:

```
composer install
```

Este comando do “composer” fará a instalação das bibliotecas que o sistema Comprasnet Contrato necessita.

**Observação:** O servidor ou Máquina Virtual deverá ter acesso a internet para downloads necessários à instalação.

Execute os comandos para configuração do Laravel 5.7:

``` 
cp .env.example .env
php artisan key:generate
```

Edite as seguintes variáveis do arquivo .env para configurar a conexão com o Banco de Dados PostgreSQL.

```
DB_CONNECTION=pgsql
DB_HOST=host_ou_ip
DB_PORT=5432
DB_DATABASE=nome_banco
DB_USERNAME=nome_usuario
DB_PASSWORD=senha_usuario
```

Execute os comandos para levantar as tabelas do Banco de Dados:

```
php artisan migrate
```

Para popularização do Banco de Dados execute o comando:

```
php artisan db:seed
```

## Instalação do API STA

Entre no caminho da pasta “sta” (exemplo de comando: cd /var/www/sta), e execute o comando:

```
composer install
```

Este comando do “composer” fará a instalação das bibliotecas que o sistema Comprasnet Contrato necessita.

**Observação**: O servidor ou Máquina Virtual deverá ter acesso a internet para download necessários a instalação.

Execute os comando para configuração do Laravel 5.8:

```
cp .env.example .env
php artisan key:generate
```

Edite as seguintes variáveis do arquivo .env para configurar a conexão com o Banco de Dados PostgreSQL.

```
DB_CONNECTION=pgsql
DB_HOST=host_ou_ip
DB_PORT=5432
DB_DATABASE=nome_banco
DB_USERNAME=nome_usuario
DB_PASSWORD=senha_usuario
```

Execute o comando para levantar as tabelas do Banco de Dados:

```
php artisan migrate
```

## Configurando as Aplicações.
### Configuração Sistema Comprasnet Contrato.
#### E-mail do Sistema.

O Sistema Comprasnet Contrato necessita de um e-mail para se comunicar com os usuários. A configuração deste é feita no arquivo .env e nos parâmetros abaixo:

```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.instituicao.gov.br
MAIL_PORT=587
MAIL_FROM_ADDRESS=comprasnetcontrato@instituicao.gov.br
MAIL_FROM_NAME="Sistema Comprasnet Contrato"
MAIL_USERNAME=comprasnetcontrato@instituicao.gov.br
MAIL_PASSWORD=senha_email
MAIL_ENCRYPTION=tls
```

#### Link da API STA

Para que o Sistema Comprasnet Contrato tenha informações da Execução Financeira e Orçamentária do SIAFI, é necessário informar no arquivo .env o link dessa aplicação:

```
API_STA_HOST=http://sta.instituicao.gov.br`
```

#### Configuração do GuardianKey (opcional).

O Sistema Comprasnet Contrato tem suporte para a ferramenta [GuardianKey](https://guardiankey.io). Para habilitar essa funcionalidade e configurar, basta que informe "true" no parâmetro "GUARDIANKEY" e informar os demais parâmetros do arquivo .env:

```
GUARDIANKEY=true
GUARDIANKEY_ORGID=
GUARDIANKEY_AUTHGROUPID=
GUARDIANKEY_KEY=
GUARDIANKEY_IV=
```

## Acessando o Sistema Comprasnet Contrato.

O usuário padrão da Aplicação é:

- CPF: 111.111.111-11
- Senha: 123456

## Demais configurações
### Permissão de pastas

Entre na pasta "www" e execute os seguintes comandos:

```
chmod -R 755
chowm www-data:www-data .
```

O comando "chmod" dará permissão necessárias, e o "chown" alterará o proprietário das pastas e arquivos para o usuário do servidor Web.

### Agendamentos Sistema Operacional.

Para que as aplicações executem suas rotinas diárias, é necessário o agendamento de algumas tarefas - do sistema operacional (crontab).

Para efetuar esse agendamento execute o comando:

```
crontab -e
```

Copie e cole no final as linhas abaixo:

```
* * * * * cd /caminho_completo_do_sistema_conta && php artisan schedule:run >> /dev/null 2>&1
```

### Configuração do Supervisor.

Acesse a pasta /etc/supervisor/conf.d e crie um arquivo sc.conf com o conteúdo abaixo, atualizando a informação do "caminho_completo_do_sistema_conta":

```
[program:sc]
process_name=%(program_name)s_%(process_num)02d
command=php /caminho_completo_do_sistema_conta/artisan queue:work --queue=siafialteradh --timeout=7200 --tries=3
autostart=true
autorestart=true
user=root
numprocs=1
redirect_stderr=true
stdout_logfile=/caminho_completo_do_sistema_conta/storage/logs/siafialteradh.worker.log

[program:sc2]
process_name=%(program_name)s_%(process_num)02d
command=php /caminho_completo_do_sistema_conta/artisan queue:work --queue=atualizasaldone --timeout=7200 --tries=3
autostart=true
autorestart=true
user=root
numprocs=5
redirect_stderr=true
stdout_logfile=/caminho_completo_do_sistema_conta/storage/logs/atualizasaldone.worker.log

[program:sc3]
process_name=%(program_name)s_%(process_num)02d
command=php /caminho_completo_do_sistema_conta/artisan queue:work --queue=default --timeout=7200 --tries=3
autostart=true
autorestart=true
user=root
numprocs=3
redirect_stderr=true
stdout_logfile=/caminho_completo_do_sistema_conta/storage/logs/default.worker.log
```

Habilite o Supervisor para ser iniciado o seu serviço automaticamente e início o serviço dele:

```
/etc/init.d/supervisor start
```

Para verificar se funcionou a configuração do supervisor, execute comando "ps aux" e verifique se constam os seguintes serviços:

```
/usr/bin/python /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
php /var/www/sc/artisan queue:work --queue=siafialteradh --timeout=7200 --tries=3
php /var/www/sc/artisan queue:work --queue=atualizasaldone --timeout=7200 --tries=3
php /var/www/sc/artisan queue:work --queue=atualizasaldone --timeout=7200 --tries=3
php /var/www/sc/artisan queue:work --queue=atualizasaldone --timeout=7200 --tries=3
php /var/www/sc/artisan queue:work --queue=atualizasaldone --timeout=7200 --tries=3
php /var/www/sc/artisan queue:work --queue=atualizasaldone --timeout=7200 --tries=3
php /var/www/sc/artisan queue:work --queue=default --timeout=7200 --tries=3
php /var/www/sc/artisan queue:work --queue=default --timeout=7200 --tries=3
php /var/www/sc/artisan queue:work --queue=default --timeout=7200 --tries=3
```