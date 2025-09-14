# Stage 1: build dependencies with Composer image (has git/zip)
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts

# Stage 2: runtime using FrankenPHP
FROM dunglas/frankenphp:1.1.0-php8.3
WORKDIR /app

# Install required packages and PHP extensions (Postgres)
RUN set -eux; \
	apt-get update; \
	apt-get install -y --no-install-recommends git unzip libpq-dev; \
	docker-php-ext-install pdo_pgsql; \
	rm -rf /var/lib/apt/lists/*

# Copy application code
COPY . .

# Copy vendor directory from builder stage
COPY --from=vendor /app/vendor ./vendor

# Ensure storage and bootstrap/cache are writable
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache || true

# Provide Caddy/FrankenPHP config and port
COPY Caddyfile /etc/caddy/Caddyfile
ENV SERVER_NAME=:8000
EXPOSE 8000

# Start FrankenPHP via Caddy
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile", "--adapter", "caddyfile"]
