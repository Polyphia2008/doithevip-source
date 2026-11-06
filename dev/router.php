<?php
// Router cho PHP built-in server: mô phỏng các RewriteRule trong .htaccess (chỉ dùng cho preview/dev).
$root = dirname(__DIR__);
$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = ltrim(rawurldecode($uri), '/');

// Chặn file nhạy cảm
if (preg_match('#(^|/)\.(env|git|htaccess)#', $path) || preg_match('#^(dev|database|storage|vendor)/#', $path) && !preg_match('#\.(css|js|png|jpg|svg|woff2?|ttf)$#', $path)) {
    http_response_code(403); exit('Forbidden');
}

// File tĩnh có thật (không phải .php)
$file = $root . '/' . $path;
if ($path !== '' && is_file($file) && !preg_match('#\.php$#', $path)) {
    return false;
}

// Áp dụng RewriteRule từ .htaccess
static $rules = null;
if ($rules === null) {
    $rules = [];
    foreach (file($root . '/.htaccess', FILE_IGNORE_NEW_LINES) as $line) {
        if (preg_match('#^\s*RewriteRule\s+(\S+)\s+(\S+)\s*(\[[^\]]*\])?#', $line, $m) && $m[2] !== '-') {
            $rules[] = [$m[1], $m[2], $m[3] ?? ''];
        }
    }
}
foreach ($rules as [$pattern, $target, $flags]) {
    if (preg_match('#' . str_replace('#', '\#', $pattern) . '#', $path, $mm)) {
        $dest = preg_replace_callback('/\$(\d)/', fn($x) => $mm[(int)$x[1]] ?? '', $target);
        $parts = explode('?', $dest, 2);
        if (!empty($parts[1])) {
            parse_str($parts[1], $q);
            $_GET = array_merge($_GET, $q);
            $_REQUEST = array_merge($_REQUEST, $q);
        }
        $script = $root . '/' . $parts[0];
        if (is_file($script)) {
            $_SERVER['SCRIPT_NAME'] = '/' . $parts[0];
            $_SERVER['SCRIPT_FILENAME'] = $script;
            chdir(dirname($script));
            require $script;
            return true;
        }
    }
}

if ($path === '' || $path === 'index.php') {
    $_SERVER['SCRIPT_FILENAME'] = $root . '/index.php';
    chdir($root);
    require $root . '/index.php';
    return true;
}
http_response_code(404);
$e = $root . '/view/error_pages/404.html';
is_file($e) ? readfile($e) : print('404');
