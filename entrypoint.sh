#!/bin/sh

# Espera o MySQL iniciar
echo "Aguardando o MySQL iniciar..."
while ! mysqladmin ping -h"$DB_HOST" --silent; do
    sleep 1
done

# Cria o banco de dados
echo "Criando banco de dados se não existir..."
mysql -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD -e "CREATE DATABASE IF NOT EXISTS \`${DB_DATABASE}\`;"

# Instala as dependências do Composer
echo "Instalando as dependências Composer..."
composer install

# Roda as migrações
echo "Rodando as migrações..."
php artisan migrate --force

# Inicia o servidor
echo "Iniciando o servidor..."
php artisan serve --host=0.0.0.0 --port=8002
