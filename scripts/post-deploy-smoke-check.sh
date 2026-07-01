#!/usr/bin/env bash
set -euo pipefail

BASE_URL="${APP_URL_PUBLIC:-${APP_URL:-}}"
BASE_URL="${BASE_URL%/}"

if [ -z "$BASE_URL" ]; then
  echo "!! APP_URL_PUBLIC atau APP_URL wajib diisi untuk smoke check." >&2
  exit 1
fi

if ! command -v curl >/dev/null 2>&1; then
  echo "!! curl tidak tersedia di server. Smoke check tidak bisa dijalankan." >&2
  exit 1
fi

TMP_DIR="$(mktemp -d)"
trap 'rm -rf "$TMP_DIR"' EXIT

pass_count=0

check_route() {
  local path="$1"
  local marker="${2:-}"
  local expected_status="${3:-200}"
  local safe_name
  safe_name="$(echo "$path" | tr '/:?&=' '_')"
  local body_file="$TMP_DIR/${safe_name}.txt"
  local status

  status="$(curl -k -sS -L -o "$body_file" -w "%{http_code}" "${BASE_URL}${path}")"

  if [ "$status" != "$expected_status" ]; then
    echo "FAIL ${path} -> HTTP ${status} (expected ${expected_status})" >&2
    exit 1
  fi

  if [ -n "$marker" ] && ! grep -Fq "$marker" "$body_file"; then
    echo "FAIL ${path} -> marker tidak ditemukan: ${marker}" >&2
    exit 1
  fi

  echo "PASS ${path}"
  pass_count=$((pass_count + 1))
}

check_json_route() {
  local path="$1"
  local marker="${2:-}"
  local expected_status="${3:-200}"
  local safe_name
  safe_name="$(echo "$path" | tr '/:?&=' '_')"
  local body_file="$TMP_DIR/${safe_name}.json"
  local header_file="$TMP_DIR/${safe_name}.headers"
  local status

  status="$(curl -k -sS -L -D "$header_file" -o "$body_file" -H "Accept: application/json" -w "%{http_code}" "${BASE_URL}${path}")"

  if [ "$status" != "$expected_status" ]; then
    echo "FAIL ${path} -> HTTP ${status} (expected ${expected_status})" >&2
    exit 1
  fi

  if ! grep -Fiq "content-type: application/json" "$header_file"; then
    echo "FAIL ${path} -> response bukan JSON" >&2
    exit 1
  fi

  if [ -n "$marker" ] && ! grep -Fq "$marker" "$body_file"; then
    echo "FAIL ${path} -> marker JSON tidak ditemukan: ${marker}" >&2
    exit 1
  fi

  echo "PASS ${path} [json]"
  pass_count=$((pass_count + 1))
}

echo "==> Menjalankan smoke check untuk ${BASE_URL}"

check_route "/" "Dayakarya"
check_route "/masuk" "Masuk dengan Google"
check_route "/daftar" "Daftar dengan Google"
check_route "/leaderboard" "Leaderboard Dayakarya"
check_route "/privacy" "Kebijakan Privasi"
check_route "/terms" "Syarat Layanan"
check_route "/hapus-akun" "Penghapusan Akun"
check_route "/manifest.webmanifest" "\"name\": \"Dayakarya\""
check_route "/offline.html" "Koneksi sedang tidak tersedia."
check_route "/sw.js" "const VERSION = 'dayakarya-v2';"
check_json_route "/api/v1/leaderboard" "\"top_works\""

echo "==> Smoke check selesai: ${pass_count} route lolos"
