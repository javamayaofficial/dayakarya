<?php

use App\Models\AffiliateLink;
use App\Models\Payment;
use App\Models\Work;
use App\Http\Controllers\Web\GoogleAuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

$memberShellHeaders = [
    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0, private',
    'Pragma' => 'no-cache',
    'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
];

$creatorEditorVersion = file_exists(resource_path('views/creator/editor.blade.php'))
    ? filemtime(resource_path('views/creator/editor.blade.php'))
    : time();

/*
|--------------------------------------------------------------------------
| Web Routes — Frontend Pengguna (PWA, mobile-first)
|--------------------------------------------------------------------------
| Halaman ini adalah "shell" premium. Data interaktif diambil dari REST API
| (routes/api.php) sehingga struktur siap dipakai ulang oleh aplikasi mobile.
*/

Route::get('/', fn () => response()->view('reader.home', [], 200, $memberShellHeaders))->name('home');
Route::view('/explore', 'reader.explore')->name('explore');
Route::view('/leaderboard', 'reader.leaderboard')->name('leaderboard');
Route::get('/manifest.webmanifest', function () {
    $manifest = file_get_contents(public_path('manifest.json'));
    abort_unless($manifest !== false, 404);

    return response($manifest, 200, [
        'Content-Type' => 'application/manifest+json; charset=utf-8',
        'Cache-Control' => 'public, max-age=3600',
    ]);
})->name('pwa.manifest');
Route::get('/manifest.json', fn () => redirect()->route('pwa.manifest', status: 302));

Route::get('/_deploy/opcache-reset', function (Request $request) {
    abort_unless($request->hasValidSignature(), 403);

    return response()->json([
        'ok' => true,
        'opcache_reset' => function_exists('opcache_reset') ? opcache_reset() : false,
        'timestamp' => now()->toIso8601String(),
    ]);
})->name('deploy.opcache-reset');

Route::get('/karya/{work:slug}', function (Request $request, Work $work) {
    abort_unless($work->status === 'published', 404);

    $work->load([
        'creator',
        'chapters' => fn ($query) => $query->where('status', 'published')->orderBy('order'),
    ]);

    $selectedChapter = $work->chapters
        ->firstWhere('id', $request->integer('bagian'))
        ?? $work->chapters->first();

    return view('reader.work', [
        'work' => $work,
        'selectedChapter' => $selectedChapter,
    ]);
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
Route::get('/wallet', fn () => response()->view('reader.wallet', [], 200, $memberShellHeaders))->name('wallet');
Route::view('/wallet/topup/selesai', 'reader.topup-done')->name('wallet.topup.done');
Route::get('/wallet/topup/manual/{payment}', fn (Payment $payment) => view('reader.topup-manual', compact('payment')))
    ->name('wallet.topup.manual');

// Dashboard creator (shell)
Route::get('/creator', fn () => response()->view('creator.dashboard', [
    'editorVersion' => $creatorEditorVersion,
], 200, $memberShellHeaders))->name('creator.dashboard');
Route::get('/creator/works/{work}', fn () => response()->view('creator.editor', [
    'editorVersion' => $creatorEditorVersion,
], 200, $memberShellHeaders))->name('creator.work.edit');

// Halaman statis (CMS)
Route::view('/tentang', 'reader.page')->name('about');
Route::view('/kontak', 'reader.contact')->name('contact');
Route::view('/faq', 'reader.faq')->name('faq');
Route::view('/privacy', 'reader.privacy')->name('privacy');
Route::view('/terms', 'reader.terms')->name('terms');
Route::view('/refund-policy', 'reader.refund')->name('refund');
Route::view('/content-policy', 'reader.content-policy')->name('content.policy');
Route::view('/creator-agreement', 'reader.creator-agreement')->name('creator.agreement');
Route::view('/hapus-akun', 'reader.account-deletion')->name('account.deletion');
