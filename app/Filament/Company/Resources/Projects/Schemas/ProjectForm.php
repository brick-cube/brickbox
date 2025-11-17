<?php

namespace App\Filament\Company\Resources\Projects\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Hidden::make('company_id')
                    ->default(fn() => Filament::auth()->user()->company_id),

                TextInput::make('name')
                    ->label('Project Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('address')
                    ->label('Project Address')
                    ->required(),

                Select::make('type')
                    ->label('Project Type')
                    ->options([
                        'residential' => 'Residential',
                        'commercial' => 'Commercial',
                        'coolie-work' => 'Coolie Work',
                    ])
                    ->required()
                    ->native(false),

                TextInput::make('area')
                    ->label('Area (sq.ft)')
                    ->numeric()
                    ->suffix('sq.ft')
                    ->nullable(),

                TextInput::make('value')
                    ->label('Project Value')
                    ->numeric()
                    ->prefix('₹')
                    ->nullable(),

                Select::make('status')
                    ->label('Project Status')
                    ->options([
                        'started' => 'Started',
                        'foundation' => 'Foundation',
                        'structure' => 'Structure',
                        'plastering' => 'Plastering',
                        'electrical and plumbing' => 'Electrical & Plumbing',
                        'flooring' => 'Flooring',
                        'painting' => 'Painting',
                        'finished' => 'Finished',
                        'stopped' => 'Stopped',
                    ])
                    ->required()
                    ->native(false),

                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->nullable()
                    ->native(false),

                DatePicker::make('end_date')
                    ->label('End Date')
                    ->nullable()
                    ->native(false),
            ]);
    }
}
