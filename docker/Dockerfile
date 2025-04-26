FROM php:7.4-apache

# Install required packages
RUN apt-get update && apt-get install -y \
    libpng-dev \
    gcc \
    make \
    && docker-php-ext-install gd pdo pdo_mysql

# Configure Apache
RUN a2enmod rewrite
RUN service apache2 restart

# Copy source files
COPY ./src/ /var/www/html/
COPY ./docker/readflag.c /readflag.c

# Compile readflag binary with setuid
RUN gcc -o /readflag /readflag.c && \
    chmod u+s /readflag && \
    chown root:root /readflag

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html/uploads && \
    chmod 777 /var/www/html/uploads

EXPOSE 80 