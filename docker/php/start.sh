#!/bin/sh

# Wait for postgres
until pg_isready -h postgres -p 5432 -U user; do
  echo "Waiting for postgres..."
  sleep 2
done

# Run migrations
php /var/www/html/yii migrate --interactive=0

# Start PHP-FPM
php-fpm
