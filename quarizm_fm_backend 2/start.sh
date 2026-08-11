#!/usr/bin/env bash
set -e

mkdir -p bootstrap/cache
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
chmod -R 777 bootstrap/cache storage || true

if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force
fi

php artisan migrate --force
php artisan db:seed --class="Database\\Seeders\\FmDemoSeeder" --force || true

php artisan serve --host 0.0.0.0 --port "${PORT:-8000}"
