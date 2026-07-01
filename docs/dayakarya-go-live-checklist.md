# Checklist Final Go-Live Dayakarya

Checklist ini dipakai sebagai gerbang akhir sebelum Dayakarya dinyatakan siap tayang production. Fokusnya bukan hanya deploy, tetapi juga pengalaman user, integrasi, compliance, dan kesiapan operasional.

---

## 1. Deploy dan Infrastruktur

- [ ] Push terbaru sudah masuk ke branch `main`
- [ ] Workflow deploy FastPanel berjalan sukses tanpa error SSH atau Composer
- [ ] Commit terbaru benar-benar sudah ter-pull di server
- [ ] `php artisan migrate --force` sudah berjalan sukses
- [ ] `php artisan filament:assets` sudah berjalan sukses
- [ ] `php artisan optimize:clear`, `config:cache`, `route:cache`, dan `view:cache` sudah berhasil
- [ ] Queue worker atau cron queue aktif
- [ ] Cron `schedule:run` aktif
- [ ] HTTPS aktif dan `Force HTTPS` sudah menyala

---

## 2. Environment Production

- [ ] `.env` production sudah diisi lengkap
- [ ] Kredensial database valid
- [ ] `PAYMENT_PROVIDER` sudah sesuai mode production
- [ ] `WA_PROVIDER` sudah sesuai mode production
- [ ] `EMAIL_PROVIDER` sudah sesuai mode production
- [ ] `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, dan `GOOGLE_REDIRECT_URI` sudah terisi
- [ ] `APP_URL` sama dengan domain production aktif
- [ ] Tidak ada secret sensitif yang dipindahkan ke database

Dokumen pendukung:

- `docs/dayakarya-production-env-template.md`

---

## 3. Autentikasi dan Akun

- [ ] Login email/password berjalan normal
- [ ] Register akun baru berjalan normal
- [ ] Logout berjalan normal
- [ ] Login Google dari `/masuk` berhasil
- [ ] Register Google dari `/daftar` berhasil
- [ ] `dk_token` tersimpan setelah login
- [ ] User lama dengan email sama otomatis terhubung ke Google
- [ ] User baru dari Google otomatis punya role `reader`
- [ ] Halaman `/wallet` mengenali user login dengan benar

Dokumen pendukung:

- `docs/fastpanel-google-oauth-runbook.md`
- `docs/google-oauth-production-checklist.md`

---

## 4. Wallet, Credit, dan Top Up

- [ ] User guest melihat state wallet yang benar, bukan error
- [ ] User guest melihat state credit pill yang benar
- [ ] User login bisa membuka halaman `/wallet`
- [ ] Saldo credit dan rupiah tampil normal
- [ ] Riwayat transaksi tampil normal
- [ ] Tombol top up menyesuaikan provider aktif
- [ ] Top up Duitku bisa membuat transaksi
- [ ] Callback Duitku mengubah payment menjadi sukses
- [ ] Credit bertambah setelah payment sukses
- [ ] Mode `manual` atau `qris_manual` menampilkan instruksi yang benar
- [ ] Halaman top up manual menampilkan order ID, nominal, dan kontak support yang benar

---

## 5. Katalog dan Konsumsi Konten

- [ ] Halaman beranda terbuka normal
- [ ] Explore atau katalog karya tampil normal
- [ ] Detail karya terbuka normal
- [ ] Chapter gratis dapat diakses
- [ ] Unlock chapter premium berjalan normal
- [ ] Riwayat baca atau interaksi dasar tidak error

---

## 6. Dashboard Kreator

- [ ] Halaman `/creator` bisa dibuka oleh user yang sesuai
- [ ] Statistik karya, views, royalty, dan followers tampil dari data nyata
- [ ] Quick create draft karya berjalan normal
- [ ] Draft baru benar-benar tersimpan
- [ ] Tidak ada tombol mati yang masih mengarah ke `#`

---

## 7. Leaderboard

- [ ] Halaman `/leaderboard` dapat dibuka publik
- [ ] Top karya tampil normal
- [ ] Top kreator tampil normal
- [ ] Summary statistik tampil normal
- [ ] API leaderboard merespons tanpa error
- [ ] Data leaderboard tetap masuk akal setelah cache aktif

---

## 8. Integrasi Operasional

- [ ] Halaman admin integrasi dapat dibuka
- [ ] Provider payment dapat dibaca dari helper pengaturan
- [ ] Provider WhatsApp dapat dibaca dari helper pengaturan
- [ ] Provider email dapat dibaca dari helper pengaturan
- [ ] Fonnte dapat mengirim notifikasi dasar
- [ ] Mailketing dapat mengirim email dasar
- [ ] Support WhatsApp dan email yang tampil ke user sudah benar
- [ ] Rekening manual atau QRIS sudah benar

---

## 9. Legal dan Compliance

- [ ] Halaman `/privacy` tersedia
- [ ] Halaman `/terms` tersedia
- [ ] Halaman `/hapus-akun` tersedia
- [ ] Footer atau navigasi memiliki akses ke halaman legal
- [ ] Isi kontak support pada halaman legal sudah benar

---

## 10. PWA dan Mobile Readiness

- [ ] `manifest.json` dapat diakses
- [ ] `sw.js` dapat diakses
- [ ] `offline.html` dapat diakses langsung tanpa Laravel routing
- [ ] Ikon PWA tampil normal
- [ ] Install prompt atau Add to Home Screen berjalan di perangkat yang mendukung
- [ ] Halaman tetap punya fallback offline dasar

---

## 11. Admin dan Moderasi

- [ ] Login admin Filament normal
- [ ] Halaman pengguna, karya, dan withdrawal terbuka normal
- [ ] Admin dapat melihat payment dan user yang baru dibuat
- [ ] Approval alur operasional inti tidak error

---

## 12. Monitoring dan Log

- [ ] `storage/logs/laravel.log` tidak menunjukkan error baru yang kritis
- [ ] Tidak ada error 500 pada route utama
- [ ] Tidak ada route publik penting yang mengarah ke 404 tidak sengaja
- [ ] Tidak ada mismatch antara login admin dan auth frontend yang menyesatkan user

---

## 13. Kriteria Lulus Go-Live

Dayakarya bisa dianggap siap tayang jika seluruh blok berikut lulus:

- [ ] Deploy sukses
- [ ] Auth sukses
- [ ] Wallet dan top up sukses
- [ ] Creator dashboard sukses
- [ ] Leaderboard sukses
- [ ] Integrasi sukses
- [ ] Legal pages sukses
- [ ] PWA baseline sukses
- [ ] Log produksi bersih dari error kritis

Jika ada satu blok inti gagal, tunda go-live dan perbaiki dulu sebelum promosi traffic.
