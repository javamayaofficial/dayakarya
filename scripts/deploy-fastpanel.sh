#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${APP_DIR:-$(pwd)}"
DEPLOY_BRANCH="${DEPLOY_BRANCH:-main}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
RUN_MIGRATIONS="${RUN_MIGRATIONS:-true}"
RUN_STORAGE_LINK="${RUN_STORAGE_LINK:-true}"
RESTART_QUEUE="${RESTART_QUEUE:-true}"

cd "$APP_DIR"

echo "==> Deploy branch: ${DEPLOY_BRANCH}"
echo "==> App dir: ${APP_DIR}"

"$COMPOSER_BIN" install --no-dev --optimize-autoloader --no-interaction

if [ "$RUN_MIGRATIONS" = "true" ]; then
  "$PHP_BIN" artisan migrate --force
fi

if [ "$RUN_STORAGE_LINK" = "true" ]; then
  "$PHP_BIN" artisan storage:link || true
fi

"$PHP_BIN" artisan optimize:clear
"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache

if [ "$RESTART_QUEUE" = "true" ]; then
  "$PHP_BIN" artisan queue:restart || true
fi

echo "==> Deploy selesai"
