FROM php:8.3-fpm-alpine

RUN set -eux; \
    apk add --no-cache \
        bash \
        curl \
        git \
        oniguruma-dev \
        libzip-dev \
        zip \
        unzip; \
    docker-php-ext-install pdo_mysql bcmath zip;

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]