FROM composer:2 AS composer
FROM php:8.1-fpm AS php

# Add PHP extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions intl pdo_mysql zip

# Set timezone
RUN echo 'Europe/Paris' > /etc/timezone

COPY --from=composer /usr/bin/composer /usr/bin/composer

USER www-data

WORKDIR /www
