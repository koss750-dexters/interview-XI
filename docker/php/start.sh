#!/bin/sh

# Wait for postgres
until pg_isready -h postgres -p 5432 -U user; do
  echo "Waiting for postgres..."
  sleep 2
done

# Run migrations for main database
echo "Running database migrations..."
php /var/www/html/yii migrate --interactive=0

# The test database setup is handled by the second migration
echo "Test database setup completed via migrations"

# Verify test database setup
echo "Verifying test database setup..."
psql -h postgres -U user -d loans_test -c "SELECT 1;" > /dev/null 2>&1 && echo "Test database is ready" || echo "Test database setup issue"

# Run tests to verify everything is working
echo "Running tests to verify setup..."
vendor/bin/phpunit --no-coverage

echo "Setup complete! Starting PHP-FPM..."

# Start PHP-FPM
php-fpm
