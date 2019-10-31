FROM php:7.2-apache

# Additional tools
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
    curl \
    openssl \
    nano \
    zip \
    git

# Common php-ext and requirements
RUN apt-get install -y --no-install-recommends libpq-dev libz-dev \
 && docker-php-ext-install pdo_mysql \
 && docker-php-ext-install bcmath \
 && docker-php-ext-configure bcmath --enable-bcmath \
 && docker-php-ext-install mysqli \
 && docker-php-ext-configure zip \
 && docker-php-ext-install zip \
 && docker-php-ext-install sockets

# Enable apache modules
RUN a2enmod rewrite

# restart apache
RUN service apache2 restart

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer