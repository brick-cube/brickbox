<?php

namespace App\Filament\Company\Resources\Projects\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Project Details')
                ->columnSpanFull()
                ->schema([

                    Grid::make(2)
                        ->schema([

                            TextEntry::make('name')
                                ->label('Project Name'),

                            TextEntry::make('address')
                                ->label('Address'),

                            TextEntry::make('type')
                                ->label('Project Type')
                                ->formatStateUsing(fn($state) => $state ? ucfirst($state) : 'N/A')
                                ->extraAttributes(fn($state) => [
                                    'class' => match ($state) {
                                        'residential' => 'inline-block px-2 py-0.5 rounded-full text-xs font-semibold text-white bg-blue-600',
                                        'commercial'  => 'inline-block px-2 py-0.5 rounded-full text-xs font-semibold text-white bg-sky-500',
                                        'coolie-work' => 'inline-block px-2 py-0.5 rounded-full text-xs font-semibold text-white bg-orange-500',
                                        default => 'inline-block px-2 py-0.5 rounded-full text-xs font-medium text-gray-700 bg-gray-100',
                                    },
                                ]),

                            TextEntry::make('status')
                                ->label('Status')
                                ->formatStateUsing(fn($state) => $state ? ucfirst($state) : 'N/A')
                                ->extraAttributes(fn($state) => [
                                    'class' => match ($state) {
                                        'started', 'foundation', 'structure' => 'inline-block px-2 py-0.5 rounded-full text-xs font-semibold text-white bg-green-600',
                                        'plastering', 'electrical and plumbing', 'flooring', 'painting' => 'inline-block px-2 py-0.5 rounded-full text-xs font-semibold text-white bg-yellow-500',
                                        'finished' => 'inline-block px-2 py-0.5 rounded-full text-xs font-semibold text-white bg-indigo-600',
                                        'stopped' => 'inline-block px-2 py-0.5 rounded-full text-xs font-semibold text-white bg-red-600',
                                        default => 'inline-block px-2 py-0.5 rounded-full text-xs font-medium text-gray-700 bg-gray-100',
                                    },
                                ]),

                            TextEntry::make('area')
                                ->label('Area (sq.ft)')
                                ->formatStateUsing(fn($state) => $state ? "{$state} sq.ft" : 'N/A'),

                            TextEntry::make('value')
                                ->label('Project Value')
                                ->formatStateUsing(fn($state) => $state ? '₹ ' . number_format($state, 2) : 'N/A'),

                            IconEntry::make('is_active')
                                ->label('Active')
                                ->boolean(),

                            TextEntry::make('start_date')
                                ->label('Start Date')
                                ->date(),

                            TextEntry::make('end_date')
                                ->label('End Date')
                                ->date(),

                            TextEntry::make('created_at')
                                ->label('Created')
                                ->dateTime(),

                            TextEntry::make('updated_at')
                                ->label('Updated')
                                ->dateTime(),
                        ]),
                ]),
        ]);
    }
}
