# Use FrankenPHP for Laravel (recommended by Fly.io)
FROM dunglas/frankenphp:1.1.0-php8.3

# Set working directory
WORKDIR /app


# Copy composer files
COPY composer.json composer.lock ./

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
	&& composer install --no-dev --optimize-autoloader --no-interaction

# Copy the rest of the app
COPY . .

# Ensure storage and bootstrap/cache are writable
RUN chmod -R 775 storage bootstrap/cache

# Expose port 8000 (FrankenPHP default)
EXPOSE 8000

# Start the server
CMD ["frankenphp", "serve", "--port=8000", "public/index.php"]
