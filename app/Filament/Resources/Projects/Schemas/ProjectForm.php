<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Select::make('company_id')
                ->relationship('company', 'name')
                ->label('Company')
                ->required(),

            Forms\Components\TextInput::make('name')
                ->label('Project Name')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->rows(3),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'ongoing' => 'Ongoing',
                    'completed' => 'Completed',
                ])
                ->default('pending'),

            Forms\Components\DatePicker::make('start_date')
                ->label('Start Date'),

            Forms\Components\DatePicker::make('end_date')
                ->label('End Date'),
        ]);
    }
}
