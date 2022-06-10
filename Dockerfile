FROM php:8.1-fpm-alpine
COPY . /var/www
COPY conf/php.ini /etc/php/7.1/fpm/conf.d/40-custom.ini
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www
RUN composer install

