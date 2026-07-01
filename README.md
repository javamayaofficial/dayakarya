# Dayakarya

**Platform SaaS Creator Economy Indonesia** — dikembangkan oleh Yayasan Pondok Daya Cipta Nusantara.

> Ubah karyamu jadi penghasilan. Ubah dukungan jadi dampak.

Dayakarya mempertemukan **kreator** (penulis, pendongeng, podcaster, guru, voice talent), **penikmat konten** (pembaca & pendengar), dan **mitra** (affiliate, sponsor, program CSR) dalam satu ekosistem. Kreator mengunggah karya, menawarkan sebagian gratis & sebagian premium yang dibuka dengan **Credit**. Setiap pembelian menghasilkan **royalti otomatis** untuk kreator dan **komisi** untuk affiliate.

---

## Fitur Utama

- **Autentikasi & Role** — Login/Register/Lupa Password, role: Admin, Operator, Creator, Reader, Listener, Affiliate, Sponsor, CSR.
- **Google Sign-In** — Login dan register cepat dengan akun Google, tetap bridge ke token frontend `dk_token`.
- **Karya Multi-Format** — Cerpen, Novel Berseri, Podcast, Audio Story, Dongeng, Motivasi, Audiobook. Chapter/episode gratis atau premium, draft/jadwal/publish.
- **Ekonomi** — Wallet (Credit & Rupiah), Top Up via **Duitku**, unlock premium, **royalti otomatis**, komisi affiliate, withdraw ke bank/e-wallet.
- **Affiliate & Referral** — link unik per karya, tracking klik & konversi, komisi otomatis.
- **Sosial** — follow, like, komentar, bookmark, riwayat baca/dengar, explore, trending, pencarian.
- **Mitra CSR/Sponsor** — program, banner, **Laporan Dampak Sosial** (pembeda utama).
- **Admin Panel (Filament)** — moderasi karya, approval withdraw, manajemen pengguna/keuangan/sponsor, CMS, FAQ, pengaturan.
- **REST API + PWA** — API untuk web & mobile client, aplikasi installable & mobile-first.

---

## Teknologi

| Lapisan | Pilihan |
|---|---|
| Backend | **Laravel 11 (PHP 8.2+)** |
| Database | **MySQL / MariaDB** |
| Admin Panel | **Filament 3** |
| API Auth | **Laravel Sanctum** (token) |
| Role/Permission | **Spatie Laravel Permission** |
| Frontend | Blade + CSS mandiri (tanpa build tool) + PWA |
| Payment | **Duitku** (utama) · Midtrans/Xendit (alt) · Transfer/QRIS Manual (fallback) |
| WhatsApp | **Fonnte** (utama) · OneSender/StarSender (alt) |
| Email | **Mailketing** (utama) · kirim.email (alt) |

---

## Struktur Proyek

```
dayakarya/
├── app/
│   ├── Models/               # Entitas: User, Work, Chapter, Wallet, Payment, dll.
│   ├── Services/             # Service layer (bisnis logic + integrasi)
│   │   ├── Payment/          # PaymentGateway (interface), DuitkuService, ManualPaymentService, PaymentManager
│   │   ├── WhatsApp/         # WhatsAppSender (interface), FonnteService, WhatsAppManager
│   │   ├── Email/            # EmailSender (interface), MailketingService, EmailManager
│   │   ├── WalletService.php # Inti alur uang: topup, unlock, royalti, komisi, withdraw
│   │   └── NotificationService.php
│   ├── Http/Controllers/Api/ # Endpoint REST (auth, wallet, unlock, withdraw, work, affiliate, callback)
│   ├── Filament/Resources/   # Admin: WorkResource, WithdrawalResource, UserResource
│   └── Providers/Filament/   # AdminPanelProvider
├── config/                   # dayakarya.php, duitku.php, fonnte.php, mailketing.php, sanctum, auth
├── routes/                   # api.php, web.php, console.php
├── resources/views/          # Frontend PWA (reader, auth, creator, layout)
├── public/                   # index.php, css/app.css, js/app.js, manifest.json, sw.js
├── database/migrations/      # 13 migrasi inti
├── database/seeders/         # Role, Admin, Category
├── docs/                     # Panduan instalasi & deploy (TERPISAH)
└── README.md
```

---

## Setup Lokal (ringkas)

```bash
git clone https://github.com/javamayaofficial/dayakarya.git dayakarya && cd dayakarya
composer install
cp .env.example .env
php artisan key:generate
# isi DB & kredensial di .env
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Login admin default: **admin@dayakarya.id** / **password** (wajib ganti).
Panel admin: `/admin` · Aplikasi pengguna: `/`

Detail lengkap ada di **[docs/installasi.md](docs/installasi.md)**.

---

## Konfigurasi Integrasi (via `.env`)

Prinsip: **satu provider utama per kategori**, ditentukan di `config/dayakarya.php` → dibaca dari `.env`. Ganti provider = cukup ubah `.env`, tanpa sentuh kode (berkat service layer + manager factory).

### Payment — Duitku (utama)
```env
PAYMENT_PROVIDER=duitku
DUITKU_MERCHANT_CODE=xxxx
DUITKU_API_KEY=xxxx
DUITKU_ENV=sandbox        # sandbox | production
```
- **Trigger:** `POST /api/v1/topup` membuat transaksi → user diarahkan ke halaman Duitku.
- **Callback:** Duitku memanggil `POST /api/v1/payments/duitku/callback` → signature diverifikasi → Credit ditambahkan (idempotent) → notifikasi WhatsApp.
- **Fallback:** set `PAYMENT_PROVIDER=manual` untuk Transfer/QRIS Manual (dikonfirmasi admin di panel).

### WhatsApp — Fonnte (utama)
```env
WA_PROVIDER=fonnte
FONNTE_TOKEN=xxxx
```
- **Trigger:** verifikasi register, top up berhasil, royalti masuk, status withdraw.

### Email — Mailketing (utama)
```env
EMAIL_PROVIDER=mailketing
MAILKETING_API_TOKEN=xxxx
MAILKETING_FROM_EMAIL=noreply@dayakarya.id
```

### Google OAuth (opsional)
```env
GOOGLE_CLIENT_ID=xxxx
GOOGLE_CLIENT_SECRET=xxxx
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```
- **Redirect URI production:** `https://dayakarya.id/auth/google/callback`
- **Alur:** klik tombol Google di `/masuk` atau `/daftar` -> callback Laravel -> token Sanctum disimpan ke `localStorage` sebagai `dk_token`.

### Ekonomi platform
```env
CREDIT_RATE_RUPIAH=100
ROYALTY_CREATOR_PERCENT=70
AFFILIATE_COMMISSION_PERCENT=10
WITHDRAW_MINIMUM=50000
WITHDRAW_FEE=2500
```

---

## Endpoint API Inti

| Method | Endpoint | Fungsi |
|---|---|---|
| POST | `/api/v1/auth/register` | Daftar + pilih role, kirim salam WA |
| POST | `/api/v1/auth/login` | Login, kembalikan token Sanctum |
| GET | `/api/v1/auth/me` | Profil + saldo wallet |
| GET | `/api/v1/works` | Katalog (filter, trending, search) |
| GET | `/api/v1/works/{work}` | Detail karya + daftar chapter |
| POST | `/api/v1/topup` | Buat transaksi top up (Duitku) |
| POST | `/api/v1/payments/duitku/callback` | Webhook Duitku (verifikasi signature) |
| POST | `/api/v1/chapters/{chapter}/unlock` | Buka premium + royalti & komisi otomatis |
| GET | `/api/v1/wallet` · `/wallet/transactions` | Saldo & riwayat |
| POST | `/api/v1/withdrawals` | Ajukan penarikan |
| POST | `/api/v1/works/{work}/affiliate-link` | Buat link affiliate |

**Alur auth:** frontend/mobile kirim kredensial → dapat **token Sanctum** → sertakan `Authorization: Bearer <token>` di setiap request terproteksi. Otorisasi memakai role (Spatie).

---

## Deploy

Panduan **dipisah** agar tidak tercampur:

- **[docs/deploy-fastpanel.md](docs/deploy-fastpanel.md)** — GitHub → FastPanel (prioritas).
- **[docs/deploy-cpanel.md](docs/deploy-cpanel.md)** — cPanel (shared hosting).
- **[docs/deploy-vps-manual.md](docs/deploy-vps-manual.md)** — VPS manual via SSH.
- **[docs/google-oauth-production-checklist.md](docs/google-oauth-production-checklist.md)** — checklist aktivasi dan smoke test Google login di production.

---

## Arah Mobile (Android & iOS)

- **Fase 1 — PWA:** aplikasi ini sudah installable (manifest + service worker). Pengguna bisa "Add to Home Screen".
- **Fase 2 — Native:** bangun aplikasi **Flutter / React Native** sebagai *client terpisah* yang mengonsumsi REST API + token Sanctum. Backend tetap di FastPanel; tidak ada duplikasi logika bisnis.
- **Pembagian tanggung jawab:** backend = sumber kebenaran (data, transaksi, royalti); mobile = presentasi & interaksi.

Siapkan endpoint yang sama, tambahkan `SANCTUM_STATEFUL_DOMAINS` bila perlu, lalu submit ke Play Store & App Store setelah QA.

---

## Lisensi

Proprietary © Yayasan Pondok Daya Cipta Nusantara.
