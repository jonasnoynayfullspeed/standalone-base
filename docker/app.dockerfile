FROM php:8.1-fpm

RUN apt-get update -y \
    && apt-get install -y libpng-dev \
    libjpeg-dev \
    zip \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libicu-dev \
    git \
    libpq-dev

RUN docker-php-ext-configure gd \
	&& docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install gd \
    && apt-get install -y libonig-dev

RUN apt-get install -y npm \
    && npm cache clean --force \
    && npm install n -g \
    # && n stable \
    && n 16.17.0 \
    # Remove old "nodejs, npm" installed first to avoid confusing.
    && apt-get purge -y nodejs npm \
    && apt-get clean

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer