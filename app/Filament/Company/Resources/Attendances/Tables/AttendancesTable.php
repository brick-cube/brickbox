<?php

namespace App\Filament\Company\Resources\Attendances\Tables;

use App\Models\Employee;
use App\Models\Project;
use Filament\Facades\Filament;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('employee.name')
                    ->label('Employee')
                    ->searchable(),

                TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable(),

                BadgeColumn::make('work_type')
                    ->label('Work Type')
                    ->colors([
                        'success' => 'full',
                        'warning' => 'half',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'present',
                        'danger' => 'absent',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
            ])

            ->filters([

                SelectFilter::make('project_id')
                    ->label('Project')
                    ->options(fn () =>
                        Project::where('company_id', Filament::auth()->user()->company_id)
                            ->pluck('name', 'id')
                    ),

                SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->options(fn () =>
                        Employee::where('company_id', Filament::auth()->user()->company_id)
                            ->pluck('name', 'id')
                    ),

                Filter::make('date')
                    ->label('Date Range')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('to'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('date', '>=', $data['from']))
                            ->when($data['to'], fn ($q) => $q->whereDate('date', '<=', $data['to']));
                    }),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
