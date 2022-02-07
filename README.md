# post_comments
QuickDev Assessment

## Projeto desenvolvido com PHP 8.1 e Laravel 8
- Laravel Breeze (Autenticação)
- Laravel Auditing (Histórico de mudanças)
- Docker
- Postgres 12.4
- Reacj
- Mui (Biblioteca visual)

## Backend
Entre na pasta /backend/post_comment-app e execute os comandos:

    docker-compose up -d --build
    docker-compose run --rm php php artisan migrate
    docker-compose run --rm php php artisan db:seed
### Endpoints
Para testar os endpoints e verificar os objestos Json a serem transitados, verificar o arquivo do Insomnia que se encontra /backend/post_comment-app/Insomnia-All_2022-02-07.json

## Front
Entre na pasta /frontend e execute os comandos:
 

    yarn install
    yarn start
