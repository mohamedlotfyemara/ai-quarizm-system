#!/usr/bin/env bash
set -e

if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force
fi

php artisan config:cache
php artisan migrate --force
php artisan db:seed --class="Database\\Seeders\\FmDemoSeeder" --force || true

php artisan serve --host 0.0.0.0 --port "${PORT:-8000}"
