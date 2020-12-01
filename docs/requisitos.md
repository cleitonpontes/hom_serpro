## Como instalar

### Requisitos
1.	Sistema operacional Linux (Ubuntu, CentOs, Debian, RedHat,...);
2.	Servidor Web (Apache2 ou Nginx);
3.	PHP 7.1.31+ (recomendado 7.3+);
4.	Banco de Dados PostgreSQL 9.4+.

### Ferramentas necessárias do Sistema Operacional Linux
- OpenSSL;
- Unzip;
- Lynx;
- Supervisor;
- Git;
- Composer;
- mod_rewrite do Apache ou Nginx.

### Extensões PHP necessárias
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

### Alteração dos parâmetros do Arquivo php.ini
- max_execution_time = 1200
- max_input_time = -1
- memory_limit = 512M
- upload_max_filesize = 40M
- date.timezone = "America/Sao_Paulo"
