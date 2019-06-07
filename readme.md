![Logo](public/img/logo_mail.png)
## SISTEMA CONTA - Gestão Administrativa para Órgãos Públicos
Sistema de Gestão Administrativa e de Contratos para Órgãos Públicos.

## Tecnologia Utilizada

A ferramenta é desenvolvida em PHP, utilizando  Framework Laravel versão 5.7.*

Essa ferramenta é Gratuita, e cada Instituição Pública poderá utilizá-la sem limites.
 
Caso o órgão queira implementar nova funcionalidade, pedimos que disponibilize esta para que outras instituições possa utilizar.

## Licença

A licença dessa ferramenta é GPLv2. Pedimos que toda implementação seja disponibilizada para a comunidade.

## Versões, Requisitos, Instalação e Configuração

### Versões

- Laravel 5.7;
- PHP 7.1+;
- PostgreSQL 9.6+

### Requisitos para instalação

- Git;
- Composer.

### Como Instalar
Para baixar o conteúdo via Git utilize o seguinte comando:
```
git clone https://gitlab.com/sistema-conta/sc
```

Para instalação entre no diretorio "sta" e execute o comando composer:
```
cd sc
composer install
```

### Configuração

Execute os comando para configuração do Laravel 5.7:
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
Execute os comando para levantar as tabelas do Banco de Dados:
```
php artisan migrate
```
Para popularização do Banco de Dados execute o comando:
```
php artisan db:seed
```

## Instalação e Configuração no Docker

Basta executar o comando:

```
chmod +x up.sh
./up.sh
```
__*Observação: O usuário que executar esses comandos deverá ter as permissões necessárias.*__

Conheça nossa [Wiki](https://gitlab.com/sistema-conta/sc/wikis/home)!
