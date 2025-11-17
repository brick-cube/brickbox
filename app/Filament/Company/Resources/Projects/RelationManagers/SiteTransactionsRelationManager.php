<?php

namespace App\Filament\Company\Resources\Projects\RelationManagers;

use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry as ComponentsTextEntry;
use Filament\Resources\RelationManagers\RelationManager;

use Filament\Schemas\Schema;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class SiteTransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'siteTransactions';
    protected static ?string $title = 'Site Transactions';

    /**
     * Schema-based "form" for Create/Edit (returns Schema)
     */
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('transaction_date')
                ->label('Transaction Date')
                ->required(),

            Select::make('type')
                ->label('Work Type')
                ->options([
                    'contract' => 'Contract',
                    'sub work' => 'Sub Work',
                    'coolie' => 'Coolie',
                ])
                ->required(),

            Select::make('category')
                ->label('Category')
                ->options([
                    'receipt' => 'Receipt',
                    'expense' => 'Expense',
                    'labour' => 'Labour',
                    'purchase' => 'Purchase',
                ])
                ->required(),

            Select::make('transaction_type')
                ->label('Transaction Type')
                ->options([
                    'expense' => 'Expense',
                    'receipt' => 'Receipt',
                ])
                ->required(),

            Textarea::make('details')
                ->label('Details')
                ->nullable(),

            Textarea::make('description')
                ->label('Description')
                ->nullable(),

            TextInput::make('cement_rate')
                ->label('Cement Rate (₹)')
                ->numeric()
                ->reactive()
                ->afterStateUpdated(
                    fn($state, $set, $get) =>
                    $set('cement_total_price', ($state * $get('cement_quantity')) ?: null)
                ),

            TextInput::make('cement_quantity')
                ->label('Cement Quantity')
                ->numeric()
                ->reactive()
                ->afterStateUpdated(
                    fn($state, $set, $get) =>
                    $set('cement_total_price', ($state * $get('cement_rate')) ?: null)
                ),

            TextInput::make('cement_total_price')
                ->label('Cement Total Price')
                ->numeric()
                ->disabled()
                ->dehydrated(true),

            TextInput::make('expense')
                ->label('Expense Amount (₹)')
                ->numeric()
                ->nullable(),
        ])->columns(2);
    }

    /**
     * Schema-based infolist (view record)
     */
    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            ComponentsTextEntry::make('transaction_date')->date()->label('Date'),
            ComponentsTextEntry::make('type')->label('Type'),
            ComponentsTextEntry::make('category')->label('Category'),
            ComponentsTextEntry::make('description')->label('Description'),
            ComponentsTextEntry::make('expense')->label('Expense'),
            ComponentsTextEntry::make('cement_total_price')->label('Cement Total'),
        ]);
    }

    /**
     * Table (uses Filament Tables API). Create action mutates form data to attach project/company.
     */
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_date')
                    ->label('Date')
                    ->date()
                    ->sortable()
                    ->searchable(),

                BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'success' => 'contract',
                        'warning' => 'sub work',
                        'info' => 'coolie',
                    ])
                    ->searchable(),

                BadgeColumn::make('category')
                    ->label('Category')
                    ->colors([
                        'info' => 'receipt',
                        'danger' => 'expense',
                        'warning' => 'labour',
                        'success' => 'purchase',
                    ])
                    ->searchable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('expense')
                    ->label('Expense')
                    ->money('INR')
                    ->sortable(),

                TextColumn::make('cement_total_price')
                    ->label('Cement Total')
                    ->money('INR')
                    ->sortable(),
            ])

            ->filters([

                // Filter by work type
                SelectFilter::make('type')
                    ->label('Work Type')
                    ->options([
                        'contract' => 'Contract',
                        'sub work' => 'Sub Work',
                        'coolie' => 'Coolie',
                    ]),

                // Filter by category
                SelectFilter::make('category')
                    ->label('Category')
                    ->options([
                        'receipt' => 'Receipt',
                        'expense' => 'Expense',
                        'labour' => 'Labour',
                        'purchase' => 'Purchase',
                    ]),

                // Filter by transaction_type (expense / receipt)
                SelectFilter::make('transaction_type')
                    ->label('Transaction Type')
                    ->options([
                        'expense' => 'Expense',
                        'receipt' => 'Receipt',
                    ]),

                // Date Range Filter
                Filter::make('transaction_date')
                    ->label('Date Range')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('to'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn($q, $value) =>
                                $q->whereDate('transaction_date', '>=', $value)
                            )
                            ->when(
                                $data['to'] ?? null,
                                fn($q, $value) =>
                                $q->whereDate('transaction_date', '<=', $value)
                            );
                    }),
            ])

            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data) {
                        $data['project_id'] = $this->ownerRecord->id;
                        $data['company_id'] = Filament::auth()->user()->company_id;
                        return $data;
                    }),
            ])

            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ]);
    }
}
