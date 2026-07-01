# Template `.env` Production Dayakarya

Template ini dibuat untuk setup cepat server production Dayakarya di FastPanel. Gunakan sebagai acuan saat mengisi file `.env` di server.

> Jangan commit file `.env` production ke repository. Isi nilai rahasia langsung di server.

---

## Cara Pakai

1. Login ke server FastPanel.
2. Buka root aplikasi:

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
nano .env
```

3. Salin blok di bawah.
4. Ganti semua nilai placeholder sesuai data production.
5. Simpan, lalu jalankan:

```bash
php artisan optimize:clear
php artisan config:cache
```

---

## Template Final

```env
APP_NAME=Dayakarya
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://dayakarya.id
APP_LOCALE=id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=isi_nama_database
DB_USERNAME=isi_user_database
DB_PASSWORD=isi_password_database

SESSION_DRIVER=database
SESSION_LIFETIME=120
QUEUE_CONNECTION=database
CACHE_STORE=database

FILESYSTEM_DISK=public

PAYMENT_PROVIDER=duitku
DUITKU_MERCHANT_CODE=isi_merchant_code_duitku
DUITKU_API_KEY=isi_api_key_duitku
DUITKU_ENV=production
DUITKU_CALLBACK_URL="${APP_URL}/api/v1/payments/duitku/callback"
DUITKU_RETURN_URL="${APP_URL}/wallet/topup/selesai"

MANUAL_BANK_NAME="BCA"
MANUAL_BANK_ACCOUNT="isi_nomor_rekening_manual"
MANUAL_BANK_HOLDER="Yayasan Pondok Daya Cipta Nusantara"
MANUAL_QRIS_IMAGE_URL=
SUPPORT_WHATSAPP_NUMBER="628xxxxxxxxxx"
SUPPORT_EMAIL="admin@dayakarya.id"

WA_PROVIDER=fonnte
FONNTE_TOKEN=isi_token_fonnte
FONNTE_URL=https://api.fonnte.com/send

EMAIL_PROVIDER=mailketing
MAILKETING_API_TOKEN=isi_api_token_mailketing
MAILKETING_FROM_EMAIL=noreply@dayakarya.id
MAILKETING_FROM_NAME=Dayakarya
MAILKETING_URL=https://api.mailketing.co.id/api/v1/send

GOOGLE_CLIENT_ID=isi_google_client_id
GOOGLE_CLIENT_SECRET=isi_google_client_secret
GOOGLE_REDIRECT_URI=https://dayakarya.id/auth/google/callback

CREDIT_RATE_RUPIAH=100
ROYALTY_CREATOR_PERCENT=70
AFFILIATE_COMMISSION_PERCENT=10
WITHDRAW_MINIMUM=50000
WITHDRAW_FEE=2500

LOG_CHANNEL=stack
LOG_LEVEL=error
```

---

## Nilai yang Wajib Dicek Ulang

- `APP_URL` harus sama dengan domain production aktif
- `DB_*` harus cocok dengan database FastPanel
- `DUITKU_ENV` harus `production` jika sudah live
- `SUPPORT_WHATSAPP_NUMBER` harus format internasional tanpa tanda `+`
- `GOOGLE_REDIRECT_URI` harus sama persis dengan Google Cloud Console

---

## Setelah Menyimpan `.env`

Jalankan urutan ini:

```bash
cd /var/www/USERNAME/data/www/dayakarya.id
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

Jika dependency belum yakin sinkron:

```bash
php /var/www/USERNAME/data/www/dayakarya.id/composer install --no-dev --optimize-autoloader
php artisan filament:assets
```

---

## Dokumen Lanjutan

- `docs/fastpanel-google-oauth-runbook.md`
- `docs/google-oauth-production-checklist.md`
- `docs/deploy-fastpanel.md`
