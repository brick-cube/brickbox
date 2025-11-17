<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use App\Models\Company;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),

                Select::make('company_id')
                    ->label('Company')
                    ->options(Company::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('role')
                    ->label('Role')
                    ->options(Role::pluck('name', 'name'))
                    ->required(),

                TextInput::make('password')
                    ->password()
                    ->confirmed()
                    ->required(fn($record) => $record === null)
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? bcrypt($state) : null)
                    ->dehydrated(fn($state) => filled($state))
                    ->label('Password'),

                TextInput::make('password_confirmation')
                    ->password()
                    ->label('Confirm Password')
                    ->required(fn($record) => $record === null),
            ]);
    }
}
