<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Services\WalletService;
use Filament\Resources\Pages\EditRecord;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->record;

        if (
            $data['status'] === 'paid'
            && $record->status !== 'paid'
            && in_array($record->provider, ['manual', 'qris_manual'], true)
        ) {
            app(WalletService::class)->creditTopup($record);
            $data['paid_at'] = $record->fresh()->paid_at;
        }

        if (
            $data['status'] === 'failed'
            && $record->status === 'awaiting_confirmation'
            && in_array($record->provider, ['manual', 'qris_manual'], true)
        ) {
            $data['paid_at'] = null;
        }

        return $data;
    }
}
