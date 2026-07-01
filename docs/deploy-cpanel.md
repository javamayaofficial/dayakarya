# Deploy Dayakarya ke cPanel

Panduan khusus **cPanel** (shared hosting). Ada dua jalur: lewat Git (disarankan) atau upload manual.

---

## Prasyarat

- Hosting cPanel dengan **PHP 8.2+** (cek di *MultiPHP Manager*).
- Akses **Terminal** cPanel (kalau tersedia) atau **File Manager**.
- **MySQL Databases** aktif.

> Beberapa shared hosting mematikan fungsi tertentu. Pastikan `proc_open`, `exec` tidak diblokir bila ingin memakai Terminal & Composer.

---

## Langkah 1 — Siapkan Database

1. cPanel → **MySQL Databases**.
2. Buat database baru (mis. `akun_dayakarya`).
3. Buat user database + password, lalu **Add User to Database** dengan **ALL PRIVILEGES**.
4. Catat nama db, user, password.

---

## Langkah 2 — Ambil Kode

### Jalur A — Git Version Control (disarankan)
1. cPanel → **Git Version Control → Create**.
2. Clone URL repo, tentukan folder (mis. `repositories/dayakarya`).
3. Buka **Terminal** cPanel:
```bash
cd ~/repositories/dayakarya
composer install --no-dev --optimize-autoloader
```

### Jalur B — Upload Manual (bila tanpa Git/Composer)
1. Di komputer lokal: jalankan `composer install --no-dev` lalu **zip seluruh proyek termasuk folder `vendor/`**.
2. Upload zip via **File Manager**, extract ke `~/dayakarya`.

---

## Langkah 3 — Atur Domain ke Folder `public`

Ada 2 opsi umum:

**Opsi 1 — Domain utama:**
Pindahkan isi folder `public/` ke `public_html/`, lalu edit `public_html/index.php` bagian path:
```php
require __DIR__.'/../dayakarya/vendor/autoload.php';
$app = require_once __DIR__.'/../dayakarya/bootstrap/app.php';
```
(sesuaikan `dayakarya` dengan lokasi folder proyek Anda di luar `public_html`).

**Opsi 2 — Addon/Subdomain (lebih rapi):**
Saat membuat domain/subdomain, set **Document Root** langsung ke `.../dayakarya/public`.

---

## Langkah 4 — Konfigurasi `.env`

Lewat File Manager, salin `.env.example` menjadi `.env`, lalu edit:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domainanda.com

DB_DATABASE=akun_dayakarya
DB_USERNAME=akun_userdb
DB_PASSWORD=passworddb

PAYMENT_PROVIDER=duitku
DUITKU_MERCHANT_CODE=...
DUITKU_API_KEY=...
DUITKU_ENV=production

WA_PROVIDER=fonnte
FONNTE_TOKEN=...

EMAIL_PROVIDER=mailketing
MAILKETING_API_TOKEN=...
```

Generate app key via Terminal:
```bash
php artisan key:generate
```
Bila tidak ada Terminal, generate key di lokal lalu tempel nilainya ke `APP_KEY=`.

---

## Langkah 5 — Migrasi

Lewat Terminal cPanel:
```bash
cd ~/dayakarya
php artisan migrate --seed
php artisan storage:link
```

Bila Terminal tidak tersedia, buat file sementara `migrate.php` di public untuk memanggil Artisan, jalankan sekali, lalu **hapus** (kurang ideal — utamakan hosting ber-Terminal).

---

## Langkah 6 — Perizinan Folder

Via File Manager, set permission `775` (atau `755`) pada:
- `storage/`
- `bootstrap/cache/`

---

## Langkah 7 — Cron Job

cPanel → **Cron Jobs**, tambah (setiap menit):
```
* * * * * cd ~/dayakarya && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1
```
(sesuaikan path PHP; cek di *MultiPHP* atau tanya support hosting)

---

## Langkah 8 — SSL

cPanel → **SSL/TLS Status** → jalankan **AutoSSL** (Let's Encrypt).

---

## Update Aplikasi

- **Jalur Git:** cPanel → Git Version Control → **Pull**, lalu Terminal: `composer install --no-dev && php artisan migrate --force`.
- **Jalur Manual:** upload ulang file yang berubah.

---

## Catatan Penting cPanel

- Queue worker sulit "selalu hidup" di shared hosting → jalankan lewat cron `queue:work --stop-when-empty`.
- Untuk file audio besar, cek batas `upload_max_filesize` & `post_max_size` di **MultiPHP INI Editor**.
- Set callback Duitku ke: `https://domainanda.com/api/v1/payments/duitku/callback`.
