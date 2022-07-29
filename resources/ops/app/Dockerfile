
FROM node:16.13.1-alpine3.13 AS assets-build


ARG PPROXY=""
ARG PSPROXY=""
ARG RELEASE_HOST_IP=""


# ENV VERSION=v16.13.0 NPM_VERSION=8.1.0

WORKDIR /var/www/html
COPY . /var/www/html/

RUN npm config set proxy ${PPROXY}
RUN npm config set https-proxy ${PSPROXY}

# RUN if [ -z "$RELEASE_HOST_IP" ]; then \
#     echo "local env active" \
#   else \
#     npm config set proxy ${PPROXY} && \
#     npm config set https-proxy ${PSPROXY}; \
#   fi

RUN npm ci
RUN npm run build

#################################

FROM php:8.1.1-fpm-alpine AS base

RUN apk add --update zlib-dev libpng-dev libzip-dev $PHPIZE_DEPS
RUN apk add git

RUN docker-php-ext-install exif
RUN docker-php-ext-install gd
RUN docker-php-ext-install zip
RUN docker-php-ext-install pcntl
#RUN docker-php-ext-install pdo_mysql
RUN pecl install apcu
RUN docker-php-ext-enable apcu
RUN docker-php-ext-enable pcntl

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev
RUN docker-php-ext-install pdo pdo_pgsql

FROM base AS dev

COPY ./composer.json composer.json
COPY ./composer.lock composer.lock
COPY ./app app
COPY ./bootstrap bootstrap
COPY ./config config
COPY ./artisan artisan

FROM base AS build-fpm

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY ./artisan artisan

COPY ./composer.json composer.json

COPY ./bootstrap bootstrap
COPY ./app app
COPY ./config config
COPY ./routes routes
COPY ./public public


COPY . /var/www/html

RUN composer install

RUN composer dump-autoload -o

FROM build-fpm AS fpm

COPY --from=build-fpm /var/www/html /var/www/html
COPY --from=assets-build /var/www/html/public/build /var/www/html/public/build

RUN chown -R www-data:www-data storage
# RUN chmod -R 755 /var/www/html/storage