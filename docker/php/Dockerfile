FROM php:8.1-fpm

# Install required packages
RUN apt-get update && apt-get install -y wget git-core libzip-dev zip unzip

# Install php extensions
RUN printf "\n" | pecl install apcu
RUN docker-php-ext-install mysqli pdo pdo_mysql zip && docker-php-ext-enable pdo_mysql zip

# Enable installed extensions
RUN docker-php-ext-enable apcu

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin

# Change workdir
WORKDIR /var/www

# Environment variables
ENV PATH="/var/www/vendor/bin:$PATH"

CMD ["php-fpm"]
