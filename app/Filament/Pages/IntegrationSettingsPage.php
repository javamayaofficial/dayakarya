<?php

namespace App\Filament\Pages;

use App\Support\IntegrationSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;

class IntegrationSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Integrasi';

    protected static ?string $title = 'Pengaturan Integrasi';

    protected static ?string $slug = 'integrasi';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.integration-settings-page';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'providers.payment' => IntegrationSettings::get('providers.payment', config('dayakarya.providers.payment')),
            'providers.whatsapp' => IntegrationSettings::get('providers.whatsapp', config('dayakarya.providers.whatsapp')),
            'providers.email' => IntegrationSettings::get('providers.email', config('dayakarya.providers.email')),
            'support.whatsapp_number' => IntegrationSettings::get('support.whatsapp_number', config('dayakarya.support.whatsapp_number')),
            'support.email' => IntegrationSettings::get('support.email', config('dayakarya.support.email')),
            'mail.from_name' => IntegrationSettings::get('mail.from_name', config('dayakarya.mail.from_name')),
            'mail.from_email' => IntegrationSettings::get('mail.from_email', config('dayakarya.mail.from_email')),
            'payment.manual.bank_name' => IntegrationSettings::get('payment.manual.bank_name', config('dayakarya.manual_payment.bank_name')),
            'payment.manual.account' => IntegrationSettings::get('payment.manual.account', config('dayakarya.manual_payment.account')),
            'payment.manual.holder' => IntegrationSettings::get('payment.manual.holder', config('dayakarya.manual_payment.holder')),
            'payment.manual.qris_image_url' => IntegrationSettings::get('payment.manual.qris_image_url', config('dayakarya.manual_payment.qris_image')),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Provider Aktif')
                    ->description('Pilih integrasi operasional yang aktif. Secret tetap dikelola aman melalui file .env server.')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('providers.payment')
                                    ->label('Payment Gateway')
                                    ->options([
                                        'duitku' => 'Duitku',
                                        'manual' => 'Transfer Manual',
                                        'qris_manual' => 'QRIS Manual',
                                    ])
                                    ->required(),
                                Select::make('providers.whatsapp')
                                    ->label('WhatsApp')
                                    ->options([
                                        'fonnte' => 'Fonnte',
                                    ])
                                    ->required(),
                                Select::make('providers.email')
                                    ->label('Email')
                                    ->options([
                                        'mailketing' => 'Mailketing',
                                    ])
                                    ->required(),
                            ]),
                    ]),

                Section::make('Kontak Operasional')
                    ->description('Dipakai untuk komunikasi support, bukti transfer, dan identitas pengirim yang terlihat oleh pengguna.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('support.whatsapp_number')
                                    ->label('Nomor WhatsApp Support')
                                    ->tel()
                                    ->placeholder('62812xxxxxxx')
                                    ->helperText('Gunakan format internasional tanpa tanda plus agar cocok untuk tautan wa.me.')
                                    ->required(),
                                TextInput::make('support.email')
                                    ->label('Email Support')
                                    ->email()
                                    ->placeholder('admin@dayakarya.id')
                                    ->required(),
                                TextInput::make('mail.from_name')
                                    ->label('Nama Pengirim Email')
                                    ->placeholder('Dayakarya')
                                    ->required(),
                                TextInput::make('mail.from_email')
                                    ->label('Email Pengirim')
                                    ->email()
                                    ->placeholder('noreply@dayakarya.id')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Pembayaran Manual')
                    ->description('Dipakai saat provider pembayaran diatur ke transfer manual atau QRIS manual.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('payment.manual.bank_name')
                                    ->label('Nama Bank / Channel')
                                    ->placeholder('BCA')
                                    ->required(),
                                TextInput::make('payment.manual.account')
                                    ->label('Nomor Rekening / Nomor Tujuan')
                                    ->placeholder('1234567890')
                                    ->required(),
                                TextInput::make('payment.manual.holder')
                                    ->label('Atas Nama')
                                    ->placeholder('Yayasan Pondok Daya Cipta Nusantara')
                                    ->required(),
                                TextInput::make('payment.manual.qris_image_url')
                                    ->label('URL Gambar QRIS')
                                    ->url()
                                    ->placeholder('https://dayakarya.id/storage/qris.png'),
                            ]),
                    ]),

                Section::make('Kredensial Rahasia')
                    ->description('Token dan API key tidak disimpan di database admin. Ini sengaja dipisahkan agar keamanan production tetap terjaga.')
                    ->schema([
                        Placeholder::make('secret_note')
                            ->hiddenLabel()
                            ->content(new HtmlString(
                                '<strong>Kelola di .env server:</strong><br>'
                                . 'FONNTE_TOKEN, MAILKETING_API_TOKEN, DUITKU_MERCHANT_CODE, DUITKU_API_KEY.<br><br>'
                                . '<strong>Callback aktif saat ini:</strong><br>'
                                . e((string) config('duitku.callback_url')) . '<br>'
                                . '<strong>Return URL:</strong><br>'
                                . e((string) config('duitku.return_url'))
                            )),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        IntegrationSettings::setMany([
            'providers.payment' => data_get($state, 'providers.payment'),
            'providers.whatsapp' => data_get($state, 'providers.whatsapp'),
            'providers.email' => data_get($state, 'providers.email'),
            'support.whatsapp_number' => preg_replace('/\D+/', '', (string) data_get($state, 'support.whatsapp_number')),
            'support.email' => data_get($state, 'support.email'),
            'mail.from_name' => data_get($state, 'mail.from_name'),
            'mail.from_email' => data_get($state, 'mail.from_email'),
            'payment.manual.bank_name' => data_get($state, 'payment.manual.bank_name'),
            'payment.manual.account' => data_get($state, 'payment.manual.account'),
            'payment.manual.holder' => data_get($state, 'payment.manual.holder'),
            'payment.manual.qris_image_url' => data_get($state, 'payment.manual.qris_image_url'),
        ]);

        Notification::make()
            ->title('Pengaturan integrasi berhasil disimpan')
            ->body('Perubahan operasional aktif tanpa menyentuh secret di file .env.')
            ->success()
            ->send();
    }
}
