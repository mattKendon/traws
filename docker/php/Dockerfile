FROM php:7-alpine

RUN docker-php-ext-install pdo pdo_mysql mysqli

COPY php.ini /usr/local/etc/php/php.ini

CMD ["php", "--version"]