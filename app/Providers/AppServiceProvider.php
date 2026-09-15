<?php

namespace App\Providers;

use App\Models\Navigation;
use App\Modules\ModuleRegistry;
use App\Modules\Workshop\WorkshopModule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ModuleRegistry::class, function () {
            $registry = new ModuleRegistry();

            // Daftarkan modul bisnis dengan 3 parameter: (key, label, class)
            $registry->register('workshop_registration', 'Registrasi Bengkel', WorkshopModule::class);

            return $registry;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('frontend.*', function ($view) {
            $navigations = [];
            
            try {
                if (Schema::hasTable('navigations')) {
                    $navigations = Navigation::with(['page', 'children.page'])
                        ->whereNull('parent_id')
                        ->where('is_active', true)
                        ->orderBy('order', 'asc')
                        ->get();
                }
            } catch (\Exception $e) {
                // Mencegah error crash saat database atau tabel belum siap/dimigrasi
            }

            $view->with('globalNavigations', $navigations);
        });
    }
}