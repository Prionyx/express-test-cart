FROM php:8.1-cli-alpine

RUN apk add --no-cache unzip bash

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

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet \
    && rm -rf /root/.composer/cache

RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

WORKDIR /app
