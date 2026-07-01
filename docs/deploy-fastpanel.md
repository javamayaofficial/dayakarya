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
```

---

## Langkah 5 — Migrasi & Storage

```bash
php artisan migrate --seed
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

---

## Langkah 6 — Arahkan Document Root ke `/public`

1. Di FastPanel: **Sites → dayakarya.id → Settings**.
2. Ubah **Document Root** menjadi folder `.../dayakarya.id/public`.
3. Simpan.

> Ini wajib. Laravel hanya boleh diakses lewat folder `public`, bukan root proyek.

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
APP_DIR=/var/www/USERNAME/data/www/dayakarya.id
PHP_BIN=php
COMPOSER_BIN=composer
RUN_MIGRATIONS=true
RUN_STORAGE_LINK=true
RESTART_QUEUE=true
```

Keterangan:

- `SSH_PRIVATE_KEY` = private key yang pasangannya sudah ditaruh di `~/.ssh/authorized_keys` server.
- `APP_DIR` = folder root proyek Laravel di server FastPanel.
- `PHP_BIN` dan `COMPOSER_BIN` bisa dibiarkan default jika perintah `php` dan `composer` tersedia global.
- `RUN_MIGRATIONS=false` bila Anda ingin migrasi dijalankan manual.
- `RESTART_QUEUE=false` bila server tidak menjalankan queue worker persisten.

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
3. Tempel isi **private key** ke secret `SSH_PRIVATE_KEY` di GitHub.

Setelah secret terisi, workflow akan berjalan otomatis setiap ada push ke branch `main`.

---

## Langkah 12 — Update Aplikasi Manual (fallback)

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

---

## Set Callback Duitku

Pastikan URL callback di dashboard Duitku:
```
https://dayakarya.id/api/v1/payments/duitku/callback
```

---

## Selesai

Buka `https://dayakarya.id` (aplikasi) dan `https://dayakarya.id/admin` (panel admin). Login admin, ganti password, mulai operasional.
