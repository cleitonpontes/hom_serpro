# COMPRASNET CONTRATOS

![Logo Comprasnet Contratos](https://gitlab.com/sistema-conta/sc/-/raw/master/public/img/logo_mail.png)


**Sistema de Gestão Administrativa para Órgãos Públicos**

## Apresentação

Com foco na busca pela eficiência da gestão e na melhoria do desempenho das instituições públicas, o Ministério da Economia, em parceria com a Advocacia-Geral da União, oferta aos órgãos e entidades da administração pública direta, autárquica e fundacional, incluindo as empresas estatais, o [Comprasnet Contratos](https://contratos.comprasnet.gov.br/login). A ferramenta faz parte das medidas de eficiência organizacional para o aprimoramento da administração pública federal direta, autárquica e fundacional estabelecidas pelo Decreto nº 9.739, de 28 de março de 2019 (Art. 6º, IX).
O [Comprasnet Contratos](https://contratos.comprasnet.gov.br/login) é uma ferramenta do governo federal que automatiza os processos de gestão contratual e conecta servidores públicos responsáveis pela execução e fiscalização de contratos, tornando informações disponíveis a qualquer momento e melhorando as condições de gestão e relacionamento com fornecedores.

**Quem pode utilizar:** 
- Órgãos e entidades da administração pública federal direta, autárquica e fundacional, bem como as empresas estatais; e 
- Demais órgãos e entidades de outros poderes ou das esferas estadual e municipal.

**Quanto custa:** 
- O sistema é ofertado gratuitamente aos órgãos e entidades integrantes do Sistema Integrado de Serviços Gerais ([SISG](https://www.gov.br/compras/pt-br/acesso-a-informacao/institucional/sisg)), custeado pelo Ministério da Economia.

**Modelo de oferta do sistema:**
- Disponibilizado de forma centralizada, evitando custos com hospedagem e manutenção de sistemas de TIC hospedagem e manutenção de sistemas de TIC.

**Vantagens da plataforma:**
- Reduz os problemas relacionados às rotinas de trabalho;
- Pleno controle das informações do que acontece no âmbito dos contratos de um órgão ou entidade;
- Promove a eficiência na gestão contratual;
- Proporciona informações para apoiar as decisões governamentais de alocação mais eficiente de recursos;
- Infraestrutura centralizada, sem custos para órgãos e entidades do Poder Executivo federal;
- Maior transparência das informações dos contratos celebrados por toda a administração pública, permitindo a padronização de rotinas e procedimentos.

**A ferramenta viabiliza:**
- Controle de documentos diversos;
- Controle sobre os prazos de vigência dos contratos;
- Gestão sobre as informações financeiras do contrato;
- Visão global das penalidades aplicadas aos contratados;
- Controle sobre o valor desembolsado em cada contrato e sobre todos os contratos do órgão ou entidade;
- Gerenciamento dos diversos contratos sob a responsabilidade do gestor;
- Facilidade e praticidade nas sub-rogações;
- Padronização das ações de fiscalização por parte dos fiscais;
•	Controle dos atos administrativos praticados;
•	Controle sobre a fiscalização realizada;
•	Contato fácil com os fornecedores e solução rápida de impasses;
•	Controle sobre a realização de aditivos contratuais.


## Como acessar o sistema

**Ambiente:** <http://contratos.comprasnet.gov.br/>  
**Usuário:** Previamente cadastrado por um usuário administrador do Sistema Comprasnet Contrato ( não é login de rede ).  
**Senha:** Previamente cadastrado por um usuário administrador do Sistema Comprasnet Contrato (não é senha de rede).


## Manuais do sistema

Manuais\
Lista de Manuais

[Página de manuais](manuais)

(verificar)  
Apropriação Folha    
2.1. Preparação do arquivo DDP\
2.2. Listar Apropriações\
2.3. Importar DDP\
2.4. Identificação das Situações\
2.5. Identificação dos Empenhos e Valores\
2.6. Consulta saldo dos Empenhos no SIAFI\
2.7. Dados Básicos do Documento Folha\
2.8. Persistir Dados no Banco de Dados\
2.9. Apropriar no SIAFI


## Documento de Arquitetura

Documento de Arquitetura


## Licença

A tipo de licença dessa ferramenta é “Licença Pública Geral GNU ([GPLv3](https://www.gnu.org/licenses/quick-guide-gplv3.html))”. Pedimos que toda implementação seja disponibilizada para a comunidade.\
Caso o órgão queira implementar nova funcionalidade, pedimos que disponibilize para que outras instituições possam utilizar.


## Como instalar

**Requisitos**
1.	Sistema operacional Linux (Ubuntu, CentOs, Debian, RedHat,...);
2.	Servidor Web (Apache2 ou Nginx);
3.	PHP 7.1.31+ (recomendado 7.3+);
4.	Banco de Dados PostgreSQL 9.4+.

**Ferramentas necessárias do Sistema Operacional 
Linux**
- OpenSSL;
- Unzip;
- Lynx;
- Supervisor;
- Git;
- Composer;
- mod_rewrite do Apache ou Nginx.

**Extensões PHP necessárias**
- php7.3-pgsql
- php7.3-pdo
- php7.3-xml
- php7.3-xmlrpc
- php7.3-curl
- php7.3-gd
- php7.3-imagick
- php7.3-cli
- php7.3-dev
- php7.3-imap
- php7.3-mbstring
- php7.3-opcache
- php7.3-soap
- php7.3-zip
- php7.3-intl
- php7.3-json
- php7.3-bcmath
- php7.3-ctype
- php7.3-tokenizer

**Alteração dos parâmetros do Arquivo php.ini**
- max_execution_time = 1200
- max_input_time = -1
- memory_limit = 512M
- upload_max_filesize = 40M
- date.timezone = "America/Sao_Paulo"

**Instalando as Aplicações**

---

Baixando as aplicações do Gitlab.com.

Entre na pasta root do servidor Web (www) e execute os seguintes comandos:
1.	git clone https://gitlab.com/sistema-conta/sta.git ;
2.	git clone https://gitlab.com/sistema-conta/sc.git .

Após o termino da execução, terá duas pastas:
- Pasta “sc” (Sistema Comprasnet Contrato);
- Pasta “sta” (API do STA – Sistema de Transferência de Arquivos / STN).

Instalação do Sistema Comprasnet Contrato.

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

Instalação do API STA,

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

Configurando as Aplicações.\
Configuração Sistema Comprasnet Contrato.\
E-mail do Sistema.\

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

Link da API STA

Para que o Sistema Comprasnet Contrato tenha informações da Execução Financeira e Orçamentária do SIAFI, é necessário informar no arquivo .env o link dessa aplicação:

```
API_STA_HOST=http://sta.instituicao.gov.br`
```

Configuração do GuardianKey (opcional).

O Sistema Comprasnet Contrato tem suporte para a ferramenta [GuardianKey](https://guardiankey.io). Para habilitar essa funcionalidade e configurar, basta que informe "true" no parâmetro "GUARDIANKEY" e informar os demais parâmetros do arquivo .env:

```
GUARDIANKEY=true
GUARDIANKEY_ORGID=
GUARDIANKEY_AUTHGROUPID=
GUARDIANKEY_KEY=
GUARDIANKEY_IV=
```

Acessando o Sistema Comprasnet Contrato.

O usuário padrão da Aplicação é:

CPF: 111.111.111-11 \
Senha: 123456

Demais configurações.\
Permissão de pastas

Entre na pasta "www" e execute os seguintes comandos:

```
chmod -R 755
chowm www-data:www-data .
```

O comando "chmod" dará permissão necessárias, e o "chown" alterará o proprietário das pastas e arquivos para o usuário do servidor Web.

Agendamentos Sistema Operacional.

Para que as aplicações executem suas rotinas diárias, é necessário o agendamento de algumas tarefas - do sistema operacional (crontab).

Para efetuar esse agendamento execute o comando:

```
crontab -e
```

Copie e cole no final as linhas abaixo:

```
* * * * * cd /caminho_completo_do_sistema_conta && php artisan schedule:run >> /dev/null 2>&1
```

Configuração do Supervisor.

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

## Contatos
