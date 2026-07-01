#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${APP_DIR:-$(pwd)}"
DEPLOY_BRANCH="${DEPLOY_BRANCH:-main}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
RUN_MIGRATIONS="${RUN_MIGRATIONS:-true}"
RUN_STORAGE_LINK="${RUN_STORAGE_LINK:-true}"
RESTART_QUEUE="${RESTART_QUEUE:-true}"
RUN_FILAMENT_ASSETS="${RUN_FILAMENT_ASSETS:-true}"

cd "$APP_DIR"

echo "==> Deploy branch: ${DEPLOY_BRANCH}"
echo "==> App dir: ${APP_DIR}"

if [ ! -f composer.lock ]; then
  echo "!! WARNING: composer.lock tidak ditemukan. Dependency yang terpasang bisa berbeda antar deploy." >&2
fi

read -r -a PHP_CMD <<< "$PHP_BIN"
read -r -a COMPOSER_CMD <<< "$COMPOSER_BIN"

"${COMPOSER_CMD[@]}" install --no-dev --optimize-autoloader --no-interaction

if [ "$RUN_MIGRATIONS" = "true" ]; then
  "${PHP_CMD[@]}" artisan migrate --force
fi

if [ "$RUN_STORAGE_LINK" = "true" ]; then
  "${PHP_CMD[@]}" artisan storage:link || true
fi

ln -sfn public/css css
ln -sfn public/js js
ln -sfn public/img img
ln -sfn public/manifest.json manifest.json
ln -sfn public/sw.js sw.js

if [ "$RUN_FILAMENT_ASSETS" = "true" ]; then
  "${PHP_CMD[@]}" artisan filament:assets
fi

"${PHP_CMD[@]}" artisan optimize:clear
"${PHP_CMD[@]}" artisan config:cache
"${PHP_CMD[@]}" artisan route:cache
"${PHP_CMD[@]}" artisan view:cache

if [ "$RESTART_QUEUE" = "true" ]; then
  "${PHP_CMD[@]}" artisan queue:restart || true
fi

echo "==> Deploy selesai"
