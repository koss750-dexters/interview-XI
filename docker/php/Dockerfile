FROM php:8.2-fpm-alpine

# Install dependencies
RUN apk add --no-cache \
    postgresql-dev \
    postgresql-client \
    zip \
    unzip \
    git

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Configure Git safe directory for Composer and clear cache
RUN git config --global --add safe.directory /var/www/html && \
    composer clear-cache

# Copy application first
COPY . .

# Ensure vendor/bin exists and configure Composer's bin-dir
RUN mkdir -p vendor/bin && composer config bin-dir vendor/bin

# Allow Yii2 composer plugin and install dependencies
RUN composer config --no-plugins allow-plugins.yiisoft/yii2-composer true && \
    composer install --optimize-autoloader



# Copy and set permissions for startup script
COPY docker/php/start.sh /start.sh
RUN chmod +x /start.sh ./yii

# Set permissions
RUN chown -R www-data:www-data /var/www/html

CMD ["/start.sh"]
