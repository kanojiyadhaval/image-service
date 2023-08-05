# Use the official PHP image as the base image
FROM php:8.0-fpm

# Install GD extension for image processing
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy composer.json and composer.lock
COPY composer.json ./

# Install project dependencies
RUN composer install

# Copy the application code
COPY src ./src
COPY public ./public
COPY images ./images

# Set proper permissions for the images folder and sub-folders
RUN chmod -R 755 ./images

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
