<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Infolists;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Infolists\Components\TextEntry::make('name')->label('Project Name'),
            Infolists\Components\TextEntry::make('company.name')->label('Company'),
            Infolists\Components\TextEntry::make('status')->label('Status'),
            Infolists\Components\TextEntry::make('start_date')->date()->label('Start Date'),
            Infolists\Components\TextEntry::make('end_date')->date()->label('End Date'),
            Infolists\Components\TextEntry::make('description')->label('Description'),
        ]);
    }
}
