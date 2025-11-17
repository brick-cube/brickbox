<?php

namespace App\Filament\Company\Resources\Employees\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('aadhar_number'),
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('phone'),
                TextEntry::make('position'),
                TextEntry::make('full_day_rate')
                    ->numeric(),
                TextEntry::make('half_day_rate')
                    ->numeric(),
                TextEntry::make('details'),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('joined_at')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
            ]);
    }
}
