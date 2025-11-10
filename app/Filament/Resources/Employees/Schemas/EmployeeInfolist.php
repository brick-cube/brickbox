<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextEntry::make('company.name')
                ->label('Company'),

            TextEntry::make('project.name')
                ->label('Project')
                ->placeholder('-'),

            TextEntry::make('name')
                ->label('Employee Name'),

            TextEntry::make('email')
                ->label('Email')
                ->placeholder('-'),

            TextEntry::make('phone')
                ->label('Phone')
                ->placeholder('-'),

            TextEntry::make('role')
                ->label('Role / Position')
                ->placeholder('-'),

            TextEntry::make('status')
                ->badge(),

            TextEntry::make('joined_date')
                ->date()
                ->label('Joined')
                ->placeholder('-'),

            TextEntry::make('remarks')
                ->label('Remarks')
                ->columnSpanFull()
                ->placeholder('-'),

            TextEntry::make('created_at')
                ->label('Created At')
                ->dateTime(),

            TextEntry::make('updated_at')
                ->label('Last Updated')
                ->dateTime(),
        ]);
    }
}
