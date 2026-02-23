FROM composer:2 AS laravel-base

WORKDIR /opt
RUN composer create-project laravel/laravel clah-laravel --no-interaction --prefer-dist

FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

COPY --from=laravel-base /opt/clah-laravel /var/www/html
COPY ./src/app /var/www/html/app
COPY ./src/bootstrap/app.php /var/www/html/bootstrap/app.php
COPY ./src/bootstrap/providers.php /var/www/html/bootstrap/providers.php
COPY ./src/routes/api.php /var/www/html/routes

EXPOSE 9000

CMD ["php-fpm", "-F"]
