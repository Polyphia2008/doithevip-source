<?php
$root = realpath(__DIR__);
$envFile = $root . '/.env';
$sqlFile = $root . '/database/shopgame.sql';
$lockFile = $root . '/storage/install.lock';

$requestPath = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/') ?: '/';
if ($requestPath === '/install.php') {
    header('Location: /install', true, 301);
    exit;
}

if (!headers_sent()) {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
}

session_start();

if (empty($_SESSION['install_csrf'])) {
    $_SESSION['install_csrf'] = bin2hex(random_bytes(16));
}

function install_read_env_file($path)
{
    if (!is_file($path)) {
        return [];
    }

    $vars = [];
    $lines = file($path, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue;
        }
        $parts = explode('=', $line, 2);
        $key = trim($parts[0]);
        $value = trim($parts[1] ?? '');
        $value = trim($value, "\"'");
        $vars[$key] = $value;
    }
    return $vars;
}

function install_is_configured($envFile)
{
    $env = install_read_env_file($envFile);
    $required = ['DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'APP_ENCRYPT_KEY'];
    foreach ($required as $key) {
        if (trim($env[$key] ?? '') === '') {
            return false;
        }
    }
    return true;
}

function install_has_tables($mysqli)
{
    $result = $mysqli->query("SHOW TABLES LIKE 'settings'");
    return $result && $result->num_rows > 0;
}

function install_db_tables_exist($envFile)
{
    if (!install_is_configured($envFile)) {
        return false;
    }

    $env = install_read_env_file($envFile);
    $mysqli = @new mysqli(
        $env['DB_HOST'] ?? '127.0.0.1',
        $env['DB_USERNAME'] ?? '',
        $env['DB_PASSWORD'] ?? '',
        $env['DB_DATABASE'] ?? ''
    );

    if ($mysqli->connect_errno) {
        return false;
    }

    $exists = install_has_tables($mysqli);
    $mysqli->close();
    return $exists;
}

function install_is_finished($lockFile, $envFile)
{
    return is_file($lockFile) && install_is_configured($envFile) && install_db_tables_exist($envFile);
}

function install_php_ok()
{
    return version_compare(PHP_VERSION, '8.3.0', '>=');
}

function install_ioncube_ok()
{
    return extension_loaded('ionCube Loader') || function_exists('ioncube_loader_version');
}

function install_ioncube_detail()
{
    if (function_exists('ioncube_loader_version')) {
        return 'v' . ioncube_loader_version();
    }
    if (extension_loaded('ionCube Loader')) {
        return 'Đã bật';
    }
    return 'Chưa bật';
}

function install_get_requirements()
{
    return [
        ['label' => 'PHP >= 8.3', 'ok' => install_php_ok(), 'detail' => 'Hiện tại: ' . PHP_VERSION],
        ['label' => 'ionCube Loader', 'ok' => install_ioncube_ok(), 'detail' => install_ioncube_detail()],
        ['label' => 'mysqli', 'ok' => extension_loaded('mysqli'), 'detail' => extension_loaded('mysqli') ? 'Đã bật' : 'Chưa bật'],
        ['label' => 'openssl', 'ok' => extension_loaded('openssl'), 'detail' => extension_loaded('openssl') ? 'Đã bật' : 'Chưa bật'],
        ['label' => 'curl', 'ok' => extension_loaded('curl'), 'detail' => extension_loaded('curl') ? 'Đã bật' : 'Chưa bật'],
        ['label' => 'mbstring', 'ok' => extension_loaded('mbstring'), 'detail' => extension_loaded('mbstring') ? 'Đã bật' : 'Chưa bật'],
        ['label' => 'json', 'ok' => extension_loaded('json'), 'detail' => extension_loaded('json') ? 'Đã bật' : 'Chưa bật'],
    ];
}

function install_requirements_ok()
{
    foreach (install_get_requirements() as $item) {
        if (!$item['ok']) {
            return false;
        }
    }
    return true;
}

function install_generate_encrypt_key()
{
    $domain = $_SERVER['SERVER_NAME'] ?? 'localhost';
    return md5($domain . time());
}

function install_render_404($root)
{
    http_response_code(404);
    readfile($root . '/view/error_pages/404.html');
    exit;
}

function install_escape($value)
{
    return htmlspecialchars(strval($value), ENT_QUOTES, 'UTF-8');
}

function install_import_sql($mysqli, $filePath)
{
    if (!is_file($filePath)) {
        return 'Không tìm thấy file database/shopgame.sql';
    }

    $sql = file_get_contents($filePath);
    if ($sql === false || trim($sql) === '') {
        return 'File SQL rỗng hoặc không đọc được.';
    }

    if (!$mysqli->multi_query($sql)) {
        return $mysqli->error;
    }

    do {
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());

    if ($mysqli->errno) {
        return $mysqli->error;
    }

    return true;
}

function install_env_value($value)
{
    $value = strval($value);
    if ($value === '') {
        return '';
    }
    if (preg_match('/[\s#"\']/', $value)) {
        return '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $value) . '"';
    }
    return $value;
}

function install_configure_error_display($envFile)
{
    $debugEnabled = false;
    if (is_file($envFile)) {
        $env = install_read_env_file($envFile);
        $debugEnabled = filter_var($env['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);
    }

    if ($debugEnabled) {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
    } else {
        error_reporting(0);
        ini_set('display_errors', '0');
    }
}

function install_auth_url()
{
    return 'https://nguyenanhvu.vn/';
}

function install_auth_name()
{
    return 'Nguyễn Anh Vũ';
}

function install_write_env($envFile, $data)
{
    $appName = trim($data['app_name'] ?? 'Shop Game V1');
    if ($appName === '') {
        $appName = 'Shop Game V1';
    }

    $lines = [
        '# INFO APP',
        'APP_NAME=' . install_env_value($appName),
        'APP_VERSION=1.0.0',
        'APP_ENV=production',
        'APP_DEBUG=false',
        '',
        '# INFO AUTH',
        'AUTH_URL=' . install_env_value(install_auth_url()),
        'AUTH_NAME=' . install_env_value(install_auth_name()),
        '',
        '# INFO DATABASE',
        'DB_HOST=' . install_env_value($data['db_host']),
        'DB_DATABASE=' . install_env_value($data['db_database']),
        'DB_USERNAME=' . install_env_value($data['db_username']),
        'DB_PASSWORD=' . install_env_value($data['db_password']),
        '',
        '# OTHER',
        'API_KEY_PINOCLOUD=',
        'APP_ENCRYPT_KEY=' . install_env_value($data['app_encrypt_key']),
    ];

    return file_put_contents($envFile, implode("\n", $lines) . "\n") !== false;
}

function install_update_env_app_name($envFile, $appName)
{
    if (!is_file($envFile)) {
        return false;
    }

    $appName = trim(strval($appName));
    if ($appName === '') {
        return false;
    }

    $lines = file($envFile, FILE_IGNORE_NEW_LINES);
    $updated = false;
    foreach ($lines as $index => $line) {
        if (strpos($line, 'APP_NAME=') === 0) {
            $lines[$index] = 'APP_NAME=' . install_env_value($appName);
            $updated = true;
        }
    }

    if (!$updated) {
        return false;
    }

    return file_put_contents($envFile, implode("\n", $lines) . "\n") !== false;
}

function install_bootstrap_app($root)
{
    require_once $root . '/vendor/autoload.php';
    require_once $root . '/libs/classes/db.php';
    require_once $root . '/libs/classes/functions.php';
    $db = new DB();
    $GLOBALS['db'] = $db;
    return $db;
}

function install_update_admin_user($db, $adminUsername, $adminPassword)
{
    $google2fa = new \PragmaRX\Google2FA\Google2FA();
    $adminRow = $db->get_row("SELECT * FROM `users` WHERE `id` = 1 LIMIT 1");
    if (!$adminRow) {
        return 'Không tìm thấy tài khoản admin mặc định (id=1).';
    }

    $usernameTaken = $db->num_rows("SELECT 1 FROM `users` WHERE `username` = '" . anti_xss($adminUsername) . "' AND `id` != 1 LIMIT 1");
    if ($usernameTaken > 0) {
        return 'Tên đăng nhập admin đã tồn tại.';
    }

    $Cuser = "/^[A-Za-z0-9_.]{3,32}$/";
    if (!preg_match($Cuser, $adminUsername)) {
        return 'Tên đăng nhập admin không hợp lệ.';
    }

    if (strlen($adminPassword) < 4) {
        return 'Mật khẩu admin phải từ 4 ký tự trở lên.';
    }

    $db->update('users', [
        'name' => $adminUsername,
        'username' => $adminUsername,
        'password' => hash_password($adminPassword),
        'twofactor_secret' => encrypt_data($google2fa->generateSecretKey()),
        'update_date' => gettime()
    ], " `id` = 1 ");

    return true;
}

function install_save_website($root, $envFile, $form)
{
    $db = install_bootstrap_app($root);

    $title = trim($form['title'] ?? '');
    if ($title === '') {
        return 'Vui lòng nhập tên website.';
    }

    $db->set_site('title', anti_xss($title));
    $db->set_site('description', anti_xss($form['description'] ?? ''));
    $db->set_site('pin_cron', anti_xss($form['pin_cron'] ?? ''));
    $db->set_site('card_partner_id', anti_xss($form['card_partner_id'] ?? ''));
    $db->set_site('card_partner_key', anti_xss($form['card_partner_key'] ?? ''));

    install_update_env_app_name($envFile, $title);

    if (($form['admin_username'] ?? '') !== '' || ($form['admin_password'] ?? '') !== '') {
        if (($form['admin_username'] ?? '') === '' || ($form['admin_password'] ?? '') === '') {
            return 'Nhập đầy đủ tài khoản và mật khẩu admin, hoặc bỏ trống cả hai.';
        }
        $adminResult = install_update_admin_user($db, $form['admin_username'], $form['admin_password']);
        if ($adminResult !== true) {
            return $adminResult;
        }
    }

    return true;
}

function install_load_website_defaults($root, $envFile, $form)
{
    if (!install_db_tables_exist($envFile)) {
        return $form;
    }

    $db = install_bootstrap_app($root);
    $form['title'] = $db->site('title') ?: ($form['title'] ?: 'Shop Game');
    $form['description'] = $db->site('description') ?: $form['description'];
    $form['pin_cron'] = $db->site('pin_cron') ?: $form['pin_cron'];
    $form['card_partner_id'] = $db->site('card_partner_id') ?: $form['card_partner_id'];
    $form['card_partner_key'] = $db->site('card_partner_key') ?: $form['card_partner_key'];
    return $form;
}

function install_mark_finished($lockFile)
{
    $dir = dirname($lockFile);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    return file_put_contents($lockFile, date('Y-m-d H:i:s')) !== false;
}

function install_step_badge_class($currentStep, $badgeStep)
{
    $order = ['requirements' => 1, 'database' => 2, 'website' => 3];
    if ($currentStep === $badgeStep) {
        return 'bg-primary';
    }
    if (($order[$currentStep] ?? 0) > ($order[$badgeStep] ?? 0)) {
        return 'bg-success';
    }
    return 'bg-light text-muted';
}

function install_determine_step($envFile)
{
    if (empty($_SESSION['install_passed_requirements']) || !install_requirements_ok()) {
        return 'requirements';
    }
    if (!install_is_configured($envFile) || !install_db_tables_exist($envFile)) {
        return 'database';
    }
    return 'website';
}

function install_render_layout_start($title, $step)
{
    echo '<!DOCTYPE html><html lang="vi" data-layout-mode="fluid" data-bs-theme="light">';
    echo '<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>' . install_escape($title) . '</title>';
    echo '<meta name="robots" content="noindex,nofollow">';
    echo '<link href="/assets/admin/css/vendor.min.css" rel="stylesheet" type="text/css" />';
    echo '<link href="/assets/admin/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />';
    echo '<link href="/assets/admin/css/icons.min.css" rel="stylesheet" type="text/css" />';
    echo '<link href="/assets/admin/fonts/fontawesome/css/all.min.css" rel="stylesheet" type="text/css" />';
    echo '<script src="/assets/admin/js/config.js"></script>';
    echo '</head><body>';
    echo '<div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center py-4">';
    echo '<div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">';
    echo '<div class="col-xl-7 col-lg-8 col-md-10">';
    echo '<div class="card overflow-hidden p-xxl-4 p-4 mb-0">';
    echo '<div class="text-center mb-4">';
    echo '<h4 class="fw-semibold mb-1">Cài đặt Shop Game</h4>';
    echo '<p class="text-muted mb-3">Thiết lập hệ thống cho website mới</p>';
    echo '<div class="d-flex flex-wrap justify-content-center gap-2">';
    echo '<span class="badge ' . install_step_badge_class($step, 'requirements') . '">1. Kiểm tra hệ thống</span>';
    echo '<span class="badge ' . install_step_badge_class($step, 'database') . '">2. Cấu hình database</span>';
    echo '<span class="badge ' . install_step_badge_class($step, 'website') . '">3. Cấu hình website</span>';
    echo '</div></div>';
}

function install_render_layout_end()
{
    echo '</div></div></div></div>';
    echo '<script src="/assets/admin/js/vendor.min.js"></script>';
    echo '<script src="/assets/admin/js/app.js"></script>';
    echo '</body></html>';
}

if (install_is_finished($lockFile, $envFile)) {
    install_render_404($root);
}

install_configure_error_display($envFile);

$step = install_determine_step($envFile);

$error = '';
$success = '';
$form = [
    'db_host' => '127.0.0.1',
    'db_database' => '',
    'db_username' => '',
    'db_password' => '',
    'app_encrypt_key' => '',
    'title' => 'Shop Game',
    'description' => 'Shop mua ban acc, cay thue va dich vu game.',
    'pin_cron' => '',
    'card_partner_id' => '',
    'card_partner_key' => '',
    'admin_username' => '',
    'admin_password' => '',
];

if (install_is_configured($envFile)) {
    $savedEnv = install_read_env_file($envFile);
    $form['db_host'] = $savedEnv['DB_HOST'] ?? '127.0.0.1';
    $form['db_database'] = $savedEnv['DB_DATABASE'] ?? '';
    $form['db_username'] = $savedEnv['DB_USERNAME'] ?? '';
    $form['db_password'] = $savedEnv['DB_PASSWORD'] ?? '';
    $form['app_encrypt_key'] = $savedEnv['APP_ENCRYPT_KEY'] ?? '';
    $form['title'] = $savedEnv['APP_NAME'] ?? $form['title'];
}

if ($step === 'website') {
    $form = install_load_website_defaults($root, $envFile, $form);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postType = $_POST['type'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    if ($csrfToken === '' || $csrfToken !== ($_SESSION['install_csrf'] ?? '')) {
        $error = 'CSRF token không hợp lệ.';
    } elseif ($postType === 'continue_requirements') {
        if (!install_requirements_ok()) {
            $error = 'Hệ thống chưa đáp ứng đủ yêu cầu. Cần PHP >= 8.3 và các extension bắt buộc.';
            $step = 'requirements';
        } else {
            $_SESSION['install_passed_requirements'] = true;
            header('Location: /install');
            exit;
        }
    } elseif ($postType === 'save_database') {
        $form['db_host'] = trim($_POST['db_host'] ?? '127.0.0.1');
        $form['db_database'] = trim($_POST['db_database'] ?? '');
        $form['db_username'] = trim($_POST['db_username'] ?? '');
        $form['db_password'] = strval($_POST['db_password'] ?? '');
        $form['app_encrypt_key'] = trim($_POST['app_encrypt_key'] ?? '');

        if ($form['db_database'] === '') {
            $error = 'Vui lòng nhập tên database.';
        } elseif ($form['db_username'] === '') {
            $error = 'Vui lòng nhập username database.';
        } elseif ($form['db_password'] === '') {
            $error = 'Vui lòng nhập password database.';
        } else {
            if ($form['app_encrypt_key'] === '') {
                $savedEnv = install_read_env_file($envFile);
                if (!empty($savedEnv['APP_ENCRYPT_KEY'])) {
                    $form['app_encrypt_key'] = $savedEnv['APP_ENCRYPT_KEY'];
                } else {
                    $form['app_encrypt_key'] = install_generate_encrypt_key();
                }
            } elseif (strlen($form['app_encrypt_key']) < 16) {
                $error = 'APP_ENCRYPT_KEY phải có ít nhất 16 ký tự.';
            }

            if ($error === '') {
                $mysqli = @new mysqli($form['db_host'], $form['db_username'], $form['db_password']);
                if ($mysqli->connect_errno) {
                    $error = 'Kết nối database thất bại: ' . $mysqli->connect_error;
                } else {
                    $safeDbName = preg_replace('/[^a-zA-Z0-9_]/', '', $form['db_database']);
                    if ($safeDbName === '') {
                        $error = 'Tên database không hợp lệ.';
                    } else {
                        $mysqli->query("CREATE DATABASE IF NOT EXISTS `{$safeDbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
                        $mysqli->select_db($safeDbName);

                        if (!install_has_tables($mysqli)) {
                            $importResult = install_import_sql($mysqli, $sqlFile);
                            if ($importResult !== true) {
                                $error = 'Import database thất bại: ' . $importResult;
                            }
                        }

                        if ($error === '') {
                            $envData = [
                                'db_host' => $form['db_host'],
                                'db_database' => $safeDbName,
                                'db_username' => $form['db_username'],
                                'db_password' => $form['db_password'],
                                'app_encrypt_key' => $form['app_encrypt_key'],
                            ];

                            if (!install_write_env($envFile, $envData)) {
                                $error = 'Không thể ghi file .env.';
                            } else {
                                header('Location: /install');
                                exit;
                            }
                        }
                    }
                    $mysqli->close();
                }
            }
        }
        $step = 'database';
    } elseif ($postType === 'save_website') {
        $form['title'] = trim($_POST['title'] ?? '');
        $form['description'] = trim($_POST['description'] ?? '');
        $form['pin_cron'] = trim($_POST['pin_cron'] ?? '');
        $form['card_partner_id'] = trim($_POST['card_partner_id'] ?? '');
        $form['card_partner_key'] = trim($_POST['card_partner_key'] ?? '');
        $form['admin_username'] = trim($_POST['admin_username'] ?? '');
        $form['admin_password'] = strval($_POST['admin_password'] ?? '');

        if (!install_is_configured($envFile) || !install_db_tables_exist($envFile)) {
            $error = 'Vui lòng hoàn tất cấu hình database trước.';
            $step = 'database';
        } else {
            $websiteResult = install_save_website($root, $envFile, $form);
            if ($websiteResult !== true) {
                $error = $websiteResult;
            } else {
                install_mark_finished($lockFile);
                $success = 'Cài đặt thành công! Bạn có thể truy cập website và đăng nhập admin.';
            }
        }
        $step = 'website';
    }
}

install_render_layout_start('Cài đặt Shop Game', $step);

if ($error !== '') {
    echo '<div class="alert alert-danger mb-3">' . install_escape($error) . '</div>';
}

if ($success !== '') {
    echo '<div class="alert alert-success mb-3">' . install_escape($success) . '</div>';
    echo '<div class="text-center"><a href="/" class="btn btn-primary">Về trang chủ</a></div>';
    install_render_layout_end();
    exit;
}

if ($step === 'requirements') {
    echo '<div class="list-group mb-4">';
    foreach (install_get_requirements() as $item) {
        $icon = $item['ok'] ? 'ti-check text-success' : 'ti-x text-danger';
        echo '<div class="list-group-item d-flex justify-content-between align-items-center">';
        echo '<span><i class="ti ' . $icon . ' me-2"></i>' . install_escape($item['label']) . '</span>';
        echo '<small class="text-muted">' . install_escape($item['detail'] ?? '') . '</small>';
        echo '</div>';
    }
    echo '</div>';
    echo '<form method="POST" action="/install">';
    echo '<input type="hidden" name="type" value="continue_requirements">';
    echo '<input type="hidden" name="csrf_token" value="' . install_escape($_SESSION['install_csrf']) . '">';
    echo '<div class="d-grid"><button type="submit" class="btn btn-primary fw-semibold"' . (install_requirements_ok() ? '' : ' disabled') . '>Tiếp tục</button></div>';
    echo '</form>';
} elseif ($step === 'database') {
    echo '<form method="POST" action="/install" class="text-start">';
    echo '<input type="hidden" name="type" value="save_database">';
    echo '<input type="hidden" name="csrf_token" value="' . install_escape($_SESSION['install_csrf']) . '">';
    echo '<div class="row g-3">';
    echo '<div class="col-md-6"><label class="form-label">DB Host</label>';
    echo '<input type="text" name="db_host" class="form-control" value="' . install_escape($form['db_host']) . '" required></div>';
    echo '<div class="col-md-6"><label class="form-label">DB Database</label>';
    echo '<input type="text" name="db_database" class="form-control" value="' . install_escape($form['db_database']) . '" required></div>';
    echo '<div class="col-md-6"><label class="form-label">DB Username</label>';
    echo '<input type="text" name="db_username" class="form-control" value="' . install_escape($form['db_username']) . '" required></div>';
    echo '<div class="col-md-6"><label class="form-label">DB Password</label>';
    echo '<input type="password" name="db_password" class="form-control" value="' . install_escape($form['db_password']) . '" required></div>';
    echo '<div class="col-12"><label class="form-label">APP_ENCRYPT_KEY <span class="text-muted">(tuỳ chọn)</span></label>';
    echo '<input type="text" name="app_encrypt_key" class="form-control" value="' . install_escape($form['app_encrypt_key']) . '" placeholder="Bỏ trống để hệ thống tự tạo"></div>';
    echo '</div>';
    echo '<p class="text-muted small mt-2 mb-0">Bỏ trống APP_ENCRYPT_KEY sẽ tự tạo từ tên miền và thời gian hiện tại.</p>';
    echo '<div class="d-grid mt-4"><button type="submit" class="btn btn-primary fw-semibold">Tiếp tục</button></div>';
    echo '</form>';
} else {
    echo '<form method="POST" action="/install" class="text-start">';
    echo '<input type="hidden" name="type" value="save_website">';
    echo '<input type="hidden" name="csrf_token" value="' . install_escape($_SESSION['install_csrf']) . '">';
    echo '<div class="row g-3">';
    echo '<div class="col-12"><label class="form-label">Tên website</label>';
    echo '<input type="text" name="title" class="form-control" value="' . install_escape($form['title']) . '" required></div>';
    echo '<div class="col-12"><label class="form-label">Mô tả</label>';
    echo '<textarea name="description" class="form-control" rows="3">' . install_escape($form['description']) . '</textarea></div>';
    echo '<div class="col-md-6"><label class="form-label">Pin Cron</label>';
    echo '<input type="text" name="pin_cron" class="form-control" value="' . install_escape($form['pin_cron']) . '" placeholder="Để trống nếu không dùng PIN"></div>';
    echo '<div class="col-md-6"><label class="form-label">Card Partner ID</label>';
    echo '<input type="text" name="card_partner_id" class="form-control" value="' . install_escape($form['card_partner_id']) . '"></div>';
    echo '<div class="col-12"><label class="form-label">Card Partner Key</label>';
    echo '<input type="text" name="card_partner_key" class="form-control" value="' . install_escape($form['card_partner_key']) . '"></div>';
    echo '<div class="col-md-6"><label class="form-label">Tài khoản admin <span class="text-muted">(tuỳ chọn)</span></label>';
    echo '<input type="text" name="admin_username" class="form-control" value="' . install_escape($form['admin_username']) . '" placeholder="admin"></div>';
    echo '<div class="col-md-6"><label class="form-label">Mật khẩu admin <span class="text-muted">(tuỳ chọn)</span></label>';
    echo '<input type="password" name="admin_password" class="form-control" placeholder="admin"></div>';
    echo '</div>';
    echo '<p class="text-muted small mt-2 mb-0">Bỏ trống tài khoản admin sẽ dùng mặc định <strong>admin / admin</strong>.</p>';
    echo '<div class="d-grid mt-4"><button type="submit" class="btn btn-primary fw-semibold">Hoàn tất cài đặt</button></div>';
    echo '</form>';
}

install_render_layout_end();