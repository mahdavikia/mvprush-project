FROM php:8.2-fpm

ARG user=laravel
ARG uid=1000

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev \
    libpq-dev libjpeg-dev libfreetype6-dev libssl-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip \
    && docker-php-ext-enable pdo_mysql

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user && \
    mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www

COPY . .

RUN chown -R $user:$user /var/www

USER $user

RUN composer install --no-interaction --prefer-dist --optimize-autoloader


CMD ["php-fpm"]
