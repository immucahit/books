# API Installation

```shell
docker compose exec api sh -c "cp .env.example .env && composer install && composer update && php artisan key:generate"
```
# WEB Installation

```shell
docker compose exec web sh -c "cp .env.example .env && composer install && composer update && php artisan key:generate"
```