<?php
$main_domain = $_SERVER['SERVER_NAME'] ?? 'localhost';

$installEnvFile = dirname(__DIR__) . '/.env';
$installRequestPath = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/') ?: '/';
$installAllowedPaths = ['/install'];

$installEnvVars = [];
if (is_file($installEnvFile)) {
    foreach (file($installEnvFile, FILE_IGNORE_NEW_LINES) as $installLine) {
        $installLine = trim($installLine);
        if ($installLine === '' || strpos($installLine, '#') === 0 || strpos($installLine, '=') === false) {
            continue;
        }
        $installParts = explode('=', $installLine, 2);
        $installEnvVars[trim($installParts[0])] = trim(trim($installParts[1] ?? ''), "\"'");
    }
}
$installRequiredKeys = ['DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'APP_ENCRYPT_KEY'];
$installConfigured = true;
foreach ($installRequiredKeys as $installKey) {
    if (trim($installEnvVars[$installKey] ?? '') === '') {
        $installConfigured = false;
        break;
    }
}
if (!$installConfigured && !in_array($installRequestPath, $installAllowedPaths, true)) {
    header('Location: /install');
    exit;
}

# Require các thư viện PHP
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/classes/db.php'; // Database
$db = new DB();

if (!in_array($installRequestPath, $installAllowedPaths, true) && !$db->table_exists('settings')) {
    header('Location: /install');
    exit;
}

require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/classes/functions.php';     // Function cơ bản
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/helper/data.php'; // Database
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/helper/user.php'; // Người Dùng
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/helper/create_qr.php'; // Tạo mã qr

# Debug
$debugEnabled = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);
ini_set('log_errors', 1);
ini_set('error_log', realpath($_SERVER["DOCUMENT_ROOT"]) . '/storage/logs/php_error.log');

if ($debugEnabled) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

$csrf_token = generate_csrf_token();

#Use Extension
use PragmaRX\Google2FA\Google2FA;
$google2fa = new Google2FA();

#Other
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$currentFullUrl = $_SERVER['REQUEST_URI']; // /order/list?service_type=hosting

# Seo Onpage
$currentLink = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$seoRow = $db->get_row("SELECT * FROM `seo` WHERE `link` = '{$currentLink}'");

if ($seoRow && !empty($seoRow['meta'])) {
    $seoMeta = json_decode($seoRow['meta'], true);
    $title = $seoMeta['title'] ?? $db->site('title');
    $description = $seoMeta['description'] ?? $db->site('description');
    $keywords = $seoMeta['keywords'] ?? $db->site('keywords');
    $image = $seoRow['image'] ?? $db->site('anhbia');
} else {
    // $title = $db->site('title');
    $description = $db->site('description');
    $keywords = $db->site('keywords');
    $image = $db->site('anhbia');
}

# Xử Lí Phiên Đăng Nhập Người Dùng
if (isset($_COOKIE['token'])) {
    $token = anti_xss($_COOKIE['token']);
    $getUser = $db->get_row("SELECT * FROM `users` WHERE `token` = '{$token}'");

    if (!$getUser || $getUser['token'] !== $token) {
        $user = false;
        setcookie('token', '', time() - 3600, '/');
        echo LoadTo('/', 0);
    } elseif (is_xss($getUser['username'])) {
        $user = false;
        $db->update('users', [
            'banned' => 1,
            'ip' => myip()
        ], "token = '$token'");
        die('Phát Hiện Dấu Hiệu Tấn Công XSS, Tài Khoản Đã Bị Cấm Vĩnh Viễn!');
    } elseif ($getUser['banned'] == 1) {
        $user = false;
        setcookie('token', $token, time() + 66666 * 60 * 60, '/');
        die('Tài Khoản Đã Bị Khóa, Vui Lòng Liên Hệ Hỗ Trợ Để Biết Thêm!');
    } else {
        $db->update('users', ['ip' => myip()], "token = '$token'");
        $user = $getUser['token'];
        $data_user = $getUser;
        $capbac = check_capbac($data_user['id']);
    }
}