<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // Custom Blade directive for admin permission checks (single or multiple)
        Blade::if('admincan', function ($permissions) {
            $hasRolePackage = file_exists(base_path('vendor/admin/admin_role_permissions'));
            $admin = auth('admin')->user();
            if (!$admin) {
                return false;
            }
            $permissionArray = explode('|', $permissions);
            foreach ($permissionArray as $permission) {
                $permission = trim($permission);
                if (!$hasRolePackage) {
                    return true; // if no role-permission package, allow access
                }
                if (method_exists($admin, 'hasPermission') && $admin->hasPermission($permission)) {
                    return true; // allow if user has at least one
                }
            }
            return false; // deny if none matched
        });

        try {
            DB::connection()->getPdo();
            if (Schema::hasTable('settings')) {
                DB::table('settings')->get();
            }
        } catch (\Throwable $e) {
            Log::warning("Skipping DB setup: " . $e->getMessage());
        }
    }
}
