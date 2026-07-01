# FastPanel Command Pack

Paket command ini dibuat agar eksekusi server FastPanel untuk Dayakarya bisa dilakukan cepat dengan metode copy-paste. Ganti `USERNAME` sesuai user FastPanel Anda.

> Jalankan per blok, jangan semua sekaligus jika Anda ingin memeriksa output tiap tahap.

---

## 1. Masuk ke Root Aplikasi

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
pwd
ls -la
```

---

## 2. Tarik Update Terbaru

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
git pull --ff-only origin main
git log -1 --oneline
```

---

## 3. Install Dependency

Jika Composer tersedia global:

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
composer install --no-dev --optimize-autoloader --no-interaction
```

Jika Composer lokal di root proyek:

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
php /var/www/USERNAME/data/www/dayakarya.id/composer install --no-dev --optimize-autoloader --no-interaction
```

---

## 4. Migrasi dan Asset Admin

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
php artisan migrate --force
php artisan filament:assets
```

---

## 5. Refresh Cache Laravel

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 6. Buat Ulang Symlink dan Restart Queue

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
php artisan storage:link || true
php artisan queue:restart || true
```

---

## 7. Cek Migration Google OAuth

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
php artisan migrate:status
```

Pastikan migration `2026_07_01_210000_add_google_auth_columns_to_users_table` berstatus `Ran`.

---

## 8. Cek Route Penting

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
php artisan route:list | grep auth/google
php artisan route:list | grep leaderboard
php artisan route:list | grep wallet
```

Jika `grep` tidak tersedia di server, cukup jalankan:

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
php artisan route:list
```

---

## 9. Lihat Log Jika Ada Error

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
tail -n 80 storage/logs/laravel.log
```

Untuk memantau live saat reproduksi bug:

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
tail -f storage/logs/laravel.log
```

---

## 10. Blok Cepat Lengkap

Gunakan blok ini jika Anda ingin satu kali tembak sesudah `.env` sudah benar:

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
git pull --ff-only origin main
php /var/www/USERNAME/data/www/dayakarya.id/composer install --no-dev --optimize-autoloader --no-interaction
php artisan migrate --force
php artisan filament:assets
php artisan storage:link || true
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart || true
php artisan migrate:status
```

---

## 10A. Jalankan Smoke Check Manual

Gunakan ini jika Anda ingin memverifikasi route utama setelah deploy:

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
APP_URL_PUBLIC=https://dayakarya.id bash scripts/post-deploy-smoke-check.sh
```

Jika ingin dijalankan otomatis setelah setiap deploy GitHub Actions, isi secret:

```text
RUN_SMOKE_CHECKS=true
APP_URL_PUBLIC=https://dayakarya.id
```

---

## 11. Urutan Uji Browser Setelah Command Selesai

1. Buka `https://dayakarya.id/masuk`
2. Uji `Masuk dengan Google`
3. Buka `https://dayakarya.id/wallet`
4. Buka `https://dayakarya.id/leaderboard`
5. Buka `https://dayakarya.id/privacy`
6. Buka `https://dayakarya.id/terms`
7. Buka `https://dayakarya.id/hapus-akun`

---

## 12. Dokumen Pendukung

- `docs/dayakarya-production-env-template.md`
- `docs/fastpanel-google-oauth-runbook.md`
- `docs/google-oauth-production-checklist.md`
- `docs/dayakarya-go-live-checklist.md`
