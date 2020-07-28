Testar via command:
Com o sistema comprasnet rodando e com o composer instalado:
php artisan make:tratarDadosMigracaoTseAgu





NÃO RODAR CONFORME ABAIXO.
1. copiar os arquivos da pasta /database/migracao_tse_agu/seeders empacotados para a pasta database/seeds
2. executar no banco de dados: /database/migracao_tse_agu/script1_producao.sql
3. php composer.phar dump-autoload
4. php artisan db:seed
5. executar no banco de dados: /database/migracao_tse_agu/script2_producao.sql
6. php artisan make:tratarDadosMigracaoTseAgu
7. executar no banco de dados: /database/migracao_tse_agu/script3_producao.sql

