<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.admin.pages.site-settings';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Situs';

    protected static ?string $title = 'Pengaturan Situs';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->loadSettings());
    }

    protected function loadSettings(): array
    {
        $keys = $this->settingKeys();
        $stored = Setting::query()->whereIn('key', $keys)->pluck('value', 'key')->toArray();

        return array_merge(array_fill_keys($keys, null), $stored);
    }

    protected function settingKeys(): array
    {
        return [
            'general_site_name',
            'general_tagline',
            'general_homepage_title',
            'general_homepage_subtitle',
            'brand_logo_path',
            'brand_favicon_path',
            'contact_email',
            'contact_phone',
            'contact_whatsapp',
            'contact_address',
            'meta_description',
            'meta_keywords',
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('SettingsTabs')
                    ->tabs([
                        Tab::make('Informasi Umum')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make('Identitas')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('general_site_name')
                                            ->label('Nama Situs')
                                            ->required()
                                            ->maxLength(120),
                                        TextInput::make('general_tagline')
                                            ->label('Tagline')
                                            ->maxLength(200),
                                        Textarea::make('general_homepage_title')
                                            ->label('Judul Hero')
                                            ->rows(2),
                                        Textarea::make('general_homepage_subtitle')
                                            ->label('Subjudul Hero')
                                            ->rows(2),
                                    ]),
                            ]),
                        Tab::make('Branding')
                            ->icon('heroicon-o-swatch')
                            ->schema([
                                Section::make('Aset Visual')
                                    ->columns(2)
                                    ->schema([
                                        FileUpload::make('brand_logo_path')
                                            ->label('Logo')
                                            ->image()
                                            ->directory('settings')
                                            ->maxSize(2048)
                                            ->imageEditor(),
                                        FileUpload::make('brand_favicon_path')
                                            ->label('Favicon')
                                            ->image()
                                            ->directory('settings')
                                            ->acceptedFileTypes(['image/png', 'image/x-icon', 'image/vnd.microsoft.icon'])
                                            ->maxSize(1024),
                                    ]),
                            ]),
                        Tab::make('Kontak')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Section::make('Informasi Kontak')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('contact_email')
                                            ->label('Email Support')
                                            ->email(),
                                        TextInput::make('contact_phone')
                                            ->label('Nomor Telepon'),
                                        TextInput::make('contact_whatsapp')
                                            ->label('WhatsApp'),
                                        Textarea::make('contact_address')
                                            ->label('Alamat')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('Metadata')
                            ->icon('heroicon-o-command-line')
                            ->schema([
                                Section::make('SEO')
                                    ->schema([
                                        Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->rows(3),
                                        TextInput::make('meta_keywords')
                                            ->label('Meta Keywords')
                                            ->helperText('Pisahkan dengan koma.')
                                            ->maxLength(255),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::setValue($key, $value);
        }

        Notification::make()
            ->title('Pengaturan disimpan')
            ->success()
            ->send();
    }
}
