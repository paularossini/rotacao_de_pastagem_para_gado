FROM php:8.1.0-fpm

# Instala o pacote mysql-client
RUN apt-get update \
    && apt-get install -y default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Instala dependências
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    git \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configura diretório 
WORKDIR /var/www/html

# Copia os arquivos do projeto
COPY . .

# Copia o entrypoint
COPY entrypoint.sh /var/www/html/entrypoint.sh

# Da permissao do entrypoint
RUN chmod +x /var/www/html/entrypoint.sh
