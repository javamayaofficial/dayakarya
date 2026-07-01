<?php

namespace App\Filament\Resources\WithdrawalResource\Pages;

use App\Filament\Resources\WithdrawalResource;
use App\Services\WalletService;
use Filament\Resources\Pages\EditRecord;

class EditWithdrawal extends EditRecord
{
    protected static string $resource = WithdrawalResource::class;

    /** Saat disetujui, potong saldo rupiah user (sekali, aman). */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->record;

        if ($data['status'] === 'approved' && $record->status !== 'approved') {
            app(WalletService::class)->debitForWithdraw(
                $record->user,
                (int) $record->amount,
                'withdrawal:' . $record->id
            );
            $data['processed_by'] = auth()->id();
            $data['processed_at'] = now();
        }

        return $data;
    }
}
