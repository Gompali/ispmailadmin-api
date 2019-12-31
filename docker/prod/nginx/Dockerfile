FROM nginx:1.16-alpine as web

ARG SRC
ARG CONF

RUN apk add --update
RUN mkdir -p /var/www/project/public && rm -rf /var/www/html

RUN rm /etc/nginx/conf.d/default.conf

COPY $CONF/* /etc/nginx/conf.d/
COPY $SRC /var/www/project/public

RUN chmod -R 755 /var/www/project/public
RUN chmod 644 /var/www/project/public/index.php

EXPOSE 80