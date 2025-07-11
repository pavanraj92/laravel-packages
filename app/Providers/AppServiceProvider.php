<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Custom Blade directive for admin permission checks
        Blade::if('admincan', function ($permission) {
            $hasRolePackage = file_exists(base_path('vendor/admin/admin_role_permissions'));
            $user = auth('admin')->user();
            return (!$hasRolePackage && $user)
                || ($hasRolePackage && $user && method_exists($user, 'hasPermission') && $user->hasPermission($permission));
        });
        try {
            if (Schema::hasTable('settings')) {
                $settings = \DB::table('settings')->get();
            }
        } catch (\Throwable $e) {
            \Log::warning("Skipping DB setup: " . $e->getMessage());
        }
    }
}
