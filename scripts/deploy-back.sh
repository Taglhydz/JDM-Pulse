#!/bin/bash
# Met à jour le backend sur AlwaysData, à lancer en SSH :
#   bash ~/jdmpulse/scripts/deploy-back.sh
set -e

cd "$(dirname "$0")/.."
git pull --ff-only

cd laravel-jdmpulse
composer install --no-dev --optimize-autoloader --no-interaction
php artisan migrate --force

# Met en cache config, routes et vues (à refaire après chaque modification du .env)
php artisan optimize

echo "Backend à jour"
