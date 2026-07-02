## [OPEN] Draft Save Error

### Session
- session_id: `draft-save-error`
- started_at: `2026-07-02`
- scope: editor draft member gagal saat klik `Simpan Perubahan` di production

### Symptoms
- User melihat `Server Error` saat menyimpan perubahan draft karya.
- Masalah tetap muncul di server production walau jalur save sudah diubah sebelumnya.

### Hypotheses
1. Route save production tidak benar-benar mengarah ke endpoint yang diharapkan.
2. Error terjadi di backend saat `Work` atau `Chapter` di-update.
3. Backend melempar HTML/500 non-JSON sehingga frontend hanya membaca sebagai `Server Error`.
4. Ada field tertentu pada payload yang lolos dari UI tapi gagal di validasi/DB server.
5. Ada perbedaan environment production yang membuat save gagal hanya di server.

### Plan
1. Tambahkan instrumentasi log minimal pada jalur save frontend dan backend.
2. Reproduce di production lalu kumpulkan bukti error.
3. Putuskan akar masalah dari log nyata.
4. Terapkan fix minimal.
5. Verifikasi pre-fix vs post-fix.
