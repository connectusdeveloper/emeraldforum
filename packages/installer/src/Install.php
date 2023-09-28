<?php

namespace Tecdiary\Installer;

use App\Helpers\Env;
use Illuminate\Http\Request;
use App\Models\{Permission, Role, Setting, User};
use Illuminate\Support\Facades\{DB, File, Hash, Http, Storage};

class Install
{
    public static function createDemoData()
    {
        set_time_limit(300);
        try {
            $demoData = Storage::disk('local')->get('demo.sql');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $data = self::dbTransaction($demoData);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return $data;
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function createEnv()
    {
        File::copy(base_path('.env.example'), base_path('.env'));
        Env::update(['APP_URL' => url('/')], false);
    }

    public static function createTables(Request $request, $data, $installation_id = null)
    {
        $result = self::isDbValid($data);
        if (!$result || false == $result['success']) {
            return $result;
        }

        set_time_limit(300);
        $data['license']['id'] = '13289844';
        $data['license']['version'] = '2.0';
        $data['license']['type'] = 'install';

        $result = ['success' => false, 'message' => ''];
        $url = 'https://api.tecdiary.net/v1/dbtables';
        $response = Http::withoutVerifying()->acceptJson()->post($url, $data['license']);
        if ($response->ok()) {
            $sql = $response->json();
            if (empty($sql['database'])) {
                $result = ['success' => false, 'message' => $sql['database'] ?? 'No database received from install server, please check with developer.'];
            } else {
                $result = self::dbTransaction($sql['database']);
            }
            Storage::disk('local')->put('keys.json', '{ "sim": "' . $data['license']['code'] . '" }');
        } else {
            $result = ['success' => false, 'message' => $response->json()];
        }

        return $result;
    }

    public static function createUser($userData)
    {
        $user = $userData;
        $user['password'] = Hash::make($user['password']);
        $user = User::create($user);
        $super_role = Role::create(['name' => 'super']);
        $admin_role = Role::create(['name' => 'admin']);
        $member_role = Role::create(['name' => 'member']);
        $user->assignRole($super_role);
        $user->email_verified_at = now();
        $user->saveQuietly();

        // Add Settings
        Setting::create(['tec_key' => 'allow_delete', 'tec_value' => '10']);
        Setting::create(['tec_key' => 'allowed_files', 'tec_value' => 'jpg,jpeg,png,pdf']);
        Setting::create(['tec_key' => 'allowed_upload', 'tec_value' => '1']);
        Setting::create(['tec_key' => 'banned_words', 'tec_value' => 'fuck, fucking']);
        Setting::create(['tec_key' => 'bottom_ad_code', 'tec_value' => '<div class="w-full py-8 rounded-md bg-gray-200 dark:bg-gray-700 flex items-center justify-center">Bottom ad position</div>']);
        Setting::create(['tec_key' => 'contact_email', 'tec_value' => 'noreply@example.com']);
        Setting::create(['tec_key' => 'contact_page', 'tec_value' => 'Please fill in the form to send us a message.']);
        Setting::create(['tec_key' => 'description', 'tec_value' => 'Simple Forum is the perfect solution for private forums. It offers secure and customizable spaces for businesses to engage with their clients. It can also be used as a general-purpose forum or bulletin board.']);
        Setting::create(['tec_key' => 'email', 'tec_value' => 'noreply@tecdiary.com']);
        Setting::create(['tec_key' => 'faqs', 'tec_value' => '1']);
        Setting::create(['tec_key' => 'flag_option', 'tec_value' => '1']);
        Setting::create(['tec_key' => 'footer_code', 'tec_value' => '<script>console.log(\'You can add your analytic code here.\');<script>']);
        Setting::create(['tec_key' => 'knowledgebase', 'tec_value' => '1']);
        Setting::create(['tec_key' => 'member_page', 'tec_value' => '1']);
        Setting::create(['tec_key' => 'mode', 'tec_value' => 'Public']);
        Setting::create(['tec_key' => 'name', 'tec_value' => 'Simple Forum']);
        Setting::create(['tec_key' => 'notifications', 'tec_value' => 'super']);
        Setting::create(['tec_key' => 'pages', 'tec_value' => '1']);
        Setting::create(['tec_key' => 'articles', 'tec_value' => '1']);
        Setting::create(['tec_key' => 'registration', 'tec_value' => '1']);
        Setting::create(['tec_key' => 'replacement_word', 'tec_value' => '*ban*']);
        Setting::create(['tec_key' => 'review_option', 'tec_value' => '10']);
        Setting::create(['tec_key' => 'rtl', 'tec_value' => '0']);
        Setting::create(['tec_key' => 'search_backdrop', 'tec_value' => '1']);
        Setting::create(['tec_key' => 'search_length', 'tec_value' => '2']);
        Setting::create(['tec_key' => 'short_name', 'tec_value' => 'TSF']);
        Setting::create(['tec_key' => 'sidebar_ad_code', 'tec_value' => '<div class="w-full h-80 rounded-md bg-gray-200 dark:bg-gray-700 flex items-center justify-center">Sidebar ad position</div>']);
        Setting::create(['tec_key' => 'signature', 'tec_value' => '1']);
        Setting::create(['tec_key' => 'sticky_sidebar', 'tec_value' => '0']);
        Setting::create(['tec_key' => 'timezone', 'tec_value' => 'Asia/Kuala_Lumpur']);
        Setting::create(['tec_key' => 'title', 'tec_value' => 'Simple Forum']);
        Setting::create(['tec_key' => 'top_ad_code', 'tec_value' => '<div class="w-full py-8 rounded-md bg-gray-200 dark:bg-gray-700 flex items-center justify-center">Top ad position</div>']);
        Setting::create(['tec_key' => 'top_members', 'tec_value' => '1']);
        Setting::create(['tec_key' => 'trending_threads', 'tec_value' => '1']);
        Setting::create(['tec_key' => 'voting', 'tec_value' => '0']);
        Setting::create(['tec_key' => 'contact', 'tec_value' => '1']);
        Setting::create(['tec_key' => 'per_page', 'tec_value' => '10']);
        Setting::create(['tec_key' => 'language', 'tec_value' => 'en']);
        Setting::create(['tec_key' => 'editor', 'tec_value' => 'html']);

        // Permissions
        Permission::create(['name' => 'read-threads', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-faqs', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-faqs', 'guard_name' => 'web']);
        Permission::create(['name' => 'create-faqs', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-faqs', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-knowledgebase', 'guard_name' => 'web']);
        Permission::create(['name' => 'create-knowledgebase', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-knowledgebase', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-knowledgebase', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-replies', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-replies', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-replies', 'guard_name' => 'web']);
        Permission::create(['name' => 'create-replies', 'guard_name' => 'web']);
        Permission::create(['name' => 'create-threads', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-threads', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-threads', 'guard_name' => 'web']);
        Permission::create(['name' => 'meta-tags', 'guard_name' => 'web']);
        Permission::create(['name' => 'assign-badges', 'guard_name' => 'web']);
        Permission::create(['name' => 'review', 'guard_name' => 'web']);
        Permission::create(['name' => 'approve-threads', 'guard_name' => 'web']);
        Permission::create(['name' => 'settings', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-roles', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-roles', 'guard_name' => 'web']);
        Permission::create(['name' => 'create-roles', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-roles', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-users', 'guard_name' => 'web']);
        Permission::create(['name' => 'create-users', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-users', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-users', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-custom-fields', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-custom-fields', 'guard_name' => 'web']);
        Permission::create(['name' => 'create-custom-fields', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-custom-fields', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-categories', 'guard_name' => 'web']);
        Permission::create(['name' => 'create-categories', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-categories', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-categories', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-badges', 'guard_name' => 'web']);
        Permission::create(['name' => 'create-badges', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-badges', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-badges', 'guard_name' => 'web']);
        Permission::create(['name' => 'read-pages', 'guard_name' => 'web']);
        Permission::create(['name' => 'create-pages', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-pages', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-pages', 'guard_name' => 'web']);

        $admin_permissions = [
            'read-threads', 'update-faqs', 'create-faqs', 'read-faqs', 'read-knowledgebase', 'create-knowledgebase',
            'update-knowledgebase', 'update-replies', 'read-replies', 'create-replies', 'create-threads', 'update-threads',
            'delete-threads', 'meta-tags', 'assign-badges', 'review', 'approve-threads', 'create-roles', 'read-roles',
            'read-users', 'create-users', 'update-custom-fields', 'create-custom-fields', 'read-custom-fields',
            'read-categories', 'create-categories', 'update-categories', 'create-badges', 'update-badges',
            'read-badges', 'read-pages', 'create-pages', 'update-pages',
        ];
        $admin_role->syncPermissions($admin_permissions);

        $member_permissions = ['read-threads', 'read-replies', 'create-threads', 'create-replies'];
        $member_role->syncPermissions($member_permissions);
    }

    public static function finalize()
    {
        Env::update(['APP_INSTALLED' => 'true', 'APP_DEBUG' => 'false', 'APP_URL' => url('/'), 'SESSION_DRIVER' => 'database'], false);
        return true;
    }

    public static function isDbValid($data)
    {
        if (!File::exists(base_path('.env'))) {
            self::createEnv();
        }

        Env::update([
            'DB_HOST'     => $data['database']['host'],
            'DB_PORT'     => $data['database']['port'],
            'DB_DATABASE' => $data['database']['name'],
            'DB_USERNAME' => $data['database']['user'],
            'DB_PASSWORD' => $data['database']['password'] ?? '',
            'DB_SOCKET'   => $data['database']['socket'] ?? '',
        ], false);

        $result = false;
        config(['database.default' => 'mysql']);
        config(['database.connections.mysql.host' => $data['database']['host']]);
        config(['database.connections.mysql.port' => $data['database']['port']]);
        config(['database.connections.mysql.database' => $data['database']['name']]);
        config(['database.connections.mysql.username' => $data['database']['user']]);
        config(['database.connections.mysql.password' => $data['database']['password'] ?? '']);
        config(['database.connections.mysql.unix_socket' => $data['database']['socket'] ?? '']);

        try {
            DB::reconnect();
            DB::connection()->getPdo();
            if (DB::connection()->getDatabaseName()) {
                $result = ['success' => true, 'message' => 'Yes! Successfully connected to the DB: ' . DB::connection()->getDatabaseName()];
            } else {
                $result = ['success' => false, 'message' => 'DB Error: Unable to connect!'];
            }
        } catch (\Exception $e) {
            $result = ['success' => false, 'message' => 'DB Error: ' . $e->getMessage()];
        }

        return $result;
    }

    public static function registerLicense(Request $request, $license)
    {
        $license['id'] = '13289844';
        $license['path'] = app_path();
        $license['host'] = $request->url();
        $license['domain'] = $request->root();
        $license['full_path'] = public_path();
        $license['referer'] = $request->path();

        $url = 'https://api.tecdiary.net/v1/license';
        return Http::withoutVerifying()->acceptJson()->post($url, $license)->json();
    }

    public static function requirements()
    {
        $requirements = [];

        if (version_compare(phpversion(), '8.1', '<')) {
            $requirements[] = 'PHP 8.1 is required! Your PHP version is ' . phpversion();
        }

        if (ini_get('safe_mode')) {
            $requirements[] = 'Safe Mode needs to be disabled!';
        }

        if (ini_get('register_globals')) {
            $requirements[] = 'Register Globals needs to be disabled!';
        }

        if (ini_get('magic_quotes_gpc')) {
            $requirements[] = 'Magic Quotes needs to be disabled!';
        }

        if (!ini_get('file_uploads')) {
            $requirements[] = 'File Uploads needs to be enabled!';
        }

        if (!class_exists('PDO')) {
            $requirements[] = 'MySQL PDO extension needs to be loaded!';
        }

        if (!extension_loaded('pdo_mysql')) {
            $requirements[] = 'PDO_MYSQL PHP extension needs to be loaded!';
        }

        if (!extension_loaded('openssl')) {
            $requirements[] = 'OpenSSL PHP extension needs to be loaded!';
        }

        if (!extension_loaded('tokenizer')) {
            $requirements[] = 'Tokenizer PHP extension needs to be loaded!';
        }

        if (!extension_loaded('mbstring')) {
            $requirements[] = 'Mbstring PHP extension needs to be loaded!';
        }

        if (!extension_loaded('curl')) {
            $requirements[] = 'cURL PHP extension needs to be loaded!';
        }

        if (!extension_loaded('ctype')) {
            $requirements[] = 'Ctype PHP extension needs to be loaded!';
        }

        if (!extension_loaded('xml')) {
            $requirements[] = 'XML PHP extension needs to be loaded!';
        }

        if (!extension_loaded('json')) {
            $requirements[] = 'JSON PHP extension needs to be loaded!';
        }

        if (!extension_loaded('zip')) {
            $requirements[] = 'ZIP PHP extension needs to be loaded!';
        }

        if (!ini_get('allow_url_fopen')) {
            $requirements[] = 'PHP allow_url_fopen config needs to be enabled!';
        }

        if (!is_writable(base_path('storage/app'))) {
            $requirements[] = 'storage/app directory needs to be writable!';
        }

        if (!is_writable(base_path('storage/framework'))) {
            $requirements[] = 'storage/framework directory needs to be writable!';
        }

        if (!is_writable(base_path('storage/logs'))) {
            $requirements[] = 'storage/logs directory needs to be writable!';
        }

        return $requirements;
    }

    public static function updateMailSettings($data)
    {
        Env::update([
            'MAIL_MAILER'     => $data['mail']['driver'],
            'MAIL_HOST'       => $data['mail']['host'],
            'MAIL_PORT'       => $data['mail']['port'],
            'MAIL_USERNAME'   => $data['mail']['username'],
            'MAIL_PASSWORD'   => $data['mail']['password'] ?? '',
            'MAIL_PATH'       => $data['mail']['path'] ?? '',
            'MAIL_ENCRYPTION' => $data['mail']['encryption'] ?? 'tls',
        ], false);
    }

    protected static function dbTransaction($sql)
    {
        try {
            $expression = DB::raw($sql);
            DB::unprepared($expression->getValue(DB::connection()->getQueryGrammar()));
            $result = ['success' => true, 'message' => 'Database tables are created.'];
        } catch (\Exception $e) {
            $result = ['success' => false, 'SQL: unable to create tables, ' . $e->getMessage()];
        }

        return $result;
    }
}
