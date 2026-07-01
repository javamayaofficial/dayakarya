<?php

use App\Models\AffiliateLink;
use App\Models\Payment;
use App\Models\Work;
use App\Http\Controllers\Web\GoogleAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Frontend Pengguna (PWA, mobile-first)
|--------------------------------------------------------------------------
| Halaman ini adalah "shell" premium. Data interaktif diambil dari REST API
| (routes/api.php) sehingga struktur siap dipakai ulang oleh aplikasi mobile.
*/

Route::view('/', 'reader.home')->name('home');
Route::view('/explore', 'reader.explore')->name('explore');
Route::view('/leaderboard', 'reader.leaderboard')->name('leaderboard');

Route::get('/karya/{work:slug}', function (Work $work) {
    abort_unless($work->status === 'published', 404);
    return view('reader.work', ['work' => $work->load('creator', 'chapters')]);
})->name('work.show');

// Redirect affiliate link + tracking klik
Route::get('/r/{code}', function (string $code) {
    $link = AffiliateLink::where('code', $code)->firstOrFail();
    $link->increment('clicks');
    return redirect()->route('work.show', $link->work)->withCookie(
        cookie('dk_ref', $code, 60 * 24 * 30) // atribusi 30 hari
    );
})->name('affiliate.redirect');

// Auth pages (shell; proses via API)
Route::view('/masuk', 'auth.login')->name('login');
Route::view('/daftar', 'auth.register')->name('register');
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// Wallet & top up
Route::view('/wallet', 'reader.wallet')->name('wallet');
Route::view('/wallet/topup/selesai', 'reader.topup-done')->name('wallet.topup.done');
Route::get('/wallet/topup/manual/{payment}', fn (Payment $payment) => view('reader.topup-manual', compact('payment')))
    ->name('wallet.topup.manual');

// Dashboard creator (shell)
Route::view('/creator', 'creator.dashboard')->name('creator.dashboard');

// Halaman statis (CMS)
Route::view('/tentang', 'reader.page')->name('about');
Route::view('/faq', 'reader.faq')->name('faq');
Route::view('/privacy', 'reader.privacy')->name('privacy');
Route::view('/terms', 'reader.terms')->name('terms');
Route::view('/hapus-akun', 'reader.account-deletion')->name('account.deletion');
