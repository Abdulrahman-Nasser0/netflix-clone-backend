# Stage 1: build dependencies with Composer image (has git/zip)
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts

# Stage 2: runtime using FrankenPHP
FROM dunglas/frankenphp:1.1.0-php8.3
WORKDIR /app

# Copy application code
COPY . .

# Copy vendor directory from builder stage
COPY --from=vendor /app/vendor ./vendor

# Ensure storage and bootstrap/cache are writable
RUN chmod -R 775 storage bootstrap/cache || true

# Expose port 8000 (FrankenPHP default)
EXPOSE 8000

# Start the server
CMD ["frankenphp", "serve", "--port=8000", "public/index.php"]
