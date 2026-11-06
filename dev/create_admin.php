<?php
// Dùng: php dev/create_admin.php <username> <password>
// Tạo/cập nhật tài khoản admin (id=1) với mật khẩu bcrypt. Chạy sau khi đã có .env và import database/shopgame.sql.
$user = $argv[1] ?? 'admin';
$pass = $argv[2] ?? null;
if (!$pass) { fwrite(STDERR, "Usage: php dev/create_admin.php <username> <password>\n"); exit(1); }
$root = dirname(__DIR__);
$_SERVER['DOCUMENT_ROOT'] = $root; $_SERVER['SERVER_NAME'] = 'localhost'; $_SERVER['REQUEST_URI'] = '/';
require $root . '/vendor/autoload.php';
require $root . '/libs/classes/db.php';
require $root . '/libs/classes/functions.php';
$db = new DB(); $GLOBALS['db'] = $db;
$db->set_site('password_hash', 'bcrypt');
$db->update('users', [
  'username' => $user, 'name' => 'Administrator', 'admin' => 1, 'banned' => 0, 'failed' => 0,
  'password' => hash_password($pass),
], " `id` = 1 ");
echo "OK: admin '$user' da duoc cap nhat\n";
