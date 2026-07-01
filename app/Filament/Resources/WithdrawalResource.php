<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawalResource\Pages;
use App\Models\Withdrawal;
use App\Services\WalletService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Approval penarikan dana kreator/affiliate.
 * Saat status disubah ke "approved", saldo rupiah user otomatis dipotong.
 */
class WithdrawalResource extends Resource
{
    protected static ?string $model = Withdrawal::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Keuangan';
    protected static ?string $modelLabel = 'Penarikan';
    protected static ?string $pluralModelLabel = 'Penarikan Dana';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('user.name')->label('Pengguna')->disabled(),
            TextInput::make('amount')->label('Jumlah')->prefix('Rp')->disabled(),
            TextInput::make('destination_name')->label('Tujuan')->disabled(),
            TextInput::make('account_number')->label('No. Rekening/E-Wallet')->disabled(),
            TextInput::make('account_holder')->label('Atas Nama')->disabled(),
            Select::make('status')->label('Status')->options([
                'pending'  => 'Menunggu',
                'approved' => 'Disetujui (potong saldo)',
                'paid'     => 'Sudah Dibayar',
                'rejected' => 'Ditolak',
            ])->required(),
            TextInput::make('note')->label('Catatan Admin'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Pengguna')->searchable(),
                Tables\Columns\TextColumn::make('amount')->label('Jumlah')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('net_amount')->label('Diterima')->money('IDR'),
                Tables\Columns\TextColumn::make('destination_name')->label('Tujuan'),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'warning' => 'pending',
                    'info'    => 'approved',
                    'success' => 'paid',
                    'danger'  => 'rejected',
                ]),
                Tables\Columns\TextColumn::make('created_at')->label('Diajukan')->dateTime('d M Y H:i'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Menunggu', 'approved' => 'Disetujui',
                    'paid' => 'Dibayar', 'rejected' => 'Ditolak',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWithdrawals::route('/'),
            'edit'  => Pages\EditWithdrawal::route('/{record}/edit'),
        ];
    }
}
