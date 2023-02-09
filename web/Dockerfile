FROM php:8.1-fpm-alpine

RUN apk add --no-cache nginx wget libzip libzip-dev libpng libpng-dev libxml2-dev libjpeg freetype-dev libwebp-dev libjpeg-turbo-dev
RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /app
COPY . /app

RUN docker-php-ext-install mysqli pdo pdo_mysql gd zip soap

RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype
RUN docker-php-ext-install gd


RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && \
    /usr/local/bin/composer install

RUN chown -R www-data: /app

CMD sh /app/docker/startup.sh

