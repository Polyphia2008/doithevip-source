<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    
    if (isset($_POST['type']) && $_POST['type'] === 'merchant-test') {
        $_SESSION['error'] = null;
        $_SESSION['success'] = null;
    
        // CSRF
        if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
            die(json_encode([
                "status" => false,
                "msg"    => "CSRF token không hợp lệ."
            ]));
        }
    
        if (!check_login()) {
            die(json_encode([
                "status" => false,
                "msg"    => "Bạn chưa đăng nhập."
            ]));
        }
    
        // ================================
        //  LẤY DỮ LIỆU TỪ FORM
        // ================================
        $type   = $_POST['category'] ?? '';
        $action = $_POST['wallet_type'] ?? ($_POST['game_type'] ?? '');
    
        if (!$type || !$action) {
            die(json_encode([
                "status" => false,
                "msg"    => "Thiếu type hoặc action."
            ]));
        }
    
        // API KEY CỦA NGƯỜI DÙNG
        $api_key = $data_user['api_key'];
    
        // ================================
        //  CHUẨN BỊ PARAMS GỬI API
        // ================================
        $params = [];
    
        // WALLET / WEBTHE
        if ($action === "webthe") {
            $params = [
                "webcard" => $_POST['webcard'] ?? '',
                "cookie"  => $_POST['cookie'] ?? ''
            ];
        }
    
        // WALLET / ACB BANK
        if ($action === "acb_bank") {
            $params = [
                "username" => anti_xss($_POST['username'] ?? ''),
                "password" => anti_xss($_POST['password'] ?? ''),
                "stk"      => anti_xss($_POST['stk'] ?? ''),
                "limit"    => anti_xss($_POST['limit'] ?? '20'),
            ];
        }
    
        // GAME / ROBLOX CREATE GAMEPASS
        if ($action === "roblox_create_gamepass") {
            $params = [
                "cookie" => trim($_POST['rbx_cookie'] ?? ''),
                "name"   => trim($_POST['rbx_name'] ?? ''),
                "price"  => (int)($_POST['rbx_price'] ?? 0),
            ];
    
            if (!empty($_POST['rbx_image'])) {
                $params['image'] = $_POST['rbx_image'];
            }
        }
    
        // ================================
        //  GHÉP QUERY STRING
        // ================================
        $query = http_build_query(array_merge([
            "api_key" => $api_key,
            "type"    => $type,
            "action"  => $action
        ], $params));
    
        $url = "https://pinocloud.vn/api/app?" . $query;
    
        // ================================
        //  REQUEST ĐẾN API THẬT
        // ================================
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT      => "PinoCloud-Test-Client"
        ]);
    
        $response = curl_exec($curl);
        $curl_error = curl_error($curl);
        curl_close($curl);
        
        if ($curl_error) {
            die(json_encode([
                "status" => false,
                "msg"    => "Lỗi CURL: " . $curl_error,
                "url"    => $url
            ]));
        }
        
        // Decode result
        $decode = json_decode($response, true);
        
        die(json_encode([
            "status"      => true,
            "msg"         => "Test API thành công",
            "url"         => $url,
            "result_raw"  => $response,      // Kết quả gốc
            "result"      => $decode ?: null // Đã decode nếu là JSON
        ]));
    }
    
?>