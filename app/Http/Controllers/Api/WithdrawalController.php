<?php

namespace App\Http\Controllers\Api;

use App\Models\Withdrawal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Pengajuan penarikan dana (royalti/komisi) ke bank atau e-wallet.
 * Persetujuan & pembayaran dilakukan admin di panel Filament.
 */
class WithdrawalController extends \App\Http\Controllers\Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $request->user()->withdrawals()->latest()->paginate(20)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $min = (int) config('dayakarya.economy.withdraw_minimum');
        $fee = (int) config('dayakarya.economy.withdraw_fee');

        $data = $request->validate([
            'destination_type' => ['required', 'in:bank,ewallet'],
            'destination_name' => ['required', 'string', 'max:50'],
            'account_number'   => ['required', 'string', 'max:50'],
            'account_holder'   => ['required', 'string', 'max:100'],
            'amount'           => ['required', 'integer', "min:$min"],
        ]);

        $user = $request->user();
        $pendingAmount = $user->withdrawals()
            ->where('status', 'pending')
            ->sum('amount');
        $availableBalance = max(0, $user->wallet->rupiah_balance - $pendingAmount);

        if ($availableBalance < $data['amount']) {
            return response()->json([
                'message' => 'Saldo rupiah tersedia kamu belum mencukupi untuk penarikan ini.',
            ], 422);
        }

        $withdrawal = Withdrawal::create([
            ...$data,
            'user_id'    => $user->id,
            'fee'        => $fee,
            'net_amount' => $data['amount'] - $fee,
            'status'     => 'pending',
        ]);

        return response()->json([
            'message'    => 'Permintaan penarikan diterima dan sedang diproses admin.',
            'withdrawal' => $withdrawal,
        ], 201);
    }
}
