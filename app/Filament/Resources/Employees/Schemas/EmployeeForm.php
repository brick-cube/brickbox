<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('company_id')
                ->relationship('company', 'name')
                ->label('Company')
                ->required()
                ->searchable(),

            Select::make('project_id')
                ->relationship('project', 'name')
                ->label('Project')
                ->nullable()
                ->searchable()
                ->helperText('Optional: Assign employee to a specific project.'),

            TextInput::make('name')
                ->label('Full Name')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->nullable(),

            TextInput::make('phone')
                ->label('Phone')
                ->tel()
                ->nullable(),

            TextInput::make('role')
                ->label('Role / Position')
                ->placeholder('e.g., Site Engineer, Supervisor, Laborer'),

            Select::make('status')
                ->label('Status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'on_leave' => 'On Leave',
                ])
                ->default('active'),

            DatePicker::make('joined_date')
                ->label('Joining Date'),

            Textarea::make('remarks')
                ->label('Remarks')
                ->placeholder('Any notes or additional information')
                ->columnSpanFull(),
        ]);
    }
}
