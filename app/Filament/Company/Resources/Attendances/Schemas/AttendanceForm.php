<?php

namespace App\Filament\Company\Resources\Attendances\Schemas;

use App\Models\Employee;
use App\Models\Project;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)
                ->schema([

                    // 🔒 company_id is auto-set from logged-in user
                    Hidden::make('company_id')
                        ->default(fn() => Filament::auth()->user()->company_id),

                    Select::make('employee_id')
                        ->label('Employee')
                        ->searchable()
                        ->required()
                        ->options(
                            fn() =>
                            Employee::where('company_id', Filament::auth()->user()->company_id)
                                ->where('is_active', true)
                                ->pluck('name', 'id')
                        ),

                    Select::make('project_id')
                        ->label('Project')
                        ->searchable()
                        ->required()
                        ->options(
                            fn() =>
                            Project::where('company_id', Filament::auth()->user()->company_id)
                                ->where('is_active', true)
                                ->pluck('name', 'id')
                        ),

                    DatePicker::make('date')
                        ->label('Date')
                        ->required()
                        ->native(false),

                    Select::make('type')
                        ->label('Type')
                        ->options([
                            'contract' => 'Contract',
                            'daily'    => 'Daily Wages',
                        ])
                        ->default('contract')
                        ->required(),

                    Select::make('work_type')
                        ->label('Work Type')
                        ->options([
                            'full' => 'Full Day',
                            'half' => 'Half Day',
                        ])
                        ->default('full')
                        ->required(),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'present' => 'Present',
                            'absent'  => 'Absent',
                        ])
                        ->default('present')
                        ->required(),
                ]),
        ]);
    }
}
