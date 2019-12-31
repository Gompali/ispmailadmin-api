FROM php:7.4-fpm-alpine as app

ARG COMPOSER_AUTH
ARG SRC

# Environment
ENV TZ=Europe/Paris
ENV DEBIAN_FRONTEND=noninteractive \
    APP_ENV=prod \
    APP_DEBUG=0

RUN mkdir -p /var/www/project/var/jwt  /var/www/project/var/cache/prod /var/www/project/var/cache/log /etc/nginx/conf.d \
&& chmod 777 -R /var/www/project/var \
&& rm -rf /var/www/html

WORKDIR /var/www/project

RUN apk add  --update --no-cache tzdata && cp /usr/share/zoneinfo/$TZ /etc/localtime && apk del tzdata
RUN apk --no-cache add ${PHPIZE_DEPS}  \
    bash pcre-dev icu-dev icu-libs \
    imagemagick imagemagick-libs imagemagick-dev \
    postgresql-dev \
    rabbitmq-c rabbitmq-c-dev \
    zip zlib-dev libzip-dev \
 && wget https://raw.githubusercontent.com/php/php-src/PHP-7.4/php.ini-production -O /usr/local/etc/php/php.ini \
 && docker-php-ext-install bcmath calendar intl json opcache zip pdo pdo_pgsql \
 && pecl install amqp imagick redis \
 && docker-php-ext-enable amqp redis \
# && apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev \
# && docker-php-ext-configure gd \
#    --with-freetype-dir=/usr/include/ \
#    --with-png-dir=/usr/include/ \
#    --with-jpeg-dir=/usr/include/ && \
#  NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) \
#  && docker-php-ext-install -j${NPROC} gd \
#  && apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev pcre-dev ${PHPIZE_DEPS} \
  && rm -rf /var/cache/apk/* && apk del ${PHPIZE_DEPS}

COPY $SRC /var/www/project

RUN cd /var/www/project

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer  && ./composer global require hirak/prestissimo --ansi \
 && ./composer install --optimize-autoloader --no-dev --no-progress --no-suggest --classmap-authoritative --no-interaction \
 && mv composer /usr/local/bin/composer

RUN chown -R www-data:www-data /var/www/project

EXPOSE 9000

USER www-data
