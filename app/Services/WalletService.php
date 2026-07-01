<?php

namespace App\Services;

use App\Models\Chapter;
use App\Models\Commission;
use App\Models\CreditTransaction;
use App\Models\Payment;
use App\Models\Royalty;
use App\Models\Unlock;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * WalletService — pusat seluruh mutasi keuangan Dayakarya.
 * Semua perubahan saldo WAJIB lewat sini agar konsisten & tercatat di ledger.
 */
class WalletService
{
    /**
     * Tambah Credit setelah pembayaran top up berhasil (idempotent).
     */
    public function creditTopup(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            // Kunci baris payment agar tidak diproses ganda oleh callback berulang
            $payment = Payment::whereKey($payment->id)->lockForUpdate()->first();

            if ($payment->status === 'paid') {
                return; // sudah diproses, hentikan (idempotent)
            }

            $wallet = $this->walletFor($payment->user);
            $wallet->increment('credit_balance', $payment->credit_amount);

            $this->log($wallet, 'topup', 'credit', $payment->credit_amount, $payment->order_id,
                'Top up ' . number_format($payment->credit_amount) . ' Credit');

            $payment->update(['status' => 'paid', 'paid_at' => now()]);
        });
    }

    /**
     * Unlock chapter/episode premium dengan Credit.
     * Sekaligus mencatat royalti kreator & komisi affiliate secara otomatis.
     *
     * @throws RuntimeException bila saldo kurang / sudah ter-unlock
     */
    public function unlockChapter(User $user, Chapter $chapter, ?User $affiliate = null): Unlock
    {
        return DB::transaction(function () use ($user, $chapter, $affiliate) {
            $wallet = $this->walletFor($user, lock: true);

            if ($chapter->unlocks()->where('user_id', $user->id)->exists()) {
                throw new RuntimeException('Chapter ini sudah kamu buka sebelumnya.');
            }

            $price = (int) $chapter->price_credit;
            if ($wallet->credit_balance < $price) {
                throw new RuntimeException('Credit kamu belum cukup untuk membuka chapter ini.');
            }

            // 1) Potong Credit pembaca
            $wallet->decrement('credit_balance', $price);
            $this->log($wallet, 'unlock', 'credit', -$price, "chapter:{$chapter->id}",
                'Buka: ' . $chapter->title);

            // 2) Catat unlock
            $unlock = Unlock::create([
                'user_id'      => $user->id,
                'chapter_id'   => $chapter->id,
                'affiliate_id' => $affiliate?->id,
                'credit_spent' => $price,
            ]);

            // 3) Konversi Credit -> Rupiah untuk distribusi bagi hasil
            $rate       = (int) config('dayakarya.economy.credit_rate_rupiah');
            $grossRp    = $price * $rate;
            $royaltyPct = (int) config('dayakarya.economy.royalty_creator_percent');
            $commPct    = (int) config('dayakarya.economy.affiliate_commission_percent');

            // 4) Royalti kreator (otomatis)
            $creator = $chapter->work->creator;
            $royaltyRp = (int) floor($grossRp * $royaltyPct / 100);
            if ($royaltyRp > 0) {
                $creatorWallet = $this->walletFor($creator, lock: true);
                $creatorWallet->increment('rupiah_balance', $royaltyRp);
                Royalty::create([
                    'creator_id'   => $creator->id,
                    'unlock_id'    => $unlock->id,
                    'amount_rupiah'=> $royaltyRp,
                ]);
                $this->log($creatorWallet, 'royalty', 'rupiah', $royaltyRp, "unlock:{$unlock->id}",
                    'Royalti dari: ' . $chapter->title);
            }

            // 5) Komisi affiliate (otomatis, bila ada & bukan diri sendiri)
            if ($affiliate && $affiliate->id !== $user->id) {
                $commRp = (int) floor($grossRp * $commPct / 100);
                if ($commRp > 0) {
                    $affWallet = $this->walletFor($affiliate, lock: true);
                    $affWallet->increment('rupiah_balance', $commRp);
                    Commission::create([
                        'affiliate_id'  => $affiliate->id,
                        'unlock_id'     => $unlock->id,
                        'amount_rupiah' => $commRp,
                    ]);
                    $this->log($affWallet, 'commission', 'rupiah', $commRp, "unlock:{$unlock->id}",
                        'Komisi affiliate: ' . $chapter->title);
                }
            }

            return $unlock;
        });
    }

    /**
     * Potong saldo rupiah saat pengajuan withdraw disetujui.
     */
    public function debitForWithdraw(User $user, int $amount, string $reference): void
    {
        DB::transaction(function () use ($user, $amount, $reference) {
            $wallet = $this->walletFor($user, lock: true);
            if ($wallet->rupiah_balance < $amount) {
                throw new RuntimeException('Saldo rupiah tidak mencukupi.');
            }
            $wallet->decrement('rupiah_balance', $amount);
            $this->log($wallet, 'withdraw', 'rupiah', -$amount, $reference, 'Penarikan dana');
        });
    }

    // ---------------------------------------------------------------

    protected function walletFor(User $user, bool $lock = false): Wallet
    {
        $query = Wallet::where('user_id', $user->id);
        if ($lock) {
            $query->lockForUpdate();
        }
        return $query->firstOr(fn () => $user->wallet()->create());
    }

    protected function log(Wallet $wallet, string $type, string $currency, int $amount, ?string $ref, string $desc): void
    {
        $balanceAfter = $currency === 'credit' ? $wallet->credit_balance : $wallet->rupiah_balance;
        CreditTransaction::create([
            'wallet_id'     => $wallet->id,
            'type'          => $type,
            'currency'      => $currency,
            'amount'        => $amount,
            'balance_after' => $balanceAfter,
            'reference'     => $ref,
            'description'   => $desc,
        ]);
    }
}
