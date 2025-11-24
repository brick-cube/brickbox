<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\ColorEntry;
use Filament\Schemas\Schema;

class CompanyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextEntry::make('name'),

                TextEntry::make('email')
                    ->label('Email address'),

                TextEntry::make('phone'),

                TextEntry::make('address'),

                TextEntry::make('subdomain')
                    ->label('Subdomain')
                    ->suffix('.brickbox.local'),

                ColorEntry::make('color')
                    ->label('Brand Color'),

                ImageEntry::make('logo')
                    ->label('Logo')
                    ->circular()
                    ->size(80),

                TextEntry::make('created_at')
                    ->dateTime()
                    ->label('Created'),

                TextEntry::make('updated_at')
                    ->dateTime()
                    ->label('Last Updated'),
            ]);
    }
}
