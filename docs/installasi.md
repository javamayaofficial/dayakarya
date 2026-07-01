# Panduan Instalasi Dayakarya

Panduan ini ditulis agar bisa diikuti bahkan oleh yang belum terbiasa dengan server. Ikuti urut dari atas.

---

## 1. Kebutuhan Server (Umum)

| Kebutuhan | Minimum |
|---|---|
| PHP | **8.2** atau lebih baru |
| Database | MySQL 8 / MariaDB 10.4+ |
| Composer | Terpasang |
| Node.js | Opsional (aset sudah siap tanpa build) |
| Ekstensi PHP | `bcmath, ctype, curl, fileinfo, json, mbstring, openssl, pdo, pdo_mysql, tokenizer, xml, gd, zip, intl` |
| Akses | Cron (untuk penjadwalan) & idealnya queue worker |

Cara cek versi PHP: `php -v`. Cara cek ekstensi: `php -m`.

---

## 2. Ambil Kode & Dependensi

```bash
git clone https://github.com/javamayaofficial/dayakarya.git dayakarya
cd dayakarya
composer install --no-dev --optimize-autoloader
```

> `composer install` mengunduh folder `vendor/` (framework Laravel, Filament, dll). Wajib dijalankan di server yang punya akses internet.

---

## 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Buka file `.env`, lalu isi bagian **DATABASE**:

```env
DB_DATABASE=dayakarya
DB_USERNAME=nama_user_db
DB_PASSWORD=password_db
```

Isi juga kredensial integrasi (lihat bagian 6).

---

## 4. Buat Struktur Database

```bash
php artisan migrate --seed
```

Perintah ini akan:
- Membuat semua tabel (users, works, wallets, payments, dll).
- Mengisi **role** (admin, creator, reader, dst).
- Membuat **admin default**: `admin@dayakarya.id` / `password`.
- Mengisi **kategori** awal.

> **Penting:** login ke `/admin`, lalu segera ganti password admin.

---

## 5. Storage & Perizinan

```bash
php artisan storage:link
```

Pastikan folder berikut bisa ditulis server (permission 775):
```bash
chmod -R 775 storage bootstrap/cache
```

---

## 6. Konfigurasi Integrasi

Di `.env`, isi sesuai layanan yang Anda pakai:

**Payment (Duitku):**
```env
PAYMENT_PROVIDER=duitku
DUITKU_MERCHANT_CODE=DXXXX
DUITKU_API_KEY=xxxxxxxx
DUITKU_ENV=sandbox
```
Saat siap produksi, ganti `DUITKU_ENV=production`.

**WhatsApp (Fonnte):**
```env
WA_PROVIDER=fonnte
FONNTE_TOKEN=xxxxxxxx
```

**Email (Mailketing):**
```env
EMAIL_PROVIDER=mailketing
MAILKETING_API_TOKEN=xxxxxxxx
MAILKETING_FROM_EMAIL=noreply@dayakarya.id
```

**Google Login (opsional, direkomendasikan):**
```env
GOOGLE_CLIENT_ID=xxxxxxxx
GOOGLE_CLIENT_SECRET=xxxxxxxx
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```
Di Google Cloud Console, buat `OAuth Client ID` tipe `Web application`, lalu isi `Authorized redirect URI` sesuai domain aplikasi, misalnya:
```text
https://dayakarya.id/auth/google/callback
```

**Fallback pembayaran (opsional):** untuk menonaktifkan gateway online dan pakai transfer manual, set `PAYMENT_PROVIDER=manual` dan isi `MANUAL_BANK_*`.

---

## 7. Jalankan Pertama Kali

**Di lokal (uji coba):**
```bash
php artisan serve
```
Buka `http://localhost:8000`.

**Di hosting:** arahkan document root ke folder **`/public`** (langkah spesifik ada di panduan deploy masing-masing).

---

## 8. Cron & Queue (agar fitur otomatis jalan)

Tambahkan cron (menjalankan penjadwalan, mis. publish terjadwal):
```
* * * * * cd /path/ke/dayakarya && php artisan schedule:run >> /dev/null 2>&1
```

Untuk notifikasi berjalan lancar (queue):
```bash
php artisan queue:work
```
Di VPS gunakan Supervisor (lihat panduan VPS).

---

## 9. Optimasi Produksi

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Jika mengubah `.env` setelah ini, jalankan `php artisan config:clear`.

---

## Checklist Selesai

- [ ] `php -v` menunjukkan 8.2+
- [ ] `composer install` sukses (folder `vendor/` ada)
- [ ] `.env` terisi database & integrasi
- [ ] Google OAuth terisi bila fitur login Google ingin dipakai
- [ ] `php artisan migrate --seed` sukses
- [ ] Password admin sudah diganti
- [ ] Document root mengarah ke `/public`
- [ ] Cron `schedule:run` aktif
- [ ] Uji: buka aplikasi, register, top up sandbox, unlock chapter

Lanjutkan ke panduan deploy sesuai hosting Anda.
