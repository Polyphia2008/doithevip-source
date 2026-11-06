<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['error'] = null;

        // Check CSRF
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

        // Làm sạch dữ liệu
        $contact = anti_xss($_POST['phoneOrEmail'] ?? '');
        $username = anti_xss($_POST['username'] ?? '');
        $name = anti_xss($_POST['name'] ?? $username);
        $password = anti_xss($_POST['password'] ?? '');
        $ipRegister = myip();
        $Cuser = "/^[A-Za-z0-9_.]{3,32}$/";

        // Xác định là email hay số điện thoại
        $email = null;
        $phone = null;

        if ($contact !== '') {
            if (check_email($contact)) {
                $email = $contact;

                if ($db->num_rows("SELECT 1 FROM `users` 
                    WHERE `email` = '{$email}'") > 0) {
                    die(JsonMsg('error', 'Email đã tồn tại trong hệ thống.'));
                }

            } elseif (check_phone($contact)) {
                $phone = $contact;

                if ($db->num_rows("SELECT 1 FROM `users` 
                    WHERE `phone` = '{$phone}'") > 0) {
                    die(JsonMsg('error', 'Số điện thoại đã tồn tại trong hệ thống.'));
                }

            } else {
                die(JsonMsg('error', 'Email hoặc số điện thoại không hợp lệ.'));
            }
        }

        // Kiểm tra tồn tại
        if ($email && $db->num_rows("SELECT * FROM `users` WHERE `email` = '{$email}'") > 0) {
            die(JsonMsg('error', 'Email đã tồn tại trong hệ thống.'));
        }

        if ($phone && $db->num_rows("SELECT * FROM `users` WHERE `phone` = '{$phone}'") > 0) {
            die(JsonMsg('error', 'Số điện thoại đã tồn tại trong hệ thống.'));
        }

        // Kiểm tra username
        if (!preg_match($Cuser, $username)) {
            die(JsonMsg('error', 'Tên đăng nhập không hợp lệ.'));
        }

        if (strlen($username) <= 3) {
            die(JsonMsg('error', 'Tên đăng nhập phải từ 4 ký tự trở lên.'));
        }

        if ($db->num_rows("SELECT * FROM `users` WHERE `username` = '{$username}'") > 0) {
            die(JsonMsg('error', 'Tên đăng nhập đã tồn tại.'));
        }

        // Kiểm tra mật khẩu
        if (strlen($password) < 3) {
            die(JsonMsg('error', 'Mật khẩu phải từ 4 ký tự trở lên.'));
        }

        // Hash password + token + insert
        $realPass = hash_password($password);
        $new_token = generate_token();
        $expiry_time = time() + $db->site('session_login');

        $db->insert("users", [
            'name' => $name,
            'username' => $username,
            'password' => $realPass,
            'email' => $email ?: null,
            'phone' => $phone ?: null,
            'admin' => 0,
            'device' => $_SERVER['HTTP_USER_AGENT'],
            'ip' => $ipRegister,
            'token' => $new_token,
            'token_expiry' => $expiry_time,
            'twofactor_secret' => encrypt_data($google2fa->generateSecretKey()),
            'create_date' => gettime(),
            'update_date' => gettime()
        ]);
        
        insert_log($db->get_id_insert(), "Thực hiện tạo tài khoản");
        setcookie("token", $new_token, $expiry_time, "/", "", true, true);
        die(JsonMsg('success', 'Đăng ký thành công'));
    }
?>