<?php

namespace App\Filament\Company\Resources\Projects\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry as ComponentsTextEntry;
use Filament\Resources\RelationManagers\RelationManager;

use Filament\Schemas\Schema;

use Filament\Schemas\Components\Section;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class SiteTransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'siteTransactions';
    protected static ?string $title = 'Site Transactions';
    protected static bool $showOnView = true;
    protected static bool $showCreateActionOnView = true;
    protected static bool $canCreate = true;


    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Transaction Details')
                ->columnSpanFull()
                ->columns(2)
                ->schema([

                    DatePicker::make('transaction_date')
                        ->label('Transaction Date')
                        ->default(today())
                        ->maxDate(today())
                        ->required(),

                    Select::make('type')
                        ->options([
                            'contract' => 'Contract',
                            'sub work' => 'Sub Work',
                            'coolie' => 'Coolie',
                        ])
                        ->default('contract')
                        ->required(),

                    Select::make('category')
                        ->options([
                            'receipt' => 'Receipt',
                            'expense' => 'Expense',
                            'labour' => 'Labour',
                            'purchase' => 'Purchase',
                        ])
                        ->default('receipt')
                        ->required(),

                    Select::make('transaction_type')
                        ->options([
                            'expense' => 'Expense',
                            'receipt' => 'Receipt',
                        ])
                        ->default('expense')
                        ->required(),

                    Textarea::make('details')->nullable(),
                    Textarea::make('description')->nullable(),

                    TextInput::make('rate')
                        ->numeric()
                        ->reactive()
                        ->afterStateUpdated(
                            fn($state, $set, $get) =>
                            $set('total_price', ($state * $get('quantity')) ?: null)
                        ),

                    TextInput::make('quantity')
                        ->numeric()
                        ->reactive()
                        ->afterStateUpdated(
                            fn($state, $set, $get) =>
                            $set('total_price', ($state * $get('rate')) ?: null)
                        ),

                    TextInput::make('total_price')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(true),

                    TextInput::make('expense')
                        ->numeric()
                        ->required()
                ]),
        ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            ComponentsTextEntry::make('transaction_date')->date()->label('Date'),
            ComponentsTextEntry::make('type')->label('Type'),
            ComponentsTextEntry::make('category')->label('Category'),
            ComponentsTextEntry::make('description')->label('Description'),
            ComponentsTextEntry::make('expense')->label('Expense'),
            ComponentsTextEntry::make('total_price')->label('Total'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_date')
                    ->date()
                    ->sortable()
                    ->searchable(),

                BadgeColumn::make('type')
                    ->colors([
                        'success' => 'contract',
                        'warning' => 'sub work',
                        'info' => 'coolie',
                    ])
                    ->searchable(),

                BadgeColumn::make('category')
                    ->colors([
                        'info' => 'receipt',
                        'danger' => 'expense',
                        'warning' => 'labour',
                        'success' => 'purchase',
                    ])
                    ->searchable(),

                TextColumn::make('details')
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('description')
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('expense')
                    ->money('INR')
                    ->sortable(),

                TextColumn::make('total_price')
                    ->money('INR')
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'contract' => 'Contract',
                        'sub work' => 'Sub Work',
                        'coolie' => 'Coolie',
                    ]),

                SelectFilter::make('category')
                    ->options([
                        'receipt' => 'Receipt',
                        'expense' => 'Expense',
                        'labour' => 'Labour',
                        'purchase' => 'Purchase',
                    ]),

                SelectFilter::make('transaction_type')
                    ->options([
                        'expense' => 'Expense',
                        'receipt' => 'Receipt',
                    ]),

                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('to'),
                    ])
                    ->query(function ($query, $data) {
                        return $query
                            ->when($data['from'] ?? null, fn($q, $v) =>
                            $q->whereDate('transaction_date', '>=', $v))
                            ->when($data['to'] ?? null, fn($q, $v) =>
                            $q->whereDate('transaction_date', '<=', $v));
                    }),
            ])

            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label('Add Transaction')
                    ->modalHeading('Add Transaction')
                    ->relationship(fn($livewire) => $livewire->getRelationship()) // required for view page
                    ->mutateFormDataUsing(function ($data, $livewire) {
                        $data['project_id'] = $livewire->ownerRecord->id;
                        $data['company_id'] = \Filament\Facades\Filament::auth()->user()->company_id;
                        return $data;
                    }),
            ])

            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ]);
    }
}
