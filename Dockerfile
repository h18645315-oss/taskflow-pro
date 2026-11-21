FROM php:8.2-cli
RUN apt-get update && apt-get install -y zip unzip git libsqlite3-dev nodejs npm && docker-php-ext-install pdo pdo_sqlite
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build
RUN touch database/database.sqlite && chmod -R 775 storage bootstrap/cache database
CMD php artisan config:clear && php artisan migrate --force && php -S 0.0.0.0:10000 -t public

