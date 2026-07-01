# Deploy Dayakarya ke FastPanel (Prioritas)

Panduan ini khusus **FastPanel**. Ada 2 mode:

- **Bootstrap awal**: server pertama kali clone dari GitHub, set `.env`, database, cron, document root.
- **Deploy otomatis**: setiap push ke branch `main`, GitHub Actions SSH ke server lalu menjalankan deploy.

---

## Prasyarat

- Server dengan FastPanel terpasang.
- Domain sudah diarahkan ke server (A record).
- Repository Dayakarya sudah ada di GitHub.
- Akses SSH ke server FastPanel.
- User server punya izin baca/tulis ke folder aplikasi.

---

## Langkah 1 — Buat Situs di FastPanel

1. Masuk panel FastPanel.
2. **Sites → Create Site**.
3. Masukkan domain (mis. `dayakarya.id`).
4. Pilih **PHP 8.2** (atau lebih baru) sebagai versi PHP situs.
5. Simpan.

---

## Langkah 2 — Buat Database

1. **Databases → Create Database**.
2. Catat: nama database, username, password.
3. Simpan — akan dipakai di `.env`.

---

## Langkah 3 — Ambil Kode dari GitHub

Buka terminal SSH ke server (atau fitur terminal FastPanel), lalu:

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
# kosongkan folder default bila perlu, lalu:
git clone https://github.com/javamayaofficial/dayakarya.git .
composer install --no-dev --optimize-autoloader
```

> Jika repo privat, gunakan Personal Access Token GitHub atau deploy key.

> Jika folder sudah berisi file default FastPanel, pindahkan atau hapus dulu isi placeholder sebelum `git clone .`.

---

## Langkah 4 — Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
nano .env
```

Isi:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dayakarya.id

DB_DATABASE=nama_db_dari_langkah_2
DB_USERNAME=user_db
DB_PASSWORD=password_db

PAYMENT_PROVIDER=duitku
DUITKU_MERCHANT_CODE=...
DUITKU_API_KEY=...
DUITKU_ENV=production

WA_PROVIDER=fonnte
FONNTE_TOKEN=...

EMAIL_PROVIDER=mailketing
MAILKETING_API_TOKEN=...

GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...
GOOGLE_REDIRECT_URI=https://dayakarya.id/auth/google/callback
```

---

## Langkah 4A - Aktifkan Google Login

Jika ingin memakai fitur login/register dengan Google, buat OAuth Client di Google Cloud Console:

1. Buka `APIs & Services -> Credentials`.
2. Buat `OAuth Client ID` tipe `Web application`.
3. Isi `Authorized redirect URI` persis:

```text
https://dayakarya.id/auth/google/callback
```

4. Salin `Client ID` dan `Client Secret` ke `.env`.
5. Setelah deploy, uji dari halaman `/masuk` dan `/daftar`.

> Pastikan domain production yang dipakai user sama dengan nilai `APP_URL`, agar callback Google tidak mismatch.

---

## Langkah 5 — Migrasi & Storage

```bash
php artisan migrate --seed
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

---

## Langkah 6 — Konfigurasi FastPanel untuk Root Proxy

Gunakan konfigurasi berikut di FastPanel agar request masuk lewat root proyek, lalu diteruskan ke `public/` oleh file proxy yang ada di repo:

1. **Static content -> Subdirectory**: kosong
2. **Backend -> Working subdirectory**: kosong
3. **Backend -> Application file**: `index.php`

Repo ini sudah menyertakan:

- root `index.php` sebagai proxy ke `public/index.php`
- root `.htaccess` untuk rewrite request ke `public/`
- symlink asset root yang akan dibuat ulang otomatis saat deploy

> Jangan arahkan lagi ke folder `public` lewat pengaturan subdirectory FastPanel, karena kombinasi itu sebelumnya memicu `403/500` dan redirect loop pada server ini.

---

## Langkah 7 — SSL (HTTPS)

1. **Sites → dayakarya.id → SSL**.
2. Aktifkan **Let's Encrypt** (gratis) → Issue.
3. Aktifkan **Force HTTPS**.

---

## Langkah 8 — Cron (Penjadwalan)

Di FastPanel: **Cron → Add**:

```
* * * * * cd /var/www/USERNAME/data/www/dayakarya.id && php artisan schedule:run >> /dev/null 2>&1
```

---

## Langkah 9 — Queue Worker (Notifikasi)

Cara sederhana lewat cron (jaga worker tetap hidup):
```
* * * * * cd /var/www/USERNAME/data/www/dayakarya.id && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

Cara ideal: buat service Supervisor bila server mengizinkan (lihat panduan VPS).

---

## Langkah 10 — Optimasi

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Langkah 11 — Siapkan Auto Deploy dari GitHub Actions

File workflow yang dipakai repo ini:

- `.github/workflows/deploy-fastpanel.yml`
- `scripts/deploy-fastpanel.sh`

Tambahkan **GitHub repository secrets** berikut:

```text
SSH_HOST=ip-atau-host-server
SSH_PORT=22
SSH_USERNAME=user_ssh
SSH_PRIVATE_KEY=isi_private_key_ssh
SSH_HOST_FINGERPRINT=SHA256:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
APP_DIR=/var/www/USERNAME/data/www/dayakarya.id
PHP_BIN=php
COMPOSER_BIN=composer
RUN_MIGRATIONS=true
RUN_STORAGE_LINK=true
RESTART_QUEUE=true
RUN_FILAMENT_ASSETS=true
```

Keterangan:

- `SSH_PRIVATE_KEY` = private key yang pasangannya sudah ditaruh di `~/.ssh/authorized_keys` server.
- `SSH_HOST_FINGERPRINT` = fingerprint host SSH server untuk verifikasi identitas server saat GitHub Actions konek.
- `APP_DIR` = folder root proyek Laravel di server FastPanel.
- `PHP_BIN` dan `COMPOSER_BIN` bisa dibiarkan default jika perintah `php` dan `composer` tersedia global.
- `COMPOSER_BIN` boleh berisi command lengkap. Contoh untuk Composer lokal di server:
  `php /var/www/dayakarya/data/www/dayakarya.id/composer`
- `RUN_MIGRATIONS=false` bila Anda ingin migrasi dijalankan manual.
- `RESTART_QUEUE=false` bila server tidak menjalankan queue worker persisten.
- `RUN_FILAMENT_ASSETS=true` direkomendasikan agar aset panel admin selalu sinkron setelah deploy.

Langkah setup SSH key:

1. Buat key khusus deploy di komputer lokal:
```bash
ssh-keygen -t ed25519 -C "github-actions-deploy"
```
2. Salin isi **public key** ke server:
```bash
mkdir -p ~/.ssh
chmod 700 ~/.ssh
nano ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```
3. Ambil fingerprint host SSH dari server:
```bash
ssh-keygen -lf /etc/ssh/ssh_host_ed25519_key.pub -E sha256
```
Contoh output:
```text
256 SHA256:AbCdEfGhIjKlMnOpQrStUvWxYz1234567890abcdef root@server (ED25519)
```
Salin bagian `SHA256:...` lalu simpan ke secret `SSH_HOST_FINGERPRINT`.
4. Tempel isi **private key** ke secret `SSH_PRIVATE_KEY` di GitHub.

Setelah secret terisi, workflow akan berjalan otomatis setiap ada push ke branch `main`.

---

## Langkah 12 — Update Aplikasi Manual (fallback)

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
git pull --ff-only origin main
php /var/www/USERNAME/data/www/dayakarya.id/composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan filament:assets
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Catatan penting:

- Simpan `composer.lock` ke repo agar versi dependency yang terpasang saat deploy tetap konsisten.
- Repo ini sekarang sudah menyertakan `config/permission.php` dan migration permission table, jadi fresh install tidak perlu lagi `vendor:publish` untuk Spatie Permission.
- Setelah Google OAuth diaktifkan, pastikan migration terbaru untuk kolom `users.google_id` dan `users.auth_provider` ikut dijalankan pada deploy.

---

## Set Callback Duitku

Pastikan URL callback di dashboard Duitku:
```
https://dayakarya.id/api/v1/payments/duitku/callback
```

## Set Redirect Google

Pastikan `Authorized redirect URI` pada Google Cloud Console:
```
https://dayakarya.id/auth/google/callback
```

Setelah deploy selesai, lakukan verifikasi production memakai panduan:

- `docs/dayakarya-production-env-template.md`
- `docs/dayakarya-go-live-checklist.md`
- `docs/fastpanel-google-oauth-runbook.md`
- `docs/google-oauth-production-checklist.md`

---

## Selesai

Buka `https://dayakarya.id` (aplikasi) dan `https://dayakarya.id/admin` (panel admin). Login admin, ganti password, mulai operasional.
