<?php

namespace App\Filament\Company\Resources\Projects\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Project;

class SummaryStats extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '10s';

    public ?Project $record = null;

    protected function getStats(): array
    {
        $project = $this->record;

        return [
            Stat::make('Total Receipts', '₹ ' . number_format(
                $project->siteTransactions()
                    ->where('category', 'receipt')
                    ->sum('expense'),
                2
            ))
                ->color('success')
                ->description('All money received')
                ->chart([7, 12, 15, 18, 22])
                ->icon('heroicon-o-banknotes'),

            Stat::make('Material Cost', '₹ ' . number_format(
                $project->siteTransactions()
                    ->where('category', 'purchase')
                    ->sum('total_price'),
                2
            ))
                ->color('warning')
                ->description('Cost for materials')
                ->chart([10, 14, 11, 20, 16])
                ->icon('heroicon-o-shopping-cart'),

            Stat::make('Labour Cost', '₹ ' . number_format(
                $project->siteTransactions()
                    ->where('category', 'labour')
                    ->sum('expense'),
                2
            ))
                ->color('danger')
                ->description('Total labour expenses')
                ->chart([4, 8, 6, 12, 10])
                ->icon('heroicon-o-user-group'),

            Stat::make('Workers', $project->attendances()
                ->distinct('employee_id')
                ->count('employee_id')
            )
                ->color('info')
                ->description('Total unique workers')
                ->chart([3, 5, 4, 9, 6])
                ->icon('heroicon-o-identification'),
        ];
    }
}
