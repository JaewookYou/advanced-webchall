FROM php:7.4-apache

# Set working directory
WORKDIR /var/www/html

# Copy public website files
COPY src/public/ /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html 