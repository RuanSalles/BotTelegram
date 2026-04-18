# Quando sair oficialmente:
# FROM php:8.5-fpm

FROM php:8.3-fpm

# Dependências
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# Extensões PHP
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# PHPUnit (global - opcional)
RUN composer global require phpunit/phpunit

ENV PATH="/root/.composer/vendor/bin:${PATH}"

# Copia config do Xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www