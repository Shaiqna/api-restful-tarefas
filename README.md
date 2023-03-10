# Começando

## Instalação

Verifique o guia de instalação oficial do laravel para obter os requisitos do servidor antes de começar. [Documentação oficial](https://laravel.com/docs/5.4/installation#installation)

Clone o repositório

    git clone git@github.com:Shaiqna/api-restful-tarefas.git

Entre na pasta do repositório

    cd api-restful-tarefas

Instale todas as dependências usando o composer

    composer install

Copie o arquivo env de exemplo e faça as alterações de configuração necessárias no arquivo .env

    cp .env.example .env

Gerar uma nova chave de aplicativo

    php artisan key:generate

Gere uma nova chave de autenticação JWT

    php artisan jwt:generate

Execute as migrações do banco de dados (**Defina a conexão do banco de dados em .env antes de migrar**)

    php artisan migrate

Inicie o servidor de desenvolvimento local

    php artisan serve

Agora você pode acessar o servidor em http://127.0.0.1:8000

**Lista de comandos**

    git clone git@github.com:Shaiqna/api-restful-tarefas.git
    cd api-restful-tarefas
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan jwt:generate 
    
**Certifique-se de definir as informações de conexão de banco de dados corretas antes de executar as migrações** [Environment variables](#environment-variables)

    php artisan migrate
    php artisan serve

## Dependencies

- [jwt-auth](https://github.com/tymondesigns/jwt-auth) - Para autenticação usando JSON Web Tokens
- [L5 Swagger](https://github.com/DarkaOnLine/L5-Swagger) - Para documentação da api

## Environment variables

- `.env` - As variáveis de ambiente podem ser definidas neste arquivo

***Note*** : Você pode definir rapidamente as informações do banco de dados e outras variáveis ​​neste arquivo e fazer com que o aplicativo funcione totalmente.

----------

# Testing API

Execute o servidor de desenvolvimento laravel

    php artisan serve

A API agora pode ser acessada em

    http://127.0.0.1:8000/api
    
Para visualizar a documentação da api acesse
    
    http://127.0.0.1:8000/api/documentation

Request headers

| **Required** 	| **Key**              	| **Value**            	|
|----------	|------------------	|------------------	|
| Yes      	| Content-Type     	| application/json 	|
| Yes      	| X-Requested-With 	| XMLHttpRequest   	|
| Yes 	    | Authorization    	| Token {JWT}      	|

Consulte a [especificação da API](#especificacao-da-api) para obter mais informações.

----------
 
# Autenticação
 
Este aplicativo usa o JSON Web Token (JWT) para lidar com a autenticação. O token é passado com cada solicitação usando o cabeçalho `Authorization` com o esquema `Token`. O middleware de autenticação JWT lida com a validação e autenticação do token. Verifique as fontes a seguir para saber mais sobre o JWT.
 
- https://jwt.io/introduction/
- https://self-issued.info/docs/draft-ietf-oauth-json-web-token.html
