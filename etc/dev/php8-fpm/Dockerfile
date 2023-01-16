FROM php:8.0-fpm

RUN apt-get update && apt-get install -y git openssl procps acl zlib1g-dev libpq-dev libzip-dev libxml2-dev

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql bcmath pdo_pgsql

# Create an alias for Symfony3 console
RUN echo 'alias symfony="php bin/console"' >> ~/.bashrc

RUN echo 'memory_limit = 16000M' > /usr/local/etc/php/php.ini

WORKDIR /var/www/app
