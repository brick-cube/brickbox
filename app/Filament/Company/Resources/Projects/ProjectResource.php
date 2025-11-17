<?php

namespace App\Filament\Company\Resources\Projects;

use App\Filament\Company\Resources\Projects\Pages\CreateProject;
use App\Filament\Company\Resources\Projects\Pages\EditProject;
use App\Filament\Company\Resources\Projects\Pages\ListProjects;
use App\Filament\Company\Resources\Projects\Pages\ViewProject;
use App\Filament\Company\Resources\Projects\RelationManagers\SiteTransactionsRelationManager;
use App\Filament\Company\Resources\Projects\Schemas\ProjectForm;
use App\Filament\Company\Resources\Projects\Schemas\ProjectInfolist;
use App\Filament\Company\Resources\Projects\Tables\ProjectsTable;
use App\Models\Project;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Facades\Filament;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ProjectForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectsTable::configure($table);
    }


    public static function getRelations(): array
    {
        return [
            SiteTransactionsRelationManager::class,
        ];
    }


    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('company_id', Filament::auth()->user()->company_id);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'view' => ViewProject::route('/{record}'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
