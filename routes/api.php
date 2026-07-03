<?php

use App\Http\Controllers\Api\AffiliateController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\PaymentCallbackController;
use App\Http\Controllers\Api\UnlockController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\WithdrawalController;
use App\Http\Controllers\Api\WorkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| REST API v1 — dipakai oleh frontend PWA & aplikasi mobile (client sama)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ---- Publik ----
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);

    Route::get('works', [WorkController::class, 'index']);
    Route::get('works/{work}', [WorkController::class, 'show']);

    // Callback pembayaran (dipanggil server Duitku)
    Route::post('payments/duitku/callback', [PaymentCallbackController::class, 'duitku']);

    // ---- Perlu login (Sanctum token) ----
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('leaderboard', [LeaderboardController::class, 'index']);

        // Wallet & Credit
        Route::get('wallet', [WalletController::class, 'show']);
        Route::get('wallet/transactions', [WalletController::class, 'transactions']);
        Route::get('wallet/payment-methods', [WalletController::class, 'paymentMethods']);
        Route::post('topup', [WalletController::class, 'topup']);
        Route::post('payments/{payment}/proof', [WalletController::class, 'uploadProof']);

        // Unlock premium
        Route::post('chapters/{chapter}/unlock', [UnlockController::class, 'store']);

        // Karya (creator)
        Route::get('creator/dashboard', [WorkController::class, 'creatorDashboard']);
        Route::get('creator/works/{work}', [WorkController::class, 'creatorShow']);
        Route::post('creator/works/{work}/cover', [WorkController::class, 'creatorUploadCover']);
        Route::post('creator/works/{work}/chapters', [WorkController::class, 'creatorStoreChapter']);
        Route::post('creator/works/{work}/publish', [WorkController::class, 'creatorPublish']);
        Route::post('works', [WorkController::class, 'store']);
        Route::post('creator/works/{work}/save', [WorkController::class, 'creatorUpdate']);
        Route::put('creator/works/{work}', [WorkController::class, 'creatorUpdate']);

        // Affiliate
        Route::post('works/{work}/affiliate-link', [AffiliateController::class, 'createLink']);
        Route::get('affiliate/stats', [AffiliateController::class, 'stats']);

        // Withdraw
        Route::get('withdrawals', [WithdrawalController::class, 'index']);
        Route::post('withdrawals', [WithdrawalController::class, 'store']);
    });
});
