ARG PHP_VERSION=8.1

FROM php:${PHP_VERSION}-fpm-alpine

WORKDIR /var/www

RUN apk add --no-cache make


RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
ADD docker/config/php.ini /usr/local/etc/php/conf.d/app.ini

RUN apk add --no-cache gnupg \
 && wget -O phive.phar "https://phar.io/releases/phive.phar" \
 && wget -O phive.phar.asc "https://phar.io/releases/phive.phar.asc" \
 && gpg --keyserver hkps://keys.openpgp.org --recv-keys 0x6AF725270AB81E04D79442549D8A98B29B2D5D79 \
 && gpg --verify phive.phar.asc phive.phar \
 && rm phive.phar.asc \
 && chmod +x phive.phar \
 && mv phive.phar /usr/local/bin/phive

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
ENV PATH "$PATH:/var/www/bin"
# This is the place for you to put your project specific image requirements!
RUN apk add --no-cache tzdata && ln -snf /usr/share/zoneinfo/Europe/Berlin /etc/localtime && echo "Europe/Berlin" > /etc/timezone

RUN apk add --no-cache nginx supervisor
RUN rm -rf /var/www/**
COPY docker/config/fpm/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/config/fpm/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

ENTRYPOINT supervisord -c /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80