FROM php:7.4-fpm-alpine
MAINTAINER Yannick Granger <yannick.granger@services.inter-invest.fr>

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV SYMFONYCLI_VERSION="4.11.3"

ENV TZ=Europe/Paris

RUN addgroup -S dockergroup
RUN adduser -D -u 501 -G dockergroup dockeruser

RUN apk add --no-cache tzdata && cp /usr/share/zoneinfo/$TZ /etc/localtime && apk del tzdata
RUN apk --no-cache add ${PHPIZE_DEPS} \
    postgresql-dev sqlite-dev \
    bash git pcre-dev \
    icu-dev icu-libs \
    imagemagick imagemagick-libs imagemagick-dev \
    libmemcached libmemcached-dev \
    libzip-dev \
    rabbitmq-c rabbitmq-c-dev \
    zip zlib-dev
    \
RUN docker-php-ext-install bcmath calendar intl json opcache pdo pdo_mysql pdo_pgsql pdo_sqlite zip \
    && pecl install amqp imagick memcached redis xdebug \
    && docker-php-ext-enable amqp memcached redis xdebug \
    && apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev \
    && apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev pcre-dev ${PHPIZE_DEPS}

#RUN docker-php-ext-configure gd \
#    --with-gd \
#    --with-freetype-dir=/usr/include/ \
#    --with-png-dir=/usr/include/ \
#    --with-jpeg-dir=/usr/include/ && \
#  NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
#  docker-php-ext-install -j${NPROC} gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && curl -sS https://get.symfony.com/cli/v${SYMFONYCLI_VERSION}/symfony_linux_amd64 -o /usr/local/bin/symfony.gz \
    && gzip -d /usr/local/bin/symfony.gz \
    && chmod +x /usr/local/bin/symfony
\
RUN { \
        echo 'opcache.memory_consumption=1024'; \
        echo 'opcache.interned_strings_buffer=32'; \
        echo 'opcache.max_accelerated_files=32000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

WORKDIR /var/www/html

#RUN /etc/init.d/apache2 restart
RUN chown -R dockeruser /var/www/html
RUN mkdir -p /var/www/html/public /var/www/html/var/cache /var/www/html/var/log
RUN chmod 777 -R /var/www/html/var

CMD ["symfony", "serve", "--dir=/var/www/html", "--allow-http", "--no-tls", "--port=8000"]

USER dockeruser

EXPOSE 8000