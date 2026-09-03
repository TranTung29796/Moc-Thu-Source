FROM php:8.3-apache

RUN docker-php-ext-install pdo_mysql && a2enmod rewrite
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/bookstore.ini
WORKDIR /var/www/html
COPY . /var/www/html
RUN mkdir -p public/uploads && chown -R www-data:www-data public/uploads
