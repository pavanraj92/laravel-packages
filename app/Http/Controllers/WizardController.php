<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator as FacadesValidator;


class WizardController extends Controller
{
    /**
     * Display a list of installed packages.
     *
     * @return \Illuminate\View\View
     */

    public function index()
    {
        $displayNameMap = config('constants.package_display_names');
        $packageInfoMap = config('constants.package_info');

        $selectedIndustry = Session::get('industry');
        $industryPackages = config('constants.industry_packages.' . $selectedIndustry, []);

        $packageList = [];

        foreach ($industryPackages as $fullPackageName) {
            // Split vendor/package
            [$vendorName, $packageName] = explode('/', $fullPackageName);

            // Skip excluded package
            if ($vendorName === 'admin' && $packageName === 'admin_auth') {
                continue;
            }

            // Get display name from config map
            $displayName = $displayNameMap[$fullPackageName] ?? $packageName;
            $packageInfo = $packageInfoMap[$fullPackageName] ?? [];

            // You can optionally load extra info if needed, or leave it blank
            $packageList[] = [
                'vendor'       => $vendorName,
                'name'         => $packageName,
                'info'         => $packageInfo,
                'display_name' => $displayName,
            ];
        }

        return view('wizard.index', compact('packageList'));
    }


    // 1. Store industry in session
    public function storeIndustry(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'industry' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'success', 'message' => $validator->messages()->first()], 400);
        }

        //check system configuration for valid PHP version and Laravel version
        $phpVersion = phpversion();
        $laravelVersion = app()->version();

        if (version_compare($phpVersion, '7.4', '<')) {
            return response()->json(['status' => 'error', 'message' => 'PHP version must be 7.4 or higher.'], 400);
        }

        if (version_compare($laravelVersion, '8.0', '<')) {
            return response()->json(['status' => 'error', 'message' => 'Laravel version must be 8.0 or higher.'], 400);
        }

        Session::put('industry', $request->industry);
        return response()->json(['status' => 'success']);
    }

    // 2. Create database in MySQL
    public function createDatabase(Request $request)
    {
        $request->merge([
            'db_name' => Str::slug($request->db_name, '_')
        ]);

        $validator = FacadesValidator::make($request->all(), [
            'website_name' => 'required|string|min:2|max:64',
            'db_name' => 'required|string|min:2|max:64',
            'db_user' => 'required|string|min:2|max:64',
            'db_password' => 'nullable|string|min:8|max:64|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{2,64}$/'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()->first()
            ], 400);
        }

        $websiteName = $request->website_name;
        $dbName = $request->db_name;
        $user = $request->db_user;
        $password = $request->db_password ?? '';

        try {
            // Check if database already exists
            $existingDb = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbName]);

            if (!empty($existingDb)) {
                // Check if 'admins' table exists in that DB
                $adminsTable = DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'admins'", [$dbName]);
                if (!empty($adminsTable)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Database and tables already exist.'
                    ], 400);
                }
            }

            // Store in session
            Session::put('db', [
                'websiteName' => $websiteName,
                'dbName' => $dbName,
                'dbUser' => $user,
                'dbPassword' => $password
            ]);

            // Execute DB creation and user privileges
            DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            if (!empty($password)) {
                DB::statement("CREATE USER IF NOT EXISTS '$user'@'%' IDENTIFIED BY ?", [$password]);
            } else {
                DB::statement("CREATE USER IF NOT EXISTS '$user'@'%'");
            }

            DB::statement("GRANT ALL PRIVILEGES ON `$dbName`.* TO '$user'@'%'");
            DB::statement("FLUSH PRIVILEGES");

            return response()->json([
                'status' => 'success',
                'message' => 'Database created successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // 3. Store packages in session
    public function storePackages(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'packages' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()->first()
            ], 422);
        }

        $userSelectedPackages = $request->packages;
        $defaultPackage = ['admin/admin_auth', 'admin/settings'];

        // Always include default package, but avoid duplicates
        $allPackages = array_unique(array_merge($defaultPackage, $userSelectedPackages));
        Session::put('packages', $allPackages);

        $industryName = Session::get('industry');

        $missingPackages = [];
        foreach ($userSelectedPackages as $fullPackageName) {
            $composerCheck = shell_exec("composer show {$fullPackageName} 2>&1");
            if (strpos($composerCheck, 'not found') !== false || strpos($composerCheck, 'No package') !== false) {
                $missingPackages[] = $fullPackageName;
            }
        }


        if (!empty($missingPackages)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing packages: ' . implode(', ', $missingPackages)
            ], 400);
        }

        try {
            set_time_limit(0); // Extend execution time
            chdir(base_path());

            // Build composer require command including default package
            $packageString = implode(' ', array_map(fn($pkg) => "{$pkg}:@dev", $allPackages));
            $command = "composer require {$packageString}";

            ob_start();
            passthru($command, $exitCode);
            $output = ob_get_clean();

            if ($exitCode === 0) {
                // Artisan::call('migrate', ['--force' => true]);
                $message = "✅ All selected packages installed successfully.";
            } else {
                $message = "❌ Composer failed. Output:\n" . $output;
                return response()->json([
                    'status' => 'error',
                    'message' => $message
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "❌ Exception: " . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'packages' => $userSelectedPackages,
            'industry' => $industryName
        ]);
    }

    // 4. Create admin table/model/migrate and store admin credentials
    public function storeAdmin(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'admin_email' => [
                'required',
                'email',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            'admin_password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).+$/',
            ],
        ], [
            'admin_password.regex' => 'Password must be at least 8 characters and include at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'success', 'message' => $validator->messages()->first()], 400);
        }


        // Update mysql connection config
        $connection = config('database.connections.mysql');
        $connection['database'] = Session::get('db.dbName');
        config(['database.connections.mysql' => $connection]);
        // Purge and reconnect to use new database
        DB::purge('mysql');
        DB::reconnect('mysql');

        // migrate the database
        Artisan::call('migrate', ['--force' => true]);

        // run the package seeder
        // Only run the seeder if the admin/users package is installed
        if (is_dir(base_path('vendor/admin/users'))) {
            Artisan::call('db:seed', [
            '--class' => 'Admin\Users\Database\Seeders\\SeedUserRolesSeeder',
            '--force' => true,
            ]);
        }

        if (is_dir(base_path('vendor/admin/settings'))) {
            Artisan::call('db:seed', [
            '--class' => 'Admin\Settings\Database\Seeders\\SettingSeeder',
            '--force' => true,
            ]);
        }


        // Use the correct connection for schema and queries
        $schema = Schema::connection('mysql');
        $db = DB::connection('mysql');

        // Check if the database is set correctly
        $currentDb = DB::connection()->getDatabaseName();

        if ($currentDb !== Session::get('db.dbName')) {
            return response()->json(['status' => 'error', 'message' => 'Database connection is not set correctly.'], 500);
        }

        // Check if admin already exists
        $existingAdmin = $db->table('admins')->where('email', $request->admin_email)->first();

        if ($existingAdmin) {
            return response()->json(['status' => 'error', 'message' => 'Admin with this email already exists.'], 400);
        }

        $websiteName = Session::get('db.websiteName');
        $websiteSlug = Str::slug($websiteName);

        // Store admin using Eloquent (if model exists), otherwise use Query Builder       
        $adminId = DB::table('admins')->insert([
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'website_name' => $websiteName,
            'website_slug' => $websiteSlug,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (is_dir(base_path('vendor/admin/admin_role_permissions'))) {
            Artisan::call('db:seed', [
                '--class' => 'Admin\AdminRolePermissions\Database\Seeders\\AdminRolePermissionDatabaseSeeder',
                '--force' => true,
            ]);
        }

        $this->updateEnvDbName(Session::get('db.dbName'));
        // Artisan::call('optimize:clear');

        // forgot all session data
        Session::flush();
        Session::forget(['industry', 'db', 'packages']);

        $loginUrl = $request->getSchemeAndHttpHost() . route('thankyou', [], false);

        return response()->json(['status' => 'success', 'admin_id' => $adminId, 'redirect_url' => $loginUrl]);
    }

    public function updateEnvDbName($newDbName)
    {
        $envPath = base_path('.env');
        $env = file_get_contents($envPath);

        // Replace the current DB_DATABASE value
        $env = preg_replace('/^DB_DATABASE=.*$/m', 'DB_DATABASE=' . $newDbName, $env);

        // update  DB_USERNAME and DB_PASSWORD if they are set in the session
        $dbUser = Session::get('db.dbUser');
        $dbPassword = Session::get('db.dbPassword');
        if ($dbUser) {
            $env = preg_replace('/^DB_USERNAME=.*$/m', 'DB_USERNAME=' . $dbUser, $env);
        }
        if ($dbPassword) {
            $env = preg_replace('/^DB_PASSWORD=.*$/m', 'DB_PASSWORD=' . $dbPassword, $env);
        }

        file_put_contents($envPath, $env);

        // Optionally, reload config cache
        // Artisan::call('config:clear');
        // Artisan::call('config:cache');
        // Artisan::call('cache:clear');
        // Artisan::call('view:clear');
        // Artisan::call('route:clear');

        Artisan::call('optimize:clear');
    }

    public function viewThankYouPage()
    {
        return view('thankyou');
    }

    /**
     * forgot all session data
     * @return void
     */
    public function clearSession()
    {
        // Clear all session data        
        Session::flush();
        return redirect()->route('wizard-install')->with('success', 'Session cleared successfully.');
    }

    /**
     * Execute the migration command
     * @return \Illuminate\Http\JsonResponse
     */
    public function runMigration()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            // Optionally, reload config cache
            Artisan::call('config:clear');
            Artisan::call('config:cache');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('optimize:clear');

            return response()->json(['status' => 'success', 'message' => 'Migration executed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    protected function setEnvValue($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $envContents = file_get_contents($path);

            if (preg_match("/^{$key}=.*/m", $envContents)) {
                // Key exists, replace the line
                $envContents = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$value}",
                    $envContents
                );
            } else {
                // Key does not exist, append it
                $envContents .= "\n{$key}={$value}";
            }

            file_put_contents($path, $envContents);
        }
    }

    public function checkPackageInstalled(Request $request)
    {
        $package = $request->package;
        [$vendor, $name] = explode('/', $package);

        $vendorPath = base_path("vendor/{$vendor}/{$name}");

        if (is_dir($vendorPath)) {
            return response()->json(['status' => 'installed']);
        } else {
            return response()->json(['status' => 'not_installed']);
        }
    }
}
