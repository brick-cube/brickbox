<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('address'),

                TextInput::make('subdomain')
                    ->label('Company Subdomain')
                    ->unique(ignoreRecord: true)
                    ->placeholder('example → wizaura, skyline, abc')
                    ->afterStateUpdated(function ($state, $set) {
                        $clean = strtolower($state);
                        $clean = str_replace(['http://', 'https://'], '', $clean);
                        $clean = str_replace('.brickbox.local', '', $clean);
                        $clean = preg_replace('/[^a-z0-9]/', '', $clean);
                        $set('subdomain', $clean);
                    })
                    ->helperText('Final URL will be: https://{subdomain}.brickbox.local')
                    ->required(),


                ColorPicker::make('color')
                    ->label('Brand Color')
                    ->default('#4997D3')
                    ->helperText('Used for brand theming inside dashboard'),
                FileUpload::make('logo')
                    ->image()
                    ->directory('company-logos')
                    ->imageEditor()
                    ->maxSize(2048)
                    ->nullable(),
            ]);
    }
}
