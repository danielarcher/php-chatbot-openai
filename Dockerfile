FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql pgsql sockets


# Create a new user "appuser" with user ID 1000
RUN useradd -u 1000 appuser

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

USER appuser
