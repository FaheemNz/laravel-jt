FROM php:7.4-fpm-alpine

RUN apk add --no-cache nginx wget

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /app
COPY . /app
COPY ./src /app

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && \
    /usr/local/bin/composer update --no-dev
    
RUN cd /app && \
    /usr/local/bin/php artisan key:generate

RUN chown -R www-data: /app

CMD sh /app/docker/startup.sh