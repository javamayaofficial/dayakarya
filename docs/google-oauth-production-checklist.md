# Checklist Production Google OAuth

Panduan ini dipakai setelah fitur `Google Sign-In` sudah masuk ke branch `main` dan server FastPanel sudah terhubung ke auto deploy.

---

## 1. Siapkan Google Cloud Console

Pastikan Anda sudah membuat `OAuth Client ID` tipe `Web application`.

Isi parameter berikut:

- **Authorized JavaScript origins**
  - `https://dayakarya.id`
- **Authorized redirect URIs**
  - `https://dayakarya.id/auth/google/callback`

Jika domain utama berbeda, samakan semua nilai dengan domain production yang benar-benar dipakai user.

---

## 2. Isi Environment di Server

Edit file `.env` di server FastPanel:

```env
APP_URL=https://dayakarya.id
GOOGLE_CLIENT_ID=isi_dari_google_cloud
GOOGLE_CLIENT_SECRET=isi_dari_google_cloud
GOOGLE_REDIRECT_URI=https://dayakarya.id/auth/google/callback
```

Lalu jalankan:

```bash
php artisan optimize:clear
php artisan config:cache
```

Jika deploy memakai workflow GitHub Actions, pastikan server sudah menarik commit terbaru sebelum cache dibangun ulang.

---

## 3. Verifikasi Dependency dan Migrasi

Pastikan deploy terakhir menjalankan:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
```

Target yang harus berhasil:

- kredensial Google OAuth di `.env` sudah terisi lengkap
- migration `add_google_auth_columns_to_users_table` sudah jalan
- kolom `users.google_id` dan `users.auth_provider` tersedia

Jika ragu, cek dari server:

```bash
php artisan migrate:status
```

---

## 4. Smoke Test Login Google

Lakukan uji ini dari browser biasa atau mode incognito:

1. Buka `https://dayakarya.id/masuk`
2. Klik tombol `Masuk dengan Google`
3. Pilih akun Google
4. Pastikan browser kembali ke `dayakarya.id`
5. Setelah redirect selesai, buka DevTools -> Application -> Local Storage
6. Pastikan key `dk_token` ada
7. Refresh halaman lalu buka `/wallet`
8. Pastikan wallet tidak lagi tampil sebagai guest state

Hasil yang diharapkan:

- user baru otomatis dibuat jika email belum ada
- user lama dengan email yang sama otomatis terhubung ke `google_id`
- role default `reader` terpasang jika user baru belum punya role
- notifikasi sukses muncul setelah redirect

---

## 5. Smoke Test Register Google

1. Buka `https://dayakarya.id/daftar`
2. Klik tombol `Daftar dengan Google`
3. Selesaikan consent screen
4. Pastikan hasil akhirnya sama seperti alur login:
   - kembali ke aplikasi
   - `dk_token` tersimpan
   - user langsung dianggap login

---

## 6. Cek Database User

Jika akses database tersedia, verifikasi user hasil login Google memiliki data berikut:

- `email` terisi
- `google_id` terisi
- `auth_provider=google`
- `email_verified_at` terisi
- `status=active`

Jika user lama sudah ada, data minimal yang harus ter-update adalah:

- `google_id`
- `auth_provider`
- `email_verified_at` jika sebelumnya kosong

---

## 7. Gejala Gagal yang Paling Umum

**Redirect URI mismatch**

- Penyebab: `GOOGLE_REDIRECT_URI`, `APP_URL`, dan redirect URI di Google Cloud tidak identik.
- Solusi: samakan semua ke `https://dayakarya.id/auth/google/callback`.

**Kembali ke halaman login tanpa masuk**

- Penyebab: callback gagal membuat token atau script callback tidak jalan.
- Solusi: buka console browser, cek error JavaScript pada halaman callback, lalu cek log Laravel.

**Tombol Google muncul, tetapi error 500 saat callback**

- Penyebab: kredensial Google belum lengkap, callback URI tidak cocok, atau server belum memuat config terbaru.
- Solusi: jalankan deploy ulang, lalu `php artisan optimize:clear` dan `php artisan config:cache`.

**User berhasil dibuat, tetapi wallet masih guest**

- Penyebab: `dk_token` tidak tersimpan di `localStorage`.
- Solusi: cek script callback, izin browser, dan pastikan redirect tidak dipotong oleh ekstensi/privacy mode tertentu.

---

## 8. Checklist Lulus

- [ ] Tombol Google tampil di `/masuk`
- [ ] Tombol Google tampil di `/daftar`
- [ ] Redirect ke Google berjalan normal
- [ ] Callback kembali ke domain production yang benar
- [ ] `dk_token` tersimpan di `localStorage`
- [ ] User baru otomatis bisa login
- [ ] User lama otomatis terhubung berdasarkan email
- [ ] Wallet terbaca sebagai user login, bukan guest
- [ ] Tidak ada error baru di `storage/logs/laravel.log`

Jika semua poin di atas lulus, maka Google OAuth production bisa dianggap aktif.
