FROM php:8.2-apache
WORKDIR /var/www

# Install dependencies + PostgreSQL client libs
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions untuk PostgreSQL
RUN docker-php-ext-install pdo_pgsql pgsql
RUN a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# (optional) cek versi composer
RUN composer --version