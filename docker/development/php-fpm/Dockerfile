FROM php:8.1-fpm-alpine

RUN apk add --update linux-headers

RUN apk add --no-cache autoconf g++ make \
    libxslt-dev \
    unzip \
    git \
    libzip-dev \
    zip \
    && pecl install xdebug \
    && rm -rf /tmp/pear \
    && docker-php-ext-install xsl zip \
    && docker-php-ext-enable xdebug

RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./common/php/conf.d /usr/local/etc/php/conf.d
COPY ./development/php/conf.d /usr/local/etc/php/conf.d
COPY ./development/php-fpm/conf.d /usr/local/etc/php/conf.d

WORKDIR /app

COPY ./development/php-fpm/entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint
