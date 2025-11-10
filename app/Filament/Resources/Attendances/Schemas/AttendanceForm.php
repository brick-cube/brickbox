<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('employee_id')
                ->relationship('employee', 'name')
                ->label('Employee')
                ->required()
                ->searchable(),

            Select::make('project_id')
                ->relationship('project', 'name')
                ->label('Project')
                ->nullable()
                ->searchable(),

            DatePicker::make('date')
                ->label('Date')
                ->default(now())
                ->required(),

            Select::make('status')
                ->label('Status')
                ->options([
                    'present' => 'Present',
                    'absent' => 'Absent',
                    'leave' => 'Leave',
                    'half_day' => 'Half Day',
                ])
                ->default('present')
                ->required(),

            Textarea::make('remarks')
                ->label('Remarks')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }
}
