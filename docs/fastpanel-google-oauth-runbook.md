# Runbook FastPanel Google OAuth

Runbook ini dipakai untuk aktivasi cepat `Google Sign-In` di production Dayakarya tanpa harus membuka banyak panduan sekaligus.

---

## Tujuan

Hasil akhir yang harus tercapai:

- tombol Google tampil di `/masuk` dan `/daftar`
- login Google berhasil kembali ke `dayakarya.id`
- token frontend `dk_token` tersimpan
- halaman `/wallet` mengenali user sebagai login, bukan guest

---

## 1. Isi Google Cloud Console

Pastikan OAuth Client Google sudah dibuat dengan nilai berikut:

```text
Authorized JavaScript origins:
https://dayakarya.id

Authorized redirect URIs:
https://dayakarya.id/auth/google/callback
```

Jika domain production berubah, samakan semua nilai dengan domain yang benar-benar dipakai.

---

## 2. Edit `.env` di Server

Masuk ke server FastPanel lalu buka root aplikasi:

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
nano .env
```

Isi atau pastikan nilai berikut benar:

```env
APP_URL=https://dayakarya.id
GOOGLE_CLIENT_ID=isi_client_id_google
GOOGLE_CLIENT_SECRET=isi_client_secret_google
GOOGLE_REDIRECT_URI=https://dayakarya.id/auth/google/callback
```

Simpan file setelah selesai.

---

## 3. Jalankan Refresh Konfigurasi

Jika Anda sudah login SSH ke server dan proyek sudah ada di folder aplikasi, jalankan:

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
php artisan optimize:clear
php artisan config:cache
```

Jika `php` tidak global, gunakan path binary PHP yang dipakai server.

---

## 4. Pastikan Deploy Terbaru Sudah Masuk

Jika auto deploy GitHub Actions sudah sukses, cukup lanjut ke langkah berikutnya.

Jika belum yakin, jalankan manual fallback:

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

Target minimum pada langkah ini:

- commit terbaru sudah masuk ke server
- `laravel/socialite` ikut terpasang
- migration Google auth sudah jalan

---

## 5. Cek Migrasi

Verifikasi migration terbaru:

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
php artisan migrate:status
```

Pastikan migration `2026_07_01_210000_add_google_auth_columns_to_users_table` berstatus `Ran`.

---

## 6. Uji Login dari Browser

Gunakan mode incognito agar tidak tercampur session lama:

1. Buka `https://dayakarya.id/masuk`
2. Klik `Masuk dengan Google`
3. Pilih akun Google
4. Tunggu redirect kembali ke Dayakarya
5. Buka DevTools -> Application -> Local Storage
6. Pastikan ada key `dk_token`
7. Buka `https://dayakarya.id/wallet`
8. Pastikan wallet tampil sebagai user login

Jika ingin uji pendaftaran juga:

1. Buka `https://dayakarya.id/daftar`
2. Klik `Daftar dengan Google`
3. Pastikan hasil akhirnya sama: redirect sukses dan `dk_token` tersimpan

---

## 7. Cek Database User

Jika punya akses database, pastikan user hasil login Google memiliki data:

- `google_id` terisi
- `auth_provider=google`
- `email_verified_at` terisi
- `status=active`

Jika akun lama dipakai, minimal field `google_id` dan `auth_provider` harus ter-update.

---

## 8. Jika Gagal

**Redirect URI mismatch**

- Samakan `APP_URL`
- Samakan `GOOGLE_REDIRECT_URI`
- Samakan redirect URI di Google Cloud Console

**Error 500 saat callback**

- Jalankan ulang `composer install`
- Jalankan `php artisan optimize:clear`
- Jalankan `php artisan config:cache`
- Cek `storage/logs/laravel.log`

**Login sukses tetapi wallet masih guest**

- Cek apakah `dk_token` tersimpan di `localStorage`
- Cek console browser pada halaman callback
- Pastikan redirect script tidak diblokir extension/privacy mode

---

## 9. Selesai

Aktivasi production Google OAuth bisa dianggap selesai jika semua poin berikut lulus:

- [ ] tombol Google muncul di `/masuk`
- [ ] tombol Google muncul di `/daftar`
- [ ] callback kembali ke domain yang benar
- [ ] `dk_token` tersimpan
- [ ] wallet mengenali user login
- [ ] tidak ada error baru di log Laravel

Untuk validasi yang lebih detail, lanjutkan ke:

- `docs/google-oauth-production-checklist.md`
