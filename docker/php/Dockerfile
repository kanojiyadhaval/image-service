# Use the official PHP image as the base image
FROM php:8.0-fpm

# Install GD extension for image processing
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Install libzip and its development headers
RUN apt-get install -y libzip-dev

# Install additional PHP extensions (git, zip, and others)
RUN apt-get install -y git \
    && docker-php-ext-install zip \
    # Add other PHP extensions as needed
    # For example, if you need mysqli extension:
    # && docker-php-ext-install mysqli \
    # Add more extensions here as needed
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy composer.json and composer.lock
COPY composer.json phpunit.xml ./

# Install project dependencies
RUN composer install

# Copy the application code
COPY src ./src
COPY public ./public
COPY images ./images
COPY tests ./tests

# Set proper permissions for the images folder and sub-folders
RUN chmod -R 777 /var/www/html/images/modified

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
