# backend

## Instruções

Para este projeto não é necessário um arquivo __.env__

Usei o SQLite como banco de dados, logo será necessário instalar a extensão php para o SQLite.

Para executar esse projeto siga os seguintes passos

1. Instale a extensão php-sqlite com o comando `sudo apt install php-sqlite`
2. Execute na pasta do projeto o comando `composer install`
3. Faça as migrações com o comando `php artisan migrate`
4. Crie o link simbólico da pasta de storage para a pasta pública com o comando:  `php artisan storage:link`

Com isso o projeto está pronto para ser executado
