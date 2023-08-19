FROM php:8.1-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt update -y && apt install git zip unzip curl -y

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /app/project

RUN ls -la

