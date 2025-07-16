FROM composer:2.5 as build
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

FROM php:8.2-apache
RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    zip unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip

WORKDIR /var/www/html
COPY . .
COPY --from=build /app/vendor ./vendor

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
