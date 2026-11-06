<?php
$client = new Google_Client();
$client->setClientId($db->site('google_client_id'));
$client->setClientSecret($db->site('google_client_secret'));
$client->setRedirectUri(BASE_URL('account/google-callback'));

if (!isset($_GET['code'])) {
    msg_redirect('account/login', 'error', 'Có lỗi xảy ra vui lòng thử lại sau.');
}

$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
if (isset($token['error'])) {
    msg_redirect('account/login', 'error', 'Đăng nhập qua Google không thành công.');
}

$client->setAccessToken($token['access_token']);

$oauth2 = new Google_Service_Oauth2($client);
$google_info = $oauth2->userinfo->get();

$googleId = anti_xss(strval($google_info->id ?? ''));
$email = anti_xss(strval($google_info->email ?? ''));
$name = anti_xss(strval($google_info->name ?? 'Google User'));

if ($googleId === '' || $email === '') {
    msg_redirect('account/login', 'error', 'Google không cung cấp đủ thông tin tài khoản.');
}

$user = $db->get_row("
    SELECT * FROM `users`
    WHERE (`social_id` = '{$googleId}' AND `social_type` = 'google')
       OR `email` = '{$email}'
    LIMIT 1
");

$new_token = generate_token();
$expiry_time = time() + intval($db->site('session_login'));

if ($user) {
    if (intval($user['banned']) === 1) {
        msg_redirect('account/login', 'error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin.');
    }

    if (intval($user['admin']) === 1 && $user['twofactor'] === 'GoogleAuth' && !empty($user['twofactor_secret'])) {
        $_SESSION['verify'] = true;
        $_SESSION['stoken'] = generate_token();
        $_SESSION['user_pending'] = $user['id'];
        msg_redirect('account/login');
    }

    $updateData = [
        'update_date' => gettime(),
        'token' => $new_token,
        'token_expiry' => $expiry_time,
        'failed' => 0,
    ];

    if (empty($user['social_id']) || empty($user['social_type'])) {
        $updateData['social_id'] = $googleId;
        $updateData['social_type'] = 'google';
    }

    $db->update('users', $updateData, " `id` = '{$user['id']}' ");
    insert_log($user['id'], 'Đăng nhập qua Google');
    setcookie('token', $new_token, $expiry_time, '/', '', true, true);

    $redirect = intval($user['admin']) === 1 ? 'admin' : '';
    msg_redirect($redirect);
}

$username = 'gg_' . $googleId;
$existsUsername = $db->num_rows("SELECT 1 FROM `users` WHERE `username` = '{$username}' LIMIT 1");
if ($existsUsername > 0) {
    $username = 'gg_' . $googleId . '_' . rand(100, 999);
}

$db->insert('users', [
    'name' => $name,
    'username' => $username,
    'email' => $email,
    'social_id' => $googleId,
    'social_type' => 'google',
    'password' => null,
    'admin' => 0,
    'token' => $new_token,
    'token_expiry' => $expiry_time,
    'twofactor_secret' => encrypt_data($google2fa->generateSecretKey()),
    'create_date' => gettime(),
    'update_date' => gettime(),
    'ip' => myip(),
    'device' => $_SERVER['HTTP_USER_AGENT'],
]);

$userId = $db->get_id_insert();
insert_log($userId, 'Đăng ký và đăng nhập qua Google');
setcookie('token', $new_token, $expiry_time, '/', '', true, true);
msg_redirect('');
