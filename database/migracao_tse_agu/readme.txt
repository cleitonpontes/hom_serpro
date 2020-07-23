Com o sistema comprasnet rodando:

1. copiar os arquivos da pasta seeders empacotados para a pasta database/seeds
2. executar script1_producao.sql
3. php composer.phar dump-autoload
4. php artisan db:seed
5. executar script2_producao.sql
6. no browser: http://localhost:8000/tratardadosmigracaotseagu
7. executar script3_producao.sql