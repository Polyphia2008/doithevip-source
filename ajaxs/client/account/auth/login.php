<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['error'] = null;
        $_SESSION['verify'] = null;
        $_SESSION['stoken'] = null;
        $_SESSION['user_pending'] = null;

        if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
            die(JsonMsg('error', 'CSRF token không hợp lệ.'));
        }
        if($db->site("status_demo") == 1){
            die(JsonMsg('error', 'Đây là trang web demo, không thể thực hiện chức năng này!'));
        }
        if ($db->site('cf_enable') == 1) {
            $token = $_POST['cf-turnstile-response'] ?? '';
            if (empty($token)) {
                die(JsonMsg('error', 'Vui lòng xác minh captcha.'));
            }
            $secret = $db->site('cf_secret_key');
            $ch = curl_init("https://challenges.cloudflare.com/turnstile/v0/siteverify");
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query([
                    'secret'   => $secret,
                    'response' => $token,
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                ])
            ]);
            $result = json_decode(curl_exec($ch), true);
            if (!$result['success']) {
                die(JsonMsg('error', 'Captcha không hợp lệ, vui lòng thử lại.'));
            }
        }
        
        if (empty($_POST["username"])) {
            die(JsonMsg('error', 'Vui lòng nhập email, số điện thoại hoặc tên đăng nhập.'));
        }

        if (empty($_POST["password"])) {
            die(JsonMsg('error', 'Vui lòng nhập mật khẩu.'));
        }

        $username = anti_xss($_POST["username"] ?? '');
        $password = anti_xss($_POST["password"] ?? '');

        $getUser = $db->get_row("SELECT * FROM `users` WHERE (`email` = '$username' OR `phone` = '$username' OR `username` = '$username') LIMIT 1");

        if (!$getUser) {
            die(JsonMsg('error', 'Tài khoản không tồn tại.'));
        }

        if ($getUser['banned']) {
            die(JsonMsg('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin.'));
        }

        if (!verify_password($password, $getUser['password'])) {
            if ($getUser['failed'] >= 5) {
                Banned($getUser['id'], "Tài khoản của bạn đã bị khoá do đang nhập sai nhiều lần");
                die(JsonMsg('error', 'Tài khoản bị khóa do đăng nhập sai nhiều lần, vui lòng liên hệ admin'));
            }
            $db->cong('users', 'failed', 1, " `id` = '" . $getUser['id'] . "' ");
            die(JsonMsg('error', 'Thông tin đăng nhập không chính xác'));
        }

        if (intval($getUser['admin']) === 1 && $getUser['twofactor'] === 'GoogleAuth' && !empty($getUser['twofactor_secret'])) {
            $_SESSION['verify'] = true;
            $_SESSION['stoken'] = generate_token();
            $_SESSION['user_pending'] = $getUser['id'];
            die(JsonMsg('verify', 'Vui lòng nhập mã xác thực 2FA.', ['redirect' => BASE_URL('account/login')]));
        }
        
        $new_token = generate_token();
        $expiry_time = time() + intval($db->site('session_login'));
        $db->update('users', [
            'failed' => 0,
            'token' => $new_token,
            'token_expiry' => $expiry_time,
            'update_date' => gettime()
        ], " `id` = '{$getUser['id']}' ");
        insert_log($getUser['id'], "Đăng nhập vào hệ thống");
        setcookie("token", $new_token, $expiry_time, "/", "", true, true);

        $redirect = intval($getUser['admin']) === 1 ? BASE_URL('admin') : BASE_URL('');
        die(JsonMsg('success', 'Đăng nhập thành công', ['redirect' => $redirect]));
    }
?>
