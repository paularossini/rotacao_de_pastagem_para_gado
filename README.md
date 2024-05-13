# --- SUBA O DOCKER ---
docker-compose up -d

# --- CRIE O BANCO DE DADOS ---
docker exec -it rotacao_de_pastagem_para_gado_db_1 mysql -uroot -proot
CREATE DATABASE rotacao_pastagem;

# --- SUBA O SERVIDOR ---
Saia do mysql (exit) e migre as tabelas:
php artisan migrate

# --- SUBA O SERVIDOR ---
php artisan serve