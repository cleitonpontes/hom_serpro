Com o sistema comprasnet rodando:

1. arquivar database/seeds/*.* (alguns arquivos poderão ser sobrepostos)
2. copiar os arquivos da pasta seeders empacotados para a pasta database/seeds
3. executar script1_producao.sql
4. php composer.phar dump-autoload
5. php artisan db:seed
6. executar script2_producao.sql