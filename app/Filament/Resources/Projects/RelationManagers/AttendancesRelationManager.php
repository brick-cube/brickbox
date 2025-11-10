<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\BulkActionGroup;


class AttendancesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';

    /**
     * The form displayed when creating or editing attendance.
     */
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('employee_id')
                ->relationship('employee', 'name')
                ->label('Employee')
                ->required(),

            DatePicker::make('date')
                ->label('Date')
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
                ->rows(2)
                ->columnSpanFull(),
        ]);
    }

    /**
     * The table displayed inside the Project resource.
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('date')
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Employee')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => fn(string $state): bool => $state === 'present',
                        'danger' => fn(string $state): bool => $state === 'absent',
                        'warning' => fn(string $state): bool => $state === 'leave',
                        'info' => fn(string $state): bool => $state === 'half_day',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'present' => 'Present',
                        'absent' => 'Absent',
                        'leave' => 'Leave',
                        'half_day' => 'Half Day',
                        default => ucfirst($state),
                    }),


                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->toggleable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
