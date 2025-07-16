FROM php:8.1-apache


RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


RUN a2enmod rewrite



COPY . /var/www/html

WORKDIR /var/www/html


RUN composer install --optimize-autoloader --no-dev

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
