<?php

namespace App\Filament\Company\Resources\Attendances\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class AttendanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Attendance Details')
                ->schema([

                    Grid::make(2)->schema([
                        
                        TextEntry::make('employee.name')
                            ->label('Employee'),

                        TextEntry::make('project.name')
                            ->label('Project'),

                        TextEntry::make('date')
                            ->date()
                            ->label('Date'),

                        TextEntry::make('type')
                            ->label('Type'),

                        TextEntry::make('work_type')
                            ->label('Work Type')
                            ->extraAttributes(fn ($state) => [
                                'class' => match ($state) {
                                    'full' => 'text-green-600 font-semibold',
                                    'half' => 'text-yellow-600 font-semibold',
                                    default => '',
                                }
                            ]),

                        TextEntry::make('created_at')
                            ->dateTime()
                            ->label('Created'),

                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->label('Updated'),
                    ])
                ]),
        ]);
    }
}
