<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminCanPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $hasRolePackage = file_exists(base_path('vendor/admin/admin_role_permissions'));
        $admin = auth('admin')->user();
        $allowed = (!$hasRolePackage && $admin)
            || ($hasRolePackage && $admin && method_exists($admin, 'hasPermission') && $admin->hasPermission($permission));

        if (!$allowed) {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }
}
