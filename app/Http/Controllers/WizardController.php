<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Log;

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

        $commonPackages = config('constants.common_packages', []);
        $industryPackages = config('constants.industry_packages.' . $selectedIndustry, []);

        $commonPackageList   = $this->buildPackageList($commonPackages, $displayNameMap, $packageInfoMap);
        $industryPackageList = $this->buildPackageList($industryPackages, $displayNameMap, $packageInfoMap);

        // $dependencyMap = [
        //     'admin/admin_role_permissions'  => ['admin/admins'],
        //     'admin/users'                   => ['admin/user_roles'],
        //     'admin/products'                => ['admin/users', 'admin/user_roles', 'admin/brands', 'admin/categories'],
        //     'admin/courses'                 => ['admin/users', 'admin/user_roles', 'admin/categories'],
        //     'admin/quizzes'                 => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/tags', 'admin/courses'],
        //     'admin/product_transactions'    => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/brands', 'admin/products'],
        //     'admin/product_inventories'    => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/brands', 'admin/products'],
        //     'admin/product_reports'         => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/brands', 'admin/products'],
        //     'admin/product_return_refunds'  => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/brands', 'admin/products'],
        //     'admin/course_reports'          => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/courses'],
        //     'admin/course_transactions'     => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/courses'],
        //     'admin/coupons'                 => [
        //         'ecommerce' => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/brands', 'admin/products'],
        //         'education' => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/courses'],
        //     ],
        //     'admin/wishlists'               => [
        //         'ecommerce' => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/brands', 'admin/products'],
        //         'education' => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/courses'],
        //     ],
        //     'admin/ratings'                 => [
        //         'ecommerce' => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/brands', 'admin/products'],
        //         'education' => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/courses'],
        //     ],
        //     'admin/tags'                 => [
        //         'ecommerce' => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/brands', 'admin/products'],
        //         'education' => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/courses'],
        //     ],
        //     'admin/commissions'          => [
        //         'ecommerce' => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/brands', 'admin/products'],
        //         'education' => ['admin/users', 'admin/user_roles', 'admin/categories', 'admin/course_transactions', 'admin/courses'],
        //     ],
        // ];

        return view('wizard.index', compact('commonPackageList', 'industryPackageList', 'selectedIndustry'));
    }


    // Store industry in session
    public function storeIndustry(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'industry' => 'required|string',
            'is_dummy_data' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'success', 'message' => $validator->messages()->first()], 400);
        }

        // Check system configuration for valid PHP version and Laravel version
        $phpVersion = phpversion();
        $laravelVersion = app()->version();

        if (version_compare($phpVersion, '8.2', '<=')) {
            return response()->json(['status' => 'error', 'message' => 'PHP version must be 8.2 or higher.'], 400);
        }

        if (version_compare($laravelVersion, '12.0', '<=')) {
            return response()->json(['status' => 'error', 'message' => 'Laravel version must be 8.0 or higher.'], 400);
        }

        $insertDummy = $request->is_dummy_data ? 1 : 0;

        Session::put('industry', $request->industry);
        Session::put('insert_dummy_data', $insertDummy);

        return response()->json(['status' => 'success']);
    }

    // Create database in MySQL
    public function createDatabase(Request $request)
    {
        $request->merge(['db_name' => Str::slug($request->db_name, '_')]);

        $validator = FacadesValidator::make($request->all(), [
            'website_name'  => 'required|string|min:2|max:64',
            'db_name'       => 'required|string|min:2|max:64',
            'db_user'       => 'required|string|min:2|max:64',
            'db_password'   => 'nullable|string|min:8|max:64|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{2,64}$/'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()->first()
            ], 400);
        }

        $websiteName    = $request->website_name;
        $dbName         = $request->db_name;
        $user           = $request->db_user;
        $password       = $request->db_password ?? '';

        try {
            // Update connection config for root or provided user
            config(['database.connections.mysql.username' => $user]);
            config(['database.connections.mysql.password' => $password]);
            DB::purge('mysql');
            DB::reconnect('mysql');

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

            // Create DB and user
            DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            if (!empty($password)) {
                DB::unprepared("CREATE USER IF NOT EXISTS '$user'@'%' IDENTIFIED BY '$password'");
            } else {
                DB::statement("CREATE USER IF NOT EXISTS '$user'@'%'");
            }

            DB::statement("GRANT ALL PRIVILEGES ON `$dbName`.* TO '$user'@'%'");
            DB::statement("FLUSH PRIVILEGES");

            $this->updateEnvDbName($dbName);

            // Store session for wizard
            Session::put('db', [
                'websiteName'   => $websiteName,
                'dbName'        => $dbName,
                'dbUser'        => $user,
                'dbPassword'    => $password,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Database created successfully. Starting package installation...'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
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
            'is_dummy_data' => 'nullable',
        ], [
            'admin_password.regex' => 'Password must be at least 8 characters and include at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'success', 'message' => $validator->messages()->first()], 400);
        }

        // Point connection to the session DB
        $this->reconnectToSessionDb();

        // migrate the database
        Artisan::call('migrate', ['--force' => true]);
        $isDummyData =  Session::get('insert_dummy_data') ?? 0;

        $websiteName = Session::get('db.websiteName');
        $websiteSlug = Str::slug($websiteName);
        // Store admin using Eloquent (if model exists), otherwise use Query Builder       
        $adminId = DB::table('admins')->insert([
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'website_name' => $websiteName,
            'website_slug' => $websiteSlug,
            'industry' => Session::get('industry', 'ecommerce'),
            'is_dummy_data' => $isDummyData,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // if ($isDummyData) {
        // Run package seeders only if dummy data checkbox is checked
        if (is_dir(base_path('vendor/admin/admin_auth'))) {
            $this->safeArtisanSeed('Admin\AdminAuth\Database\Seeders\\PackageSeeder');
        }
        if (is_dir(base_path('vendor/admin/users'))) {
            $this->safeArtisanSeed('Admin\\Users\\Database\\Seeders\\SeedUserRolesSeeder');
        }
        if (is_dir(base_path('vendor/admin/settings'))) {
            $this->safeArtisanSeed('Admin\\Settings\\Database\\Seeders\\SettingSeeder');
        }

        if(Session::get('insert_dummy_data') == 1){
            if (is_dir(base_path('vendor/admin/users'))) {
                $this->safeArtisanSeed('Admin\\Users\\Database\\Seeders\\UserSeeder');
            }
            if (is_dir(base_path('vendor/admin/categories'))) {
                $this->safeArtisanSeed('Admin\Categories\Database\Seeders\\CategorySeeder');
            }
            if(Session::get('industry') == 'ecommerce'){
                if (is_dir(base_path('vendor/admin/brands'))) {
                    $this->safeArtisanSeed('Admin\Brands\Database\Seeders\\BrandSeeder');
                }
                if (is_dir(base_path('vendor/admin/products'))) {
                    $this->safeArtisanSeed('Admin\Products\Database\Seeders\\ProductSeeder');
                }
            }elseif(Session::get('industry') == 'education'){
                if (is_dir(base_path('vendor/admin/courses'))) {
                    $this->safeArtisanSeed('Admin\Courses\Database\Seeders\\CourseWithLecturesSeeder');
                }
            }
        }
        // if (is_dir(base_path('vendor/admin/emails'))) {
        //     $this->safeArtisanSeed('Admin\Emails\Database\Seeders\\MailDatabaseSeeder');
        // }
        // if (is_dir(base_path('vendor/admin/shipping_charges'))) {
        //     $this->safeArtisanSeed('Admin\ShippingCharges\Database\Seeders\\ShippingZoneSeeder');
        // }
        // }
        // Insert setting
        DB::table('settings')->insert([
            'title' => 'industry',
            'slug' => 'industry',
            'config_value' => Session::get('industry', 'ecommerce'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // if (is_dir(base_path('vendor/admin/admin_role_permissions'))) {
        //     $this->safeArtisanSeed('Admin\AdminRolePermissions\Database\Seeders\\AdminRolePermissionDatabaseSeeder');
        // }

        $this->updateEnvDbName(Session::get('db.dbName'));

        // Clear sessions (kept overall effect, removed redundant forget after flush)
        Session::flush();
        Session::forget(['industry', 'db', 'packages']);
        $loginUrl = $request->getSchemeAndHttpHost() . route('thankyou', [], false);
        return response()->json(['status' => 'success', 'admin_id' => $adminId, 'redirect_url' => $loginUrl]);
    }

    // 3. Store packages in session
    public function storePackages(Request $request)
    {
        // $defaultPackage = ['admin/admin_auth', 'admin/settings'];
        $defaultPackages = [
            'admin/admin_auth',
            'admin/users',
            'admin/user_roles',
            'admin/settings',
        ];

        $industryPackagesMap = [
            'ecommerce' => [
                'admin/categories',
                'admin/brands',
                'admin/products',
            ],
            'education' => [
                'admin/categories',
                'admin/courses',
            ],
        ];

        $industry = Session::get('industry', 'ecommerce');
        $industryPackages = $industryPackagesMap[$industry] ?? [];

        // Final package list
        $packagesToInstall = array_merge($defaultPackages, $industryPackages);

        // Already installed?
        $installedPackages = Session::get('installed_packages', []);
        if (!array_diff($packagesToInstall, $installedPackages)) {
            return response()->json([
                'status'   => 'success',
                'message'  => 'Packages already installed.',
                'packages' => $packagesToInstall,
            ]);
        }

        try {
            set_time_limit(0);
            chdir(base_path());
            [$exitCode, $output] = $this->composerPassthru($packagesToInstall);

            if ($exitCode !== 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Composer failed. Check server logs.\n" . $output
                ], 500);
            }

            Session::put('installed_packages', $packagesToInstall);

            return response()->json([
                'status'  => 'success',
                'message' => 'All packages installed successfully.',
                'packages' => Session::get('installed_packages')
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function flattenPackages(array $packages): array
    {
        $flat = [];
        array_walk_recursive($packages, function ($item) use (&$flat) {
            if (is_string($item)) {
                $flat[] = $item;
            }
        });
        return $flat;
    }

    public function updateEnvDbName($newDbName)
    {
        $envPath = base_path('.env');
        if (!is_file($envPath)) {
            return;
        }

        $env = file_get_contents($envPath);
        $env = preg_replace('/^DB_DATABASE=.*$/m', 'DB_DATABASE=' . $newDbName, $env); // Replace the current DB_DATABASE value

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
        // Artisan::call('storage:link');
        // Artisan::call('view:clear');
        // Artisan::call('route:clear');
        // Artisan::call('optimize:clear');
    }

    public function viewThankYouPage()
    {
        try {
            return view('thankyou');
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * forgot all session data
     * @return void
     */
    public function clearSession()
    {
        // Clear all session data  
        config(['database.connections.mysql.username' => '']);
        config(['database.connections.mysql.password' => '']);
        DB::purge('mysql');
        DB::reconnect('mysql');

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
        if (!file_exists($path)) {
            return;
        }

        $envContents = file_get_contents($path);

        if (preg_match("/^{$key}=.*/m", $envContents)) {
            // Key exists, replace the line
            $envContents = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContents);
        } else {
            $envContents .= "\n{$key}={$value}";
        }

        file_put_contents($path, $envContents);
    }

    public function checkPackageInstalled(Request $request)
    {
        $package = $request->package;
        [$vendor, $name] = explode('/', $package);

        $vendorPath = base_path("vendor/{$vendor}/{$name}");

        return response()->json(['status' => is_dir($vendorPath) ? 'installed' : 'not_installed']);
    }

    /**
     * Map raw package names into displayable payload, skipping admin/admin_auth.
     */
    private function buildPackageList(array $packages, array $displayNameMap, array $packageInfoMap): array
    {
        $list = [];
        foreach ($packages as $fullPackageName) {
            if (!is_string($fullPackageName)) {
                Log::error('Invalid package name type', ['value' => $fullPackageName]);
                continue;
            }

            $parts = explode('/', $fullPackageName);
            if (count($parts) !== 2) {
                Log::error('Malformed package name', ['value' => $fullPackageName]);
                continue;
            }

            [$vendorName, $packageName] = $parts;

            if ($vendorName === 'admin' && $packageName === 'admin_auth') {
                continue;
            }

            $displayName = $displayNameMap[$fullPackageName] ?? $packageName;
            $packageInfo = $packageInfoMap[$fullPackageName] ?? [];

            $list[] = [
                'vendor'       => $vendorName,
                'name'         => $packageName,
                'info'         => $packageInfo,
                'display_name' => $displayName,
            ];
        }
        return $list;
    }

    /**
     * Reconnect the default mysql connection to use the database saved in session.
     */
    private function reconnectToSessionDb(): void
    {
        $connection              = config('database.connections.mysql');
        $connection['database']  = Session::get('db.dbName');
        config(['database.connections.mysql' => $connection]);
        DB::purge('mysql');
        DB::reconnect('mysql');
    }

    /**
     * Safe seeder runner with force flag.
     */
    private function safeArtisanSeed(string $seederClass): void
    {
        Artisan::call('db:seed', [
            '--class' => $seederClass,
            '--force' => true,
        ]);
    }

    /**
     * Execute composer require for multiple packages one by one.
     * Returns [exitCode, output].
     */
    private function composerPassthru(array $packages): array
    {
        $outputMessages = [];
        $exitCode = 0;

        // Detect OS
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        // Full PHP path on Windows (adjust if needed)
        $phpPath = PHP_BINARY;
        // $composerPath = $isWindows ? 'C:\\composer\\composer.phar' : 'composer';
        $composerPath = $this->detectComposerPath();

        foreach ($packages as $pkg) {
            $packageString = "{$pkg}:@dev";
            // $command = $isWindows
            //     ? "\"$phpPath\" \"$composerPath\" require $packageString"
            //     : "$composerPath require $packageString";
              $command = "$composerPath require $packageString --no-interaction";

            $outputMessages[] = "Installing $pkg ...";

            exec($command . " 2>&1", $outputLines, $code);
            $outputMessages = array_merge($outputMessages, $outputLines);

            if ($code !== 0) {
                $exitCode = $code;
                $outputMessages[] = "Failed installing $pkg.";
                break; // stop on first failure
            }
        }

        return [$exitCode, implode("\n", $outputMessages)];
    }

    private function detectComposerPath(): string
    {
        $phpPath = PHP_BINARY;

        // Check if composer is installed globally
        $globalComposer = trim(shell_exec("where composer 2>NUL") ?: shell_exec("which composer"));
        if (!empty($globalComposer)) {
            return "composer"; // no php prefix needed
        }

        // Check for composer.phar in project root
        $projectComposer = base_path('composer.phar');
        if (file_exists($projectComposer)) {
            return "\"$phpPath\" \"$projectComposer\"";
        }

        throw new \RuntimeException("Composer not found. Install Composer globally or place composer.phar in project root.");
    }

}
