<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Services\WalletService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Keuangan';
    protected static ?string $modelLabel = 'Pembayaran';
    protected static ?string $pluralModelLabel = 'Pembayaran';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()
            ->whereIn('provider', ['manual', 'qris_manual'])
            ->where('status', 'awaiting_confirmation')
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('order_id')->label('Order ID')->disabled(),
            TextInput::make('user.name')->label('Pengguna')->disabled(),
            TextInput::make('provider')->label('Provider')->disabled(),
            TextInput::make('amount_rupiah')->label('Nominal Transfer')->prefix('Rp')->disabled(),
            TextInput::make('credit_amount')->label('Credit')->suffix(' Credit')->disabled(),
            TextInput::make('reference')->label('Referensi')->disabled(),
            TextInput::make('payment_method')->label('Metode Pembayaran')->disabled(),
            TextInput::make('payment_url')->label('URL Pembayaran')->disabled(),
            Select::make('status')->label('Status')->options([
                'pending' => 'Pending',
                'awaiting_confirmation' => 'Menunggu Verifikasi',
                'paid' => 'Paid',
                'failed' => 'Failed',
                'expired' => 'Expired',
            ])->required(),
            FileUpload::make('proof')
                ->label('Bukti Transfer')
                ->disk('public')
                ->directory('payment-proofs')
                ->image()
                ->imagePreviewHeight('220')
                ->openable()
                ->downloadable(),
            Placeholder::make('proof_note')
                ->label('Catatan Bukti')
                ->content('Bukti transfer yang diunggah pengguna akan tampil di sini. Admin tetap bisa mengganti file jika diperlukan.'),
            Textarea::make('meta')
                ->label('Meta / Instruksi')
                ->rows(8)
                ->formatStateUsing(fn (?array $state) => $state ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : null)
                ->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('proof')
                    ->label('Bukti')
                    ->disk('public')
                    ->square(),
                Tables\Columns\TextColumn::make('order_id')->label('Order ID')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('user.name')->label('Pengguna')->searchable(),
                Tables\Columns\TextColumn::make('provider')
                    ->label('Provider')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'manual' => 'Manual',
                        'qris_manual' => 'QRIS Manual',
                        'duitku' => 'Duitku',
                        default => ucfirst($state),
                    }),
                Tables\Columns\TextColumn::make('amount_rupiah')->label('Nominal')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('credit_amount')
                    ->label('Credit')
                    ->formatStateUsing(fn (int $state): string => number_format($state, 0, ',', '.') . ' Credit'),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'gray' => 'pending',
                    'warning' => 'awaiting_confirmation',
                    'success' => 'paid',
                    'danger' => 'failed',
                    'info' => 'expired',
                ])->formatStateUsing(fn (string $state): string => match ($state) {
                    'awaiting_confirmation' => 'Menunggu Verifikasi',
                    'paid' => 'Paid',
                    'failed' => 'Failed',
                    'expired' => 'Expired',
                    default => ucfirst($state),
                }),
                Tables\Columns\TextColumn::make('paid_at')->label('Dibayar')->dateTime('d M Y H:i')->placeholder('-'),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('provider')->options([
                    'manual' => 'Transfer Manual',
                    'qris_manual' => 'QRIS Manual',
                    'duitku' => 'Duitku',
                ]),
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'awaiting_confirmation' => 'Menunggu Verifikasi',
                    'paid' => 'Paid',
                    'failed' => 'Failed',
                    'expired' => 'Expired',
                ]),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Topup Manual')
                    ->modalDescription('Credit pengguna akan langsung ditambahkan ke wallet dan transaksi ditandai paid.')
                    ->visible(fn (Payment $record): bool => in_array($record->provider, ['manual', 'qris_manual'], true) && $record->status === 'awaiting_confirmation')
                    ->action(function (Payment $record): void {
                        $processed = app(WalletService::class)->creditTopup($record);

                        Notification::make()
                            ->title($processed ? 'Topup manual disetujui.' : 'Topup sudah pernah diproses sebelumnya.')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Topup Manual')
                    ->modalDescription('Transaksi manual akan ditandai gagal dan tidak menambah credit pengguna.')
                    ->visible(fn (Payment $record): bool => in_array($record->provider, ['manual', 'qris_manual'], true) && $record->status === 'awaiting_confirmation')
                    ->action(function (Payment $record): void {
                        $record->update([
                            'status' => 'failed',
                            'paid_at' => null,
                        ]);

                        Notification::make()
                            ->title('Topup manual ditolak.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
