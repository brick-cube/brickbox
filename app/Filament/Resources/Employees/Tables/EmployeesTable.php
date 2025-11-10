<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;

// Actions
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Employee Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('company.name')
                    ->label('Company')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('project.name')
                    ->label('Project')
                    ->placeholder('-')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('role')
                    ->label('Role / Position')
                    ->sortable()
                    ->searchable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'on_leave',
                        'danger'  => 'inactive',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'active',
                        'heroicon-o-pause-circle' => 'on_leave',
                        'heroicon-o-x-circle'     => 'inactive',
                    ])
                    ->sortable(),

                TextColumn::make('joined_date')
                    ->label('Joined')
                    ->date()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->limit(20)
                    ->tooltip(fn ($record) => $record->email),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->limit(15)
                    ->tooltip(fn ($record) => $record->phone),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name'),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'on_leave' => 'On Leave',
                    ]),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])

            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('joined_date', 'desc');
    }
}
