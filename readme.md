![Logo](public/img/logo_mail.png)
## SISTEMA CONTA - Gestão Administrativa para Órgãos Públicos

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
