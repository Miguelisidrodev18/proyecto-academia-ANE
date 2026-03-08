#!/bin/bash
echo "🚀 INICIANDO ACTUALIZACIÓN DESDE GITHUB"
cd /home/u188616411/domains/estelarsoftware.leongrup.com/public_html/nueva-era

# Guardar .env
cp laravel-core/.env .env.backup

# Git pull
git pull origin main

# Restaurar .env
mv .env.backup laravel-core/.env

# Actualizar PHP
cd laravel-core
composer install --no-dev --optimize-autoloader

# Migraciones
php artisan migrate --force

# Limpiar caché
php artisan optimize:clear

cd ..
echo "✅ ACTUALIZACIÓN COMPLETADA"
