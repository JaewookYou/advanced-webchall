FROM php:7.4-apache

# Install required PHP extensions and tools
RUN apt-get update && apt-get install -y \
    gcc \
    make \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy and compile readflag binary
COPY docker/readflag.c /tmp/readflag.c
RUN gcc /tmp/readflag.c -o /readflag \
    && chmod 4755 /readflag \
    && rm /tmp/readflag.c

# Create flag file
RUN echo "FLAG{mixed_present_set_for_web_vulns!__isn't_it_fun?}" > /flag.txt \
    && chmod 600 /flag.txt \
    && chown root:root /flag.txt

# Create upload directories with proper permissions
RUN mkdir -p /var/www/html/uploads/thumbnails && \
    chown -R www-data:www-data /var/www/html

# Set upload limits and disable phar.readonly
RUN echo "upload_max_filesize = 10M" > /usr/local/etc/php/conf.d/uploads.ini && \
    echo "post_max_size = 10M" >> /usr/local/etc/php/conf.d/uploads.ini && \
    echo "phar.readonly = 0" >> /usr/local/etc/php/conf.d/uploads.ini