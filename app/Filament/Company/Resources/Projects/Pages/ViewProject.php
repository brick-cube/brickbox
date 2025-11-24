<?php

namespace App\Filament\Company\Resources\Projects\Pages;

use App\Filament\Company\Resources\Projects\ProjectResource;
use App\Filament\Company\Resources\Projects\RelationManagers\SiteTransactionsRelationManager;
use App\Filament\Company\Resources\Projects\Widgets\SummaryStats;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected bool $hasRelationManager = true;

    /** show relation section as full width, not tabs */
    protected bool $hasCombinedRelationManagerTabs = false;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SummaryStats::class,
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            SiteTransactionsRelationManager::class,
        ];
    }
}
