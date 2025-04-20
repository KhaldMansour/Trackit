FROM php:8.3-apache
WORKDIR /var/www/html

# Mod Rewrite
RUN a2enmod rewrite

COPY . /var/www/html

RUN cp .env.example .env
# Linux Library
RUN apt-get update -y && apt-get install -y \
    libicu-dev \
    libmariadb-dev \
    unzip zip \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev 

# PHP Extension
RUN docker-php-ext-install gettext intl pdo_mysql gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN mkdir -p storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chmod -R 755 storage

# Copy the custom Apache config
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf


EXPOSE 80
