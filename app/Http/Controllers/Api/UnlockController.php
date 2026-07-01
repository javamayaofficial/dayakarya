<?php

namespace App\Http\Controllers\Api;

use App\Models\AffiliateLink;
use App\Models\Chapter;
use App\Models\User;
use App\Services\NotificationService;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Unlock chapter/episode premium memakai Credit.
 * Royalti kreator & komisi affiliate dihitung otomatis di WalletService.
 */
class UnlockController extends \App\Http\Controllers\Controller
{
    public function __construct(
        protected WalletService $wallet,
        protected NotificationService $notifier
    ) {}

    public function store(Request $request, Chapter $chapter): JsonResponse
    {
        $user = $request->user();

        if (! $chapter->is_premium) {
            return response()->json(['message' => 'Chapter ini gratis, tidak perlu Credit.'], 422);
        }

        // Deteksi affiliate dari kode ref (opsional)
        $affiliate = null;
        if ($ref = $request->input('ref')) {
            $link = AffiliateLink::where('code', $ref)->first();
            if ($link) {
                $affiliate = User::find($link->affiliate_id);
                $link->increment('conversions');
            }
        }

        try {
            $unlock = $this->wallet->unlockChapter($user, $chapter, $affiliate);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        // Notifikasi royalti ke kreator
        $creator = $chapter->work->creator;
        $rate = (int) config('dayakarya.economy.credit_rate_rupiah');
        $royalty = (int) floor($chapter->price_credit * $rate * config('dayakarya.economy.royalty_creator_percent') / 100);
        $this->notifier->royaltyReceived($creator, $royalty, $chapter->work->title);

        return response()->json([
            'message'  => 'Chapter berhasil dibuka. Selamat menikmati!',
            'unlock_id'=> $unlock->id,
            'content'  => $chapter->isAudio() ?? false ? null : $chapter->content,
            'audio_url'=> $chapter->audio_url,
        ], 201);
    }
}
