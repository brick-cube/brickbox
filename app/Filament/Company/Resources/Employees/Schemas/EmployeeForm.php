<?php

namespace App\Filament\Company\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Facades\Filament;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn() => Filament::auth()->user()->company_id),
                TextInput::make('aadhar_number')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('position'),
                TextInput::make('full_day_rate')
                    ->required()
                    ->numeric(),
                TextInput::make('half_day_rate')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('details'),
                Toggle::make('is_active')
                    ->required(),
                DatePicker::make('joined_at'),
            ]);
    }
}
