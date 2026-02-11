FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache bash curl git unzip libzip-dev \
    && docker-php-ext-install zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer create-project --prefer-dist --no-interaction laravel/laravel /var/www/html \
    && chown -R www-data:www-data /var/www/html

COPY src/app /var/www/html/app
COPY src/routes/api.php /var/www/html/routes/api.php

EXPOSE 9000

CMD ["php-fpm", "-F"]
