<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
                'gray' => Color::Slate,
                'danger' => Color::Red,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'info' => Color::Sky,
            ])
            // Lebar sidebar diatur lewat method resmi, bukan CSS width,
            // supaya margin konten utama ikut menyesuaikan otomatis.
            ->sidebarWidth('16rem')
            // Menampilkan ikon hamburger di topbar untuk toggle sidebar.
            ->sidebarFullyCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Master Layanan'),
                NavigationGroup::make()
                    ->label('Master CMS'),
            ])
            // Custom CSS (padding sidebar, gap antar grup, border-radius card, background sidebar)
            // sekarang semuanya ditangani di resources/css/filament/admin/theme.css.
            // renderHook JS lama dihapus karena isinya sudah digantikan CSS,
            // dan CSS jauh lebih andal (gak nunggu DOMContentLoaded, gak "kedip"
            // sesaat sebelum JS jalan, dan tetap kepakai walau Livewire re-render DOM).
            //->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
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
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}