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
RUN_SMOKE_CHECKS="${RUN_SMOKE_CHECKS:-false}"
APP_URL_PUBLIC="${APP_URL_PUBLIC:-${APP_URL:-}}"

if [ ! -d "$APP_DIR" ]; then
  echo "!! ERROR: APP_DIR tidak ditemukan: ${APP_DIR}" >&2
  echo "   Pastikan secret APP_DIR mengarah ke root proyek Laravel di server FastPanel." >&2
  exit 1
fi

cd "$APP_DIR"

if [ ! -d .git ]; then
  echo "!! ERROR: ${APP_DIR} bukan working tree Git yang valid." >&2
  echo "   Pastikan secret APP_DIR mengarah ke folder repo Dayakarya di server." >&2
  exit 1
fi

echo "==> Deploy branch: ${DEPLOY_BRANCH}"
echo "==> App dir: ${APP_DIR}"

if [ ! -f composer.lock ]; then
  echo "!! WARNING: composer.lock tidak ditemukan. Dependency yang terpasang bisa berbeda antar deploy." >&2
fi

resolve_php_cmd() {
  local candidates=()
  local candidate
  local cmd=()

  if [ -n "${PHP_BIN:-}" ]; then
    candidates+=("$PHP_BIN")
  fi

  candidates+=("php" "/usr/bin/php" "/usr/local/bin/php")

  for candidate in "${candidates[@]}"; do
    read -r -a cmd <<< "$candidate"
    if "${cmd[@]}" -v >/dev/null 2>&1; then
      PHP_CMD=("${cmd[@]}")
      return 0
    fi
  done

  return 1
}

resolve_composer_cmd() {
  local candidates=()
  local candidate
  local cmd=()
  local php_joined="${PHP_CMD[*]}"

  if [ -n "${COMPOSER_BIN:-}" ]; then
    candidates+=("$COMPOSER_BIN")
  fi

  candidates+=("composer")

  if [ -f "$APP_DIR/composer" ]; then
    candidates+=("${php_joined} $APP_DIR/composer")
  fi

  if [ -f "$APP_DIR/composer.phar" ]; then
    candidates+=("${php_joined} $APP_DIR/composer.phar")
  fi

  candidates+=("${php_joined} /usr/local/bin/composer" "${php_joined} /usr/bin/composer")

  for candidate in "${candidates[@]}"; do
    read -r -a cmd <<< "$candidate"
    if "${cmd[@]}" --version >/dev/null 2>&1; then
      COMPOSER_CMD=("${cmd[@]}")
      return 0
    fi
  done

  return 1
}

if ! resolve_php_cmd; then
  echo "!! ERROR: PHP_BIN tidak bisa dijalankan: ${PHP_BIN}" >&2
  echo "   Pastikan secret PHP_BIN mengarah ke binary PHP yang valid di server FastPanel." >&2
  exit 1
fi

if ! resolve_composer_cmd; then
  echo "!! ERROR: COMPOSER_BIN tidak bisa dijalankan: ${COMPOSER_BIN}" >&2
  echo "   Isi secret COMPOSER_BIN dengan command Composer yang valid, misalnya 'composer' atau 'php /path/to/composer'." >&2
  exit 1
fi

echo "==> PHP command: ${PHP_CMD[*]}"
echo "==> Composer command: ${COMPOSER_CMD[*]}"

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
ln -sfn public/offline.html offline.html
ln -sfn public/sw.js sw.js

if [ "$RUN_FILAMENT_ASSETS" = "true" ]; then
  "${PHP_CMD[@]}" artisan filament:assets
fi

"${PHP_CMD[@]}" artisan optimize:clear
"${PHP_CMD[@]}" artisan config:cache
"${PHP_CMD[@]}" artisan route:cache
"${PHP_CMD[@]}" artisan view:cache

if [ -n "${APP_URL_PUBLIC:-}" ]; then
  echo "==> Meminta reset opcache web"
  OPCACHE_RESET_URL="$("${PHP_CMD[@]}" artisan deploy:opcache-reset-url --base="${APP_URL_PUBLIC}")"

  if command -v curl >/dev/null 2>&1; then
    curl --fail --silent --show-error "$OPCACHE_RESET_URL" >/dev/null
  else
    "${PHP_CMD[@]}" -r '
      $url = $argv[1] ?? "";
      if (!$url) {
          fwrite(STDERR, "URL reset opcache kosong.\n");
          exit(1);
      }
      $context = stream_context_create(["http" => ["timeout" => 20]]);
      $response = @file_get_contents($url, false, $context);
      if ($response === false) {
          fwrite(STDERR, "Reset opcache web gagal dipanggil.\n");
          exit(1);
      }
    ' "$OPCACHE_RESET_URL"
  fi
else
  echo "==> APP_URL_PUBLIC kosong, reset opcache web dilewati"
fi

if [ "$RESTART_QUEUE" = "true" ]; then
  "${PHP_CMD[@]}" artisan queue:restart || true
fi

if [ "$RUN_SMOKE_CHECKS" = "true" ]; then
  echo "==> Menjalankan smoke check pasca deploy"
  export APP_URL_PUBLIC
  chmod +x scripts/post-deploy-smoke-check.sh
  bash scripts/post-deploy-smoke-check.sh
fi

echo "==> Deploy selesai"
