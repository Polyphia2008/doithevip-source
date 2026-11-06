<?php
use League\OAuth2\Client\Provider\Facebook;

if (empty($db->site('facebook_client_id')) || empty($db->site('facebook_client_secret'))) {
    msg_redirect('account/login', 'error', 'Chưa cấu hình đăng nhập Facebook.');
}

$provider = new Facebook([
    'clientId'     => $db->site('facebook_client_id'),
    'clientSecret' => $db->site('facebook_client_secret'),
    'redirectUri'  => BASE_URL('account/facebook-callback'),
    'graphApiVersion' => $db->site('facebook_graph_version') ?: 'v24.0',
]);

if (!isset($_GET['code'])) {
    msg_redirect('account/login', 'error', 'Lỗi đăng nhập Facebook.');
}

if (empty($_GET['state']) || ($_GET['state'] !== ($_SESSION['facebook_oauth_state'] ?? ''))) {
    unset($_SESSION['facebook_oauth_state']);
    msg_redirect('account/login', 'error', 'Có lỗi xảy ra vui lòng thử lại sau.');
}

unset($_SESSION['facebook_oauth_state']);

try {
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    $fbUser = $provider->getResourceOwner($token);
    $fbId = anti_xss(strval($fbUser->getId()));
    $email = anti_xss(strval($fbUser->getEmail() ?: "fb_{$fbId}@facebook.local"));
    $name = anti_xss(strval($fbUser->getName() ?: 'Facebook User'));

    if ($fbId === '') {
        msg_redirect('account/login', 'error', 'Facebook không cung cấp đủ thông tin tài khoản.');
    }

    $checkUser = $db->get_row("SELECT * FROM `users` WHERE `social_id` = '{$fbId}' AND `social_type` = 'facebook' LIMIT 1");

    $new_token = generate_token();
    $expiry_time = time() + intval($db->site('session_login'));

    if (!$checkUser) {
        $username = 'fb_' . $fbId;
        $existsUsername = $db->num_rows("SELECT 1 FROM `users` WHERE `username` = '{$username}' LIMIT 1");
        if ($existsUsername > 0) {
            $username = 'fb_' . $fbId . '_' . rand(100, 999);
        }

        $db->insert('users', [
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'social_id' => $fbId,
            'social_type' => 'facebook',
            'password' => null,
            'admin' => 0,
            'ip' => myip(),
            'device' => $_SERVER['HTTP_USER_AGENT'],
            'token' => $new_token,
            'token_expiry' => $expiry_time,
            'twofactor_secret' => encrypt_data($google2fa->generateSecretKey()),
            'create_date' => gettime(),
            'update_date' => gettime()
        ]);

        $userId = $db->get_id_insert();
        insert_log($userId, 'Đăng ký và đăng nhập qua Facebook');
        setcookie('token', $new_token, $expiry_time, '/', '', true, true);
        msg_redirect('');
    }

    if (intval($checkUser['banned']) === 1) {
        msg_redirect('account/login', 'error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin.');
    }

    if (intval($checkUser['admin']) === 1 && $checkUser['twofactor'] === 'GoogleAuth' && !empty($checkUser['twofactor_secret'])) {
        $_SESSION['verify'] = true;
        $_SESSION['stoken'] = generate_token();
        $_SESSION['user_pending'] = $checkUser['id'];
        msg_redirect('account/login');
    }

    $db->update('users', [
        'update_date' => gettime(),
        'token' => $new_token,
        'token_expiry' => $expiry_time,
        'failed' => 0,
    ], " `id` = '{$checkUser['id']}' ");

    insert_log($checkUser['id'], 'Đăng nhập qua Facebook');
    setcookie('token', $new_token, $expiry_time, '/', '', true, true);

    $redirect = intval($checkUser['admin']) === 1 ? 'admin' : '';
    msg_redirect($redirect);
} catch (Exception $e) {
    msg_redirect('account/login', 'error', 'Đăng nhập qua Facebook không thành công!');
}
