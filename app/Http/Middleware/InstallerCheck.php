<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InstallerCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Session::has('db')) {
            return $next($request);
        }

        $adminModulePath = base_path('vendor/admin/admin_auth');

        if (is_dir($adminModulePath)) {
         
            try {
                // ✅ Check if DB connection works
                DB::connection()->getPdo(); // This will throw if DB doesn't exist
    
                // ✅ Then check if 'admins' table exists
                if (Schema::hasTable('admins')) {
                    $slug = DB::table('admins')->latest()->value('website_slug') ?? 'admin';
                    if (auth('admin')->check()) {
                        $dashboardUrl = url("{$slug}/admin/dashboard");
                        return redirect()->to($dashboardUrl);
                    }
    
                    // Redirect to login route
                    $loginUrl = url("{$slug}/admin/login");
                    return redirect()->to($loginUrl);
                }
            } catch (\Throwable $e) {
                // Handle missing DB or other errors gracefully
                \Log::warning("Database not found or unreachable: " . $e->getMessage());
                // Skip redirect and continue to landing page or next middleware
            }
        }

        return $next($request);
    }
}
