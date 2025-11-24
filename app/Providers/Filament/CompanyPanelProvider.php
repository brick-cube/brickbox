<?php

namespace App\Providers\Filament;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;

class CompanyPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {

        return $panel
            ->id('company')
            ->brandName(config('app.name'))
            ->brandLogo(null)
            ->path('company')
            ->login(false)
            ->authGuard('web')
            ->colors([
                'primary' => '#4997D3',
            ])
            ->discoverResources(in: app_path('Filament/Company/Resources'), for: 'App\Filament\Company\Resources')
            ->discoverPages(in: app_path('Filament/Company/Pages'), for: 'App\Filament\Company\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Company/Widgets'), for: 'App\Filament\Company\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\TenantMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {
        Filament::serving(function () {
            $panel = Filament::getCurrentPanel();

            if (! $panel || $panel->getId() !== 'company') return;

            $tenant = session('tenant');
            if (! $tenant) return;

            $panel->brandName($tenant->name);
            $panel->brandLogoHeight('32px');
            $panel->brandLogo(
                $tenant->logo ? asset('storage/' . ltrim($tenant->logo, '/')) : null
            );

            Filament::registerRenderHook(
                'panels::head.end',
                fn() => $tenant?->color
                    ? "<style>
                    :root {
                        --primary-50: {$tenant->color}20;
                        --primary-100: {$tenant->color}33;
                        --primary-200: {$tenant->color}66;
                        --primary-300: {$tenant->color}80;
                        --primary-400: {$tenant->color}99;
                        --primary-500: {$tenant->color};
                        --primary-600: {$tenant->color};
                        --primary-700: {$tenant->color};
                        --primary-800: {$tenant->color};
                        --primary-900: {$tenant->color};
                    }
                </style>"
                : ''
            );
        });
    }
}
