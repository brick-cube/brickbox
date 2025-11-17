<?php

namespace App\Filament\Company\Resources\Projects\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                
                TextColumn::make('name')
                    ->label('Project Name')
                    ->searchable(),

                TextColumn::make('address')
                    ->label('Address')
                    ->limit(30)
                    ->searchable(),

                BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'residential',
                        'info' => 'commercial',
                        'warning' => 'coolie-work',
                    ]),

                TextColumn::make('area')
                    ->label('Area')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} sq.ft" : 'N/A'),

                TextColumn::make('value')
                    ->label('Value')
                    ->formatStateUsing(fn ($state) => $state ? "₹ " . number_format($state, 2) : 'N/A'),

                BadgeColumn::make('status')
                    ->colors([
                        'success' => ['started', 'foundation', 'structure'],
                        'warning' => ['plastering', 'electrical and plumbing', 'flooring', 'painting'],
                        'info' => ['finished'],
                        'danger' => ['stopped'],
                    ]),

                BadgeColumn::make('is_active')
                    ->label('Active')
                    ->colors([
                        'success' => fn ($state) => $state === true,
                        'danger' => fn ($state) => $state === false,
                    ])
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([]) // No trashed filters
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),  // normal delete (not soft delete)
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(), // normal bulk delete
                ]),
            ]);
    }
}
