FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache bash curl git \
    && mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html

EXPOSE 9000

CMD ["php-fpm", "-F"]
