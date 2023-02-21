FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql

COPY . /var/www/html

WORKDIR /var/www/html

RUN curl --silent --show-error https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

RUN composer install --prefer-dist --no-dev --no-scripts --no-progress --no-suggest --optimize-autoloader

EXPOSE 9000

CMD ["php-fpm"]