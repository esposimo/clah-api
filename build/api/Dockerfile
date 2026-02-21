FROM composer:2 AS laravel-base

WORKDIR /opt
RUN composer create-project laravel/laravel clah-laravel --no-interaction --prefer-dist

FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

COPY --from=laravel-base /opt/clah-laravel /var/www/html
COPY app/Http/Controllers/HealthController.php /var/www/html/app/Http/Controllers/HealthController.php
COPY app/Http/Controllers/EnvironmentController.php /var/www/html/app/Http/Controllers/EnvironmentController.php
COPY app/Domain /var/www/html/app/Domain
COPY app/Infrastructure /var/www/html/app/Infrastructure
COPY app/Providers/AppServiceProvider.php /var/www/html/app/Providers/AppServiceProvider.php
COPY app/Services/PlatformStateService.php /var/www/html/app/Services/PlatformStateService.php
COPY bootstrap /var/www/html/bootstrap

EXPOSE 9000

CMD ["php-fpm", "-F"]
