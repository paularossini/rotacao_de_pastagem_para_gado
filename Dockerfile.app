FROM php:8.1.0-fpm

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql
