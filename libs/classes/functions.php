<?php
use Valitron\Validator;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;

date_default_timezone_set('Asia/Ho_Chi_Minh');

// EXTENSION - SECURE
function BASE_URL($url)
{
    // Nếu đang chạy CLI hoặc môi trường không có HTTP_HOST
    if (!isset($_SERVER['HTTP_HOST']) || empty($_SERVER['HTTP_HOST'])) {
        return $url; // trả về đường dẫn tương đối
    }

    $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
        . "://" . $_SERVER["HTTP_HOST"];

    return rtrim($base_url, '/') . '/' . ltrim($url, '/');
}

class Redirect {
    function __construct($url = null) {
        if ($url)
        {
            echo '<script>location.href="'.$url.'";</script>';
        }
    }
}

function LoadTo($url, $time)
{
    $setTime = !empty($time) ? $time * 1000 : 0;
    $script = '<script>setTimeout(function() { window.location.href = "' . $url . '"; }, ' . $setTime . ');</script>';
    return $script;
}


$hasherFactory = new PasswordHasherFactory([
    'common' => new NativePasswordHasher(),
]);
$hasher = $hasherFactory->getPasswordHasher('common');
function hash_password($password)
{
    global $db, $hasher;
    $method = $db->site('password_hash');

    if ($method === 'md5') {
        return md5($password);
    }

    // fallback: dùng hasher hiện tại
    return $hasher->hash($password);
}

function verify_password($inputPassword, $hashedPassword)
{
    global $db, $hasher;
    $method = $db->site('password_hash');

    if ($method === 'md5') {
        return md5($inputPassword) === $hashedPassword;
    }

    // fallback: dùng hasher hiện tại
    return $hasher->verify($hashedPassword, $inputPassword);
}

function encrypt_data($data) {
    $key = $_ENV['APP_ENCRYPT_KEY'];
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $encrypted);
}
function decrypt_data($data) {
    $key = $_ENV['APP_ENCRYPT_KEY'];
    $data = base64_decode($data);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    return openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
}
function check_string($data)
{
    if ($data === null) {
        return '';
    }
    return trim(htmlspecialchars(addslashes((string)$data)));
}

function clean_xss($data)
{
    static $purifier = null;
    if ($purifier === null) {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
    }
    $arow_xss = $purifier->purify($data);
    return $arow_xss;
}
function is_xss($data)
{
    static $purifier = null;
    if ($purifier === null) {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
    }

    $clean = $purifier->purify($data);
    return $clean !== $data;
}
function anti_xss($data)
{
    // Fix &entity\n;
    $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
    $query_string = (string) ($_SERVER['QUERY_STRING'] ?? '');
    $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
    $skipQueryInjectionCheck =
        strpos($requestPath, '/admin') === 0
        || in_array($requestPath, [
            '/account/google',
            '/account/google-callback',
            '/account/facebook',
            '/account/facebook-callback',
        ], true);

    // Avoid short tokens that false-positive on normal query keys (e.g. "script" inside "description").
    $sql_injection = [
        'union select',
        'union%20select',
        'information_schema',
        'sleep(',
        'benchmark(',
        'load_file(',
        'into outfile',
        'into dumpfile',
        '0x3a',
        '/**/',
        '**/',
        '--',
        ';--',
        'or 1=1',
        "'1'='1",
        '1=1',
        '../',
        '..%2f',
        '%00',
        '<script',
        'javascript:',
        '<?php',
        'etc/passwd',
        'boot.ini',
        'base64_',
        'eval(',
        'self/environ',
    ];
    if (!$skipQueryInjectionCheck && $query_string !== '') {
        $queryLower = strtolower($query_string);
        foreach ($sql_injection as $key) {
            if (strpos($queryLower, strtolower($key)) !== false) {
                new Redirect('/');
            }
        }
    }
    $data = addslashes(trim((string) $data));
    do {
        // Remove really unwanted tags
        $old_data = $data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    } while ($old_data !== $data);
    // we are done...
    return $data;
}
// RANDOM
function generate_csrf_token()
{
    if (!isset($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = base64_encode(openssl_random_pseudo_bytes(32));
    }
    return $_SESSION["csrf_token"];
}
function random($string, $int)
{
    return substr(str_shuffle($string), 0, $int);
}
function generate_token(int $length = 64): string {
    return bin2hex(random_bytes($length / 2));
}
function generate_with_text(string $prefix_start = "PINO", int $segments = 4, int $length = 6, string $prefix_end = "") {
    $parts = [];
    for ($i = 0; $i < $segments; $i++) {
        // random base36: 0-9 + A-Z
        $part = strtoupper(substr(base_convert(bin2hex(random_bytes(8)), 16, 36),0,$length));
        $parts[] = $part;
    }

    // Nếu có custom text thì kẹp vào giữa

    return $prefix_start . '-' . implode('-', $parts) . '-' . $prefix_end;
}
function generate_token_with_ip(int $length = 64): string {
    $token = bin2hex(random_bytes($length / 2));
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    return $token . '|' . $ip;
}
function generate_code_with_prefix($prefix, $length = 14) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomPart = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPart .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $prefix . $randomPart;
}
function randomString($length, $ktdb = false) {
    $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $specialChars = '!@#$%^&*_+-=?';
    $characters = $lowercase . $uppercase;

    if($ktdb) {
        $characters .= $numbers . $specialChars;
    } else {
        $characters .= $numbers;
    }

    $password = '';
    $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
    $password .= $lowercase[rand(0, strlen($lowercase) - 1)]; 

    if($ktdb) {
        $password .= $specialChars[rand(0, strlen($specialChars) - 1)];
    } else {
        $password .= $numbers[rand(0, strlen($numbers) - 1)]; 
    }

    for($i = strlen($password); $i < $length; $i++){
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }

    $password = str_shuffle($password);
    return $password;
}

// FORMAT - CONVERT
function format_number($price)
{
    return str_replace(".", ",", number_format($price));
}
function format_datetime($datetime) {
    if (empty($datetime) || $datetime == '0000-00-00 00:00:00') {
        return 'Không xác định';
    }
    return date("d/m/Y H:i:s", strtotime($datetime));
}

function ToTime($timestamp){
    return date('Y-m-d h:i:s', $timestamp);
}

function timeRemaining($timestamp) {
    $now = time();

    $diff = $timestamp - $now;
    if ($diff <= 0) {
        return "Hết hạn";
    }

    $days    = floor($diff / 86400);
    $hours   = floor(($diff % 86400) / 3600);
    $minutes = floor(($diff % 3600) / 60);

    if ($days > 0) {
        return "Còn {$days} ngày";
    } elseif ($hours > 0) {
        return "Còn {$hours} giờ {$minutes} phút";
    } else {
        return "Còn {$minutes} phút";
    }
}

function JsonMsg($status, $msg, $extra = [])
{
    return json_encode(array_merge(array("status" => $status, "msg" => $msg), $extra));
}

// TIME
function timeAgo($time_ago)
{
    $time_ago = date("Y-m-d H:i:s", $time_ago);
    $time_ago = strtotime($time_ago);
    $cur_time = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return "$seconds giây trước";
    }
    //Minutes
    else if ($minutes <= 60) {
        return "$minutes phút trước";
    }
    //Hours
    else if ($hours <= 24) {
        return "$hours tiếng trước";
    }
    //Days
    else if ($days <= 7) {
        if ($days == 1) {
            return "Hôm qua";
        } else {
            return "$days ngày trước";
        }
    }
    //Weeks
    else if ($weeks <= 4.3) {
        return "$weeks tuần trước";
    }
    //Months
    else if ($months <= 12) {
        return "$months tháng trước";
    }
    //Years
    else {
        return "$years năm trước";
    }
}
function timeAgo2($time_ago)
{
    $time_ago = strtotime($time_ago);
    $cur_time = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return "$seconds giây trước";
    }
    //Minutes
    else if ($minutes <= 60) {
        return "$minutes phút trước";
    }
    //Hours
    else if ($hours <= 24) {
        return "$hours tiếng trước";
    }
    //Days
    else if ($days <= 7) {
        if ($days == 1) {
            return "Hôm qua";
        } else {
            return "$days ngày trước";
        }
    }
    //Weeks
    else if ($weeks <= 4.3) {
        return "$weeks tuần trước";
    }
    //Months
    else if ($months <= 12) {
        return "$months tháng trước";
    }
    //Years
    else {
        return "$years năm trước";
    }
}
function timeAgo3($time_ago)
{
    $time_ago   = date("Y-m-d H:i:s", $time_ago);
    $time_ago   = strtotime($time_ago);
    $time_elapsed   = $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60);
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400);
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640);
    $years      = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return "$seconds giây";
    }
    //Minutes
    elseif ($minutes <= 60) {
        return "$minutes phút";
    }
    //Hours
    elseif ($hours <= 24) {
        return "$hours tiếng";
    }
    //Days
    elseif ($days <= 7) {
        if ($days == 1) {
            return "$days ngày";
        } else {
            return "$days ngày";
        }
    }
    //Weeks
    elseif ($weeks <= 4.3) {
        return "$weeks tuần";
    }
    //Months
    elseif ($months <=12) {
        return "$months tháng";
    }
    //Years
    else {
        return "$years năm";
    }
}
function gettime()
{
    return date('Y-m-d H:i:s', time());
}
function format_domain($domain) {
    // Xóa khoảng trắng thừa
    $domain = trim($domain);

    // Thêm http nếu thiếu để parse_url hoạt động
    if (!preg_match('#^https?://#i', $domain)) {
        $domain = 'http://' . $domain;
    }

    // Lấy host từ URL
    $host = parse_url($domain, PHP_URL_HOST);

    if (!$host) {
        return null; // Không hợp lệ
    }

    // Bỏ www.
    $host = preg_replace('/^www\./i', '', $host);

    // Chuyển về chữ thường
    $host = strtolower($host);

    return $host;
}

// CHECK
function check_format_card($type, $seri, $pin)
{
    $seri = strlen($seri);
    $pin = strlen($pin);
    $data = [];
    if ($type == "Viettel" || $type == "viettel" || $type == "VT" || $type == "VIETTEL") {
        if ($seri != 11 && $seri != 14) {
            $data = ["status" => false, "msg" => "Độ dài seri không phù hợp"];
            return $data;
        }
        if ($pin != 13 && $pin != 15) {
            $data = ["status" => false, "msg" => "Độ dài mã thẻ không phù hợp"];
            return $data;
        }
    }
    if ($type == "Mobifone" || $type == "mobifone" || $type == "Mobi" || $type == "MOBIFONE") {
        if ($seri != 15) {
            $data = ["status" => false, "msg" => "Độ dài seri không phù hợp"];
            return $data;
        }
        if ($pin != 12) {
            $data = ["status" => false, "msg" => "Độ dài mã thẻ không phù hợp"];
            return $data;
        }
    }
    if ($type == "VNMB" || $type == "Vnmb" || $type == "VNM" || $type == "VNMOBI") {
        if ($seri != 16) {
            $data = ["status" => false, "msg" => "Độ dài seri không phù hợp"];
            return $data;
        }
        if ($pin != 12) {
            $data = ["status" => false, "msg" => "Độ dài mã thẻ không phù hợp"];
            return $data;
        }
    }
    if ($type == "Vinaphone" || $type == "vinaphone" || $type == "Vina" || $type == "VINAPHONE") {
        if ($seri != 14) {
            $data = ["status" => false, "msg" => "Độ dài seri không phù hợp"];
            return $data;
        }
        if ($pin != 14) {
            $data = ["status" => false, "msg" => "Độ dài mã thẻ không phù hợp"];
            return $data;
        }
    }
    if ($type == "Garena" || $type == "garena") {
        if ($seri != 9) {
            $data = ["status" => false, "msg" => "Độ dài seri không phù hợp"];
            return $data;
        }
        if ($pin != 16) {
            $data = ["status" => false, "msg" => "Độ dài mã thẻ không phù hợp"];
            return $data;
        }
    }
    if ($type == "Zing" || $type == "zing" || $type == "ZING") {
        if ($seri != 12) {
            $data = ["status" => false, "msg" => "Độ dài seri không phù hợp"];
            return $data;
        }
        if ($pin != 9) {
            $data = ["status" => false, "msg" => "Độ dài mã thẻ không phù hợp"];
            return $data;
        }
    }
    if ($type == "Vcoin" || $type == "VTC") {
        if ($seri != 12) {
            $data = ["status" => false, "msg" => "Độ dài seri không phù hợp"];
            return $data;
        }
        if ($pin != 12) {
            $data = ["status" => false, "msg" => "Độ dài mã thẻ không phù hợp"];
            return $data;
        }
    }
    $data = ["status" => true, "msg" => "Success"];
    return $data;
}
function check_email($input) {
    return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
}

function check_phone($input) {
    return preg_match('/^(03|05|07|08|09)[0-9]{8}$/', $input);
}
function check_img($img)
{
    $filename = $_FILES[$img]['name'];
    $ext = explode(".", $filename);
    $ext = end($ext);
    $arr_type = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($ext, $arr_type)) {
        return true;
    }
}
function check_noidung_hoadon($des, $MEMO_PREFIX)
{
    // Kiểm tra xem có chữ "VCB" hoặc "Vietcombank" trong mô tả không
    if (stripos($des, 'VCB') !== false || stripos($des, 'Vietcombank') !== false) {
        // Nếu là VCB thì xóa tất cả khoảng trắng
        $des = str_replace(' ', '', $des);
    }

    // Tìm mã đơn hàng theo regex
    $re = '/' . preg_quote($MEMO_PREFIX, '/') . '(\d+)/im';
    preg_match($re, $des, $matches);

    if (empty($matches)) {
        return null;
    }

    // Trích xuất mã đơn hàng
    $orderId = intval($matches[1]);
    return $orderId;
}
function check_login()
{
    if (isset($_COOKIE['token'])) {
        return true;
    } else {
        return false;
    }
}
function check_login_redirect()
{
    if (isset($_COOKIE['token'])) {
        echo LoadTo(BASE_URL(''), 0);
        exit;
    }
}
function check_not_login_redirect()
{
    if (!isset($_COOKIE['token'])) {
        echo LoadTo(BASE_URL(''), 0);
        exit;
    }
}
function check_twofactor_conflict($user)
{
    if (!empty($user['twofactor'])) {
        msg_redirect(
            'disable/security/' . $user['twofactor'],
            'error',
            'Để bật phương thức xác thực mới, vui lòng tắt phương thức xác thực cũ. Xin cảm ơn!'
        );
    }
}
function check_demo_mode($redirect_url = '') {
    global $db;

    if ($db->site("status_demo") != 0) {
        msg_redirect($redirect_url, 'error', 'Trang web DEMO không thể sử dụng chức năng này.');
    }
}
function checkCoupon($product_id, $coupon, $total_money)
{
    global $db;
    if($coupon = $db->get_row("SELECT * FROM `coupons` WHERE `code` = '" . check_string($coupon) . "' AND `min` <= " . $total_money . " AND `max` >= " . $total_money . " AND `used` < `amount` ")) {
        if($db->num_rows(" SELECT * FROM `coupon_used` WHERE `coupon_id` = '" . $coupon["id"] . "' ") < $coupon["amount"]) {
            if($coupon["product_id"] == "") {
                return $coupon['discount'];
            }
            if(in_array($product_id, json_decode($coupon["product_id"]))) {
                return $coupon['discount'];
            }
            return false;
        }
        return false;
    }
    return false;
}
function getFlashSaleStatus($startDateTimeString, $endDateTimeString)
{
    // Lấy thời gian hiện tại
    $currentTime = time();

    // Chuyển đổi chuỗi ngày giờ thành dạng timestamp
    $startTime = strtotime($startDateTimeString);
    $endTime = strtotime($endDateTimeString);

    // So sánh thời gian hiện tại với thời gian bắt đầu và kết thúc của flash sale
    if ($currentTime < $startTime) {
        return array('status' => 1, 'msg' => "Sắp diễn ra");
    } elseif ($currentTime > $endTime) {
        return array('status' => 0, 'msg' => "Đã kết thúc");
    } else {
        return array('status' => 2, 'msg' => "Đang diễn ra");
    }
}
function getFlashSale($flashSaleID)
{
    global $db;

    // Lấy thời gian hiện tại
    $currentTime = time();

    // Lấy thông tin về thời gian bắt đầu và kết thúc của chương trình flash sale từ cơ sở dữ liệu
    $flashSale = $db->get_row("SELECT start_time, end_time FROM flash_sales WHERE id = '{$flashSaleID}'");

    if (!$flashSale) {
        return [
            'status' => 'error',
            'msg' => 'Chiến dịch Flash Sale không tồn tại'
        ];
    }

    $startTime = strtotime($flashSale['start_time']);
    $endTime = strtotime($flashSale['end_time']);

    // Kiểm tra trạng thái của chương trình flash sale
    if ($currentTime < $startTime) {
        return [
            'status' => 'upcoming',
            'msg' => 'Sắp diễn ra'
        ];
    } elseif ($currentTime >= $startTime && $currentTime <= $endTime) {
        return [
            'status' => 'ongoing',
            'msg' => 'Đang diễn ra'
        ];
    } else {
        return [
            'status' => 'expired',
            'msg' => 'Đã kết thúc'
        ];
    }
}
function getProductFlashSale($flashSaleID, $productID, $productType)
{
    global $db;

    $flashSaleProduct = $db->get_row("
        SELECT 
            fs.id AS flash_sale_id,
            fs.start_time,
            fs.end_time,
            fsp.discount_price,
            fsp.description
        FROM flash_sales AS fs
        JOIN flash_sale_products AS fsp
            ON fs.id = fsp.flash_sale_id
        WHERE fs.id = '{$flashSaleID}'
          AND fsp.product_id = '{$productID}'
          AND fsp.product_type = '{$productType}'
        LIMIT 1
    ");

    if (!$flashSaleProduct) {
        return [
            'flashSaleID'     => 0,
            'flashSaleStatus' => 'none',
            'discountPrice'   => 0,
            'description'     => '',
            'start_time'      => '',
            'end_time'        => ''
        ];
    }

    $flashSale = getFlashSale($flashSaleProduct['flash_sale_id']);

    return [
        'flashSaleID'     => $flashSaleProduct['flash_sale_id'],
        'flashSaleStatus' => $flashSale['status'],
        'discountPrice'   => ($flashSale['status'] === 'ongoing'
                                ? (int)$flashSaleProduct['discount_price']
                                : 0),
        'description'     => $flashSaleProduct['description'],
        'start_time'      => $flashSaleProduct['start_time'],
        'end_time'        => $flashSaleProduct['end_time']
    ];
}
function getActiveProductFlashSale($productID, $productType) {
    global $db;

    // Lấy flash sale đang hoạt động
    $currentTime = date("H:i:s"); // vì flash_sales.start_time, end_time là TIME
    $flashSale = $db->get_row("
        SELECT * FROM flash_sales 
        WHERE start_time <= '{$currentTime}' 
          AND end_time >= '{$currentTime}'
        LIMIT 1
    ");

    if (!$flashSale) {
        return [
            'flashSaleID'     => 0,
            'flashSaleStatus' => 'none',
            'discountPrice'   => 0,
            'description'     => '',
            'start_time'      => '',
            'end_time'        => ''
        ];
    }

    // Gọi lại function gốc
    return getProductFlashSale($flashSale['id'], $productID, $productType);
}

function getHourAndMinute($dateTimeString)
{
    // Tạo đối tượng DateTime
    $dateTime = new DateTime($dateTimeString);

    // Lấy giờ và phút
    $hour = $dateTime->format('H');
    $minute = $dateTime->format('i');

    // Trả về giờ và phút dưới dạng mảng
    return sprintf("%02d:%02d", $hour, $minute);
}
// FILE
function upload_multiple_file($name, $folder, $i = 0)
{
        $rand = rand(0, 99999999999999);
    $arr_type = ['jpg', 'jpeg', 'png', 'gif'];

    $destination_path = realpath($_SERVER["DOCUMENT_ROOT"]);
    $path = $destination_path . "/upload/$folder/";

    // Tạo thư mục nếu chưa có
    if (!is_dir($path)) mkdir($path, 0777, true);

    if ($_FILES[$name]["error"][$i] == 0) {
        $arr = explode(".", $_FILES[$name]["name"][$i]);
        $ext = strtolower(end($arr));

        if (in_array($ext, $arr_type)) {
            $filename = md5($_FILES[$name]["name"][$i] . $rand) . "." . $ext;
            @move_uploaded_file($_FILES[$name]["tmp_name"][$i], $path . $filename);
        }

        $image = "upload/$folder/" . $filename;
    }
    return $image ?? null;
}
function upload_file($name, $folder)
{
        $rand = rand(0, 99999999999999);
    $arr_type = ['jpg', 'jpeg', 'png', 'gif'];

    $destination_path = realpath($_SERVER["DOCUMENT_ROOT"]);
    $path = $destination_path . "/upload/$folder/";

    if (!is_dir($path)) mkdir($path, 0777, true);

    if ($_FILES[$name]["error"] == 0) {
        $arr = explode(".", $_FILES[$name]["name"]);
        $ext = strtolower(end($arr));

        if (in_array($ext, $arr_type)) {
            $filename = md5($_FILES[$name]["name"] . $rand) . "." . $ext;
            @move_uploaded_file($_FILES[$name]["tmp_name"], $path . $filename);
        }

        $image = "upload/$folder/" . $filename;
    }
    return $image ?? null;
}
function update_file($name, $old_link, $folder)
{
        $rand = rand(0, 99999999999999);
    $arr_type = ['jpg', 'jpeg', 'png', 'gif'];

    $destination_path = realpath($_SERVER["DOCUMENT_ROOT"]);
    $path = $destination_path . "/upload/$folder/";

    if (!is_dir($path)) mkdir($path, 0777, true);

    if ($_FILES[$name]["error"] == 0) {
        $arr = explode(".", $_FILES[$name]["name"]);
        $ext = strtolower(end($arr));

        if (in_array($ext, $arr_type)) {
            $filename = md5($_FILES[$name]["name"] . $rand) . "." . $ext;
            @move_uploaded_file($_FILES[$name]["tmp_name"], $path . $filename);
            $image = "/upload/$folder/" . $filename;
        }
    } else {
        $image = $old_link;
    }
    return $image;
}
function uploadAndSaveOption($inputName, $optionname)
{
    global $db;

    if (!isset($_FILES[$inputName])) {
        return false;
    }

    if (check_img($inputName)) {

        $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', rand(5, 10));

        $root = realpath($_SERVER["DOCUMENT_ROOT"]);
        $folder = "theme";
        $path = $root . "/upload/$folder/";

        // Tạo thư mục nếu chưa có
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $file_name = $rand . '.png';
        $file_path = $path . $file_name;
        $file_url  = "/upload/$folder/" . $file_name;

        if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $file_path)) {
            $db->set_site($optionname, $file_url);
            return $file_url;
        }
    }

    return false;
}
// SLUG
function create_slug($string)
{
    $search = array(
        '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
        '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
        '#(ì|í|ị|ỉ|ĩ)#',
        '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
        '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
        '#(ỳ|ý|ỵ|ỷ|ỹ)#',
        '#(đ)#',
        '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
        '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
        '#(Ì|Í|Ị|Ỉ|Ĩ)#',
        '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
        '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
        '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
        '#(Đ)#',
        "/[^a-zA-Z0-9\-\_]/",
    );
    $replace = array(
        'a',
        'e',
        'i',
        'o',
        'u',
        'y',
        'd',
        'A',
        'E',
        'I',
        'O',
        'U',
        'Y',
        'D',
        '-',
    );
    $string = preg_replace($search, $replace, $string);
    $string = preg_replace('/(-)+/', '-', $string);
    $string = strtolower($string);
    return $string;
}
function toslug($str)
{
    $str = trim(mb_strtolower($str));
    $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
    $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
    $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
    $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
    $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
    $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
    $str = preg_replace('/(đ)/', 'd', $str);
    $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
    $str = preg_replace('/([\s]+)/', '', $str);
    return $str;
}

// CREATE QR
function qr_bank_create($type, $stk, $accountname, $amount, $comment)
{
    if ($type == 'MOMO') {
        $result = 'data:image/png;base64,' . base64_encode(file_get_contents("https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl=2|99|$stk|||0|0|$amount|$comment|transfer_myqr"));
    } else {
        $result = "https://api.vietqr.io/$type/$stk/$amount/$comment/qronly2.jpg?accountName=$accountname";
    }
    return $result;
}
// DEVICE
function myip()
{
    $keys = [
        'HTTP_CF_CONNECTING_IP', // Cloudflare
        'HTTP_X_REAL_IP',        // Nginx proxy
        'HTTP_FORWARDED',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_CLIENT_IP',
        'REMOTE_ADDR'
    ];

    foreach ($keys as $key) {
        if (!empty($_SERVER[$key])) {

            // X-Forwarded-For có thể chứa nhiều IP
            $ipList = explode(',', $_SERVER[$key]);
            foreach ($ipList as $ip) {
                $ip = trim($ip);

                // Validate IP
                if (filter_var(
                    $ip,
                    FILTER_VALIDATE_IP,
                    FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                )) {
                    return $ip;
                }
            }
        }
    }

    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

// CONNECT
function curl_get($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $referer = $_SERVER['HTTP_HOST'] ?? 'unknown';
    curl_setopt($ch, CURLOPT_REFERER, 'https://' . $referer);

    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
function curl_post($url, $data = [], $headers = [])
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $referer = $_SERVER['HTTP_HOST'] ?? 'unknown';
    curl_setopt($ch, CURLOPT_REFERER, 'https://' . $referer);

    // Header mặc định
    $defaultHeaders = [
        'Content-Type: application/x-www-form-urlencoded',
        'Expect:' // tránh lỗi 100-continue
    ];

    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array_merge($defaultHeaders, $headers)
    );

    $response = curl_exec($ch);

    curl_close($ch);
    return $response;
}

function card_url_api()
{
    return 'https://doithevip.com';
}

function card_callback_allowed_ips()
{
    static $ips = null;
    static $resolvedAt = 0;
    if ($ips !== null && (time() - $resolvedAt) < 300) {
        return $ips;
    }

    $host = parse_url(card_url_api(), PHP_URL_HOST) ?: 'doithevip.com';
    $resolved = @gethostbynamel($host);
    $ips = is_array($resolved) ? $resolved : [];
    $resolvedAt = time();

    return $ips;
}

function assert_card_callback_from_doithevip()
{
    $remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';
    if ($remoteIp === '' || !in_array($remoteIp, card_callback_allowed_ips(), true)) {
        http_response_code(403);
        die('callback_source_error');
    }
}

function require_cron_pin()
{
    global $db;
    $pinCron = strval($db->site('pin_cron') ?? '');
    if ($pinCron === '') {
        return;
    }
    $pin = strval($_GET['pin'] ?? '');
    if ($pin === '') {
        die('Vui lòng nhập mã PIN');
    }
    if ($pin !== $pinCron) {
        die('Mã PIN không chính xác');
    }
}

function touch_cron_time($column)
{
    global $db;
    $allowed = [
        'check_time_cron_invoice',
        'check_time_cron_delivery',
        'check_time_cron_expires',
        'check_time_cron_suspended',
    ];
    if (!in_array($column, $allowed, true)) {
        return;
    }
    $db->set_site($column, strval(time()));
}

function cron_job_url($path)
{
    global $db;
    $url = BASE_URL(rtrim($path, '/') . '/');
    $pin = strval($db->site('pin_cron') ?? '');
    if ($pin !== '') {
        $url .= (strpos($url, '?') === false ? '?' : '&') . 'pin=' . rawurlencode($pin);
    }
    return $url;
}

function format_cron_last_run($value)
{
    if ($value === null || $value === '' || intval($value) <= 0) {
        return 'Chưa chạy';
    }
    if (is_numeric($value)) {
        return timeAgo(intval($value));
    }
    $ts = strtotime(strval($value));
    if ($ts === false || $ts <= 0) {
        return 'Chưa chạy';
    }
    return timeAgo($ts);
}

function wallet_provider_ribbon($provider, $active = false, $size = 'md')
{
    $labels = [
        'rpay' => 'RPAY',
        'stc'  => 'STC',
        'pino' => 'PINO',
    ];
    $provider = strtolower((string) $provider);
    $label = $labels[$provider] ?? strtoupper($provider);
    $state = $active ? 'is-on' : 'is-off';
    $sizeClass = $size === 'sm' ? 'wallet-provider-ribbon--sm' : '';

    return '<span class="wallet-provider-ribbon wallet-provider-ribbon--' . $provider . ' ' . $state . ' ' . $sizeClass . '">' . $label . '</span>';
}

function wallet_provider_corner_ribbon($text = 'Nên dùng')
{
    $text = htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');

    return '<span class="wallet-corner-ribbon">' . $text . '</span>';
}

function format_napthe_message($message)
{
    if (!is_string($message) || trim($message) === '') {
        return 'Nạp thẻ thất bại, vui lòng thử lại.';
    }

    $message = trim($message);
    $map = [
        'lang.card_existed'        => 'Thẻ cào đã tồn tại trong hệ thống!',
        'lang.card_exist'          => 'Thẻ cào đã tồn tại trong hệ thống!',
        'lang.card_invalid'        => 'Thẻ cào không hợp lệ!',
        'lang.card_wrong_amount'   => 'Sai mệnh giá thẻ cào!',
        'lang.card_wrong_value'    => 'Sai mệnh giá thẻ cào!',
        'lang.card_locked'         => 'Thẻ cào đang bị khóa!',
        'lang.card_used'           => 'Thẻ cào đã được sử dụng!',
        'lang.card_processing'     => 'Thẻ cào đang được xử lý!',
        'lang.card_success'        => 'Nạp thẻ thành công!',
        'lang.card_pending'        => 'Thẻ cào đang chờ xử lý!',
        'lang.card_error'          => 'Thẻ cào lỗi, vui lòng kiểm tra lại!',
        'lang.card_maintenance'    => 'Hệ thống đổi thẻ đang bảo trì!',
        'lang.partner_invalid'     => 'Thông tin đối tác không hợp lệ!',
        'lang.sign_invalid'        => 'Chữ ký xác thực không hợp lệ!',
        'lang.request_invalid'     => 'Yêu cầu không hợp lệ!',
        'lang.amount_invalid'      => 'Mệnh giá thẻ không hợp lệ!',
        'lang.telco_invalid'       => 'Nhà mạng không hợp lệ!',
    ];

    if (isset($map[$message])) {
        return $map[$message];
    }

    if (strpos($message, 'lang.') === 0) {
        $label = str_replace('_', ' ', substr($message, 5));
        return ucfirst($label) . '!';
    }

    return $message;
}

function napthe($telco, $amount, $serial, $pin, $trans_id)
{
    global $db;

    $partner_id  = $db->site("card_partner_id");
    $partner_key = $db->site("card_partner_key");
    $base_url    = rtrim(card_url_api(), '/');

    $url = $base_url . '/chargingws/v2';

    $payload = [
        'partner_id' => $partner_id,
        'request_id' => $trans_id,
        'telco'      => $telco,
        'amount'     => $amount,
        'serial'     => $serial,
        'code'       => $pin,
        'command'    => 'charging',
        'sign'       => md5($partner_key . $pin . $serial)
    ];

    $response = curl_post($url, $payload);

    if (!$response) {
        return [
            'status'  => -1,
            'message' => 'Không kết nối được hệ thống nạp thẻ'
        ];
    }

    $data = json_decode(trim($response), true);

    if (!is_array($data)) {
        return [
            'status'  => -2,
            'message' => format_napthe_message($response)
        ];
    }

    if (isset($data['message'])) {
        $data['message'] = format_napthe_message($data['message']);
    }

    return $data;
}

function laytrangthai_thecao($telco, $amount, $serial, $pin, $trans_id)
{
    global $db;

    $partner_id  = $db->site("card_partner_id");
    $partner_key = $db->site("card_partner_key");
    $base_url    = rtrim(card_url_api(), '/');

    $url = $base_url . '/chargingws/v2';

    $payload = [
        'telco'      => $telco,
        'code'       => $pin,
        'serial'     => $serial,
        'amount'     => $amount,
        'request_id' => $trans_id,
        'partner_id' => $partner_id,
        'sign'       => md5($partner_key . $pin . $serial),
        'command'    => 'check'
    ];

    $response = curl_post($url, $payload);

    if (!$response) {
        return [
            'status'  => -1,
            'message' => 'Không kết nối được hệ thống kiểm tra trạng thái'
        ];
    }

    $data = json_decode(trim($response), true);

    if (!is_array($data)) {
        return [
            'status'  => -2,
            'message' => format_napthe_message($response)
        ];
    }

    if (isset($data['message'])) {
        $data['message'] = format_napthe_message($data['message']);
    }

    return $data;
}

function sendCSM($mail_nhan, $ten_nhan, $chu_de, $noi_dung, $bcc = '', $path = '')
{
    global $db;

    // Lấy cấu hình driver đang được kích hoạt
    $driver = $db->get_row("SELECT * FROM `sendmail_driver` WHERE `status` = 1 AND `installed` = 1 AND `driver` = 'Smtp' ");
    $setting = $db->get_row("SELECT * FROM `sendmail_setting` WHERE `driver` = 'Smtp'");

    if (!$driver || !$setting) {
        return 'Chưa cấu hình SMTP hoặc cấu hình không hợp lệ.';
    }

    $configs = json_decode($driver['configs'], true);
    if (!$configs) {
        return 'Không thể đọc cấu hình SMTP.';
    }

    // Tạo và cấu hình PHPMailer
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $configs['host'] ?? 'localhost';
        $mail->SMTPAuth   = true;
        $mail->Username   = $configs['username'] ?? '';
        $mail->Password   = $configs['password'] ?? '';
        $mail->SMTPSecure = $configs['encryption'] ?? 'tls';
        $mail->Port       = $configs['port'] ?? 587;

        $mail->setFrom($setting['from_email'], $setting['from_name']);
        $mail->addAddress($mail_nhan, $ten_nhan);
        if (!empty($path)) $mail->addAttachment($path);
        if (!empty($bcc)) $mail->addBCC($bcc);

        $mail->isHTML(true);
        $mail->Subject = $chu_de;
        $mail->Body    = $noi_dung;
        $mail->CharSet = 'UTF-8';

        return $mail->send();
    } catch (Exception $e) {
        return 'Xảy ra lỗi khi gửi mail.';
    }
}

// STATUS - TYPE
function status_number($data)
{
    if ($data == 1){
        $show = 'Hiển Thị';
    }
    else if ($data == 0){
        $show = 'Ẩn';
    }
    return $show;
}
function status_invoice($id) {
    global $db;
    $row = $db->get_row("SELECT `status` FROM `invoices` WHERE `id` = '".intval($id)."'");

    if (!$row) {
        return [
            'status' => null,
            'class' => 'badge badge-module badge-secondary',
            'text' => 'Không tồn tại'
        ];
    }

    switch ($row['status']) {
        case 'unpaid':
            return [
                'status' => 'unpaid',
                'class' => 'badge badge-module badge-warning',
                'text' => 'Chưa thanh toán'
            ];
        case 'paid':
            return [
                'status' => 'paid',
                'class' => 'badge badge-module badge-success',
                'text' => 'Hoàn thành'
            ];
        case 'cancel':
            return [
                'status' => 'cancel',
                'class' => 'badge badge-module badge-danger',
                'text' => 'Đã huỷ'
            ];
        default:
            return [
                'status' => $row['status'],
                'class' => 'badge badge-module badge-secondary',
                'text' => $row['status']
            ];
    }
}

function status_invoice_admin($id) {
    global $db;
    $row = $db->get_row("SELECT `status` FROM `invoices` WHERE `id` = '".intval($id)."'");

    if (!$row) {
        return [
            'status' => null,
            'class' => 'badge badge-soft-secondary text-decoration-none',
            'text' => 'Không tồn tại'
        ];
    }

    switch ($row['status']) {
        case 'unpaid':
            return [
                'status' => 'unpaid',
                'class' => 'badge badge-soft-warning text-decoration-none',
                'text' => 'Chưa thanh toán'
            ];
        case 'paid':
            return [
                'status' => 'paid',
                'class' => 'badge badge-soft-success text-decoration-none',
                'text' => 'Hoàn thành'
            ];
        case 'cancel':
            return [
                'status' => 'cancel',
                'class' => 'badge badge-soft-danger text-decoration-none',
                'text' => 'Đã huỷ'
            ];
        default:
            return [
                'status' => $row['status'],
                'class' => 'badge badge-soft-secondary text-decoration-none',
                'text' => $row['status']
            ];
    }
}

function status_service($id) {
    global $db;
    $row = $db->get_row("SELECT `status` FROM `orders` WHERE `id` = '".intval($id)."'");

    if (!$row) {
        return [
            'status' => null,
            'class' => 'badge badge-module badge-secondary',
            'text' => 'Không tồn tại'
        ];
    }

    switch ($row['status']) {
        case 99:
            return [
                'status' => 99,
                'class' => 'badge badge-module badge-warning',
                'text' => 'Chờ xử lý'
            ];
        case 1:
            return [
                'status' => 1,
                'class' => 'badge badge-module badge-success',
                'text' => 'Thành công'
            ];
        case 2:
            return [
                'status' => 2,
                'class' => 'badge badge-module badge-warning',
                'text' => 'Đang xử lý'
            ];
        case 3:
            return [
                'status' => 3,
                'class' => 'badge badge-module badge-danger',
                'text' => 'Hủy và không hoàn tiền'
            ];
        case 4:
            return [
                'status' => 4,
                'class' => 'badge badge-module badge-warning',
                'text' => 'Hủy và hoàn tiền'
            ];
        case 5:
            return [
                'status' => 5,
                'class' => 'badge badge-module badge-danger',
                'text' => 'Bị khóa'
            ];
        case 6:
            return [
                'status' => 6,
                'class' => 'badge badge-module badge-danger',
                'text' => 'Không thanh toán'
            ];
        default:
            return [
                'status' => 7,
                'class' => 'badge badge-module badge-secondary',
                'text' => 'Không xác định'
            ];
    }
}

function status_service_admin($id) {
    global $db;
    $row = $db->get_row("SELECT `status` FROM `orders` WHERE `id` = '".intval($id)."'");

    if (!$row) {
        return [
            'status' => null,
            'class' => 'badge badge-soft-secondary text-decoration-none',
            'text' => 'Không tồn tại'
        ];
    }

    switch ($row['status']) {
        case 100:
            return [
                'status' => 100,
                'class' => 'badge badge-outline-warning text-decoration-none',
                'text' => 'Chưa thanh toán'
            ];
        case 99:
            return [
                'status' => 99,
                'class' => 'badge badge-outline-warning text-decoration-none',
                'text' => 'Chờ xử lý'
            ];
        case 1:
            return [
                'status' => 1,
                'class' => 'badge badge-outline-success text-decoration-none',
                'text' => 'Thành công'
            ];
        case 2:
            return [
                'status' => 2,
                'class' => 'badge badge-outline-warning text-decoration-none',
                'text' => 'Đang xử lý'
            ];
        case 3:
            return [
                'status' => 3,
                'class' => 'badge badge-outline-danger text-decoration-none',
                'text' => 'Đã huỷ'
            ];
        case 4:
            return [
                'status' => 4,
                'class' => 'badge badge-outline-warning text-decoration-none',
                'text' => 'Hoàn tiền'
            ];
        case 5:
            return [
                'status' => 5,
                'class' => 'badge badge-outline-danger text-decoration-none',
                'text' => 'Bị khóa'
            ];
        case 6:
            return [
                'status' => 6,
                'class' => 'badge badge-outline-danger text-decoration-none',
                'text' => 'Không thanh toán'
            ];
        default:
            return [
                'status' => 7,
                'class' => 'badge badge-outline-secondary text-decoration-none',
                'text' => 'Không xác định'
            ];
    }
}

function status_service_from_invoice($id) {
    global $db;
    $row = $db->get_row("SELECT `status_service` FROM `invoices` WHERE `id` = '".intval($id)."'");

    if (!$row) {
        return [
            'status' => null,
            'class' => 'badge badge-module badge-secondary',
            'text' => 'Không tồn tại'
        ];
    }

    switch ($row['status_service']) {
        case 100:
            return [
                'status' => 100,
                'class' => 'badge badge-module badge-warning',
                'text' => 'Chưa thanh toán'
            ];
        case 99:
            return [
                'status' => 99,
                'class' => 'badge badge-module badge-warning',
                'text' => 'Chờ xử lý'
            ];
        case 1:
            return [
                'status' => 1,
                'class' => 'badge badge-module badge-success',
                'text' => 'Hoàn thành'
            ];
        case 3:
            return [
                'status' => 3,
                'class' => 'badge badge-module badge-danger',
                'text' => 'Đã huỷ'
            ];
        default:
            return [
                'status' => $row['status'],
                'class' => 'badge badge-module badge-secondary',
                'text' => $row['status']
            ];
    }
}

function status_thecao($id) {
    global $db;
    $row = $db->get_row("SELECT `status` FROM `cards` WHERE `id` = '".intval($id)."'");

    if (!$row) {
        return [
            'status' => null,
            'class' => 'badge badge-module badge-secondary',
            'text' => 'Không tồn tại'
        ];
    }

    switch ($row['status']) {
        case 99:
            return [
                'status' => 99,
                'class' => 'badge badge-module badge-warning',
                'text' => 'Chờ xử lý'
            ];
        case 1:
            return [
                'status' => 1,
                'class' => 'badge badge-module badge-success',
                'text' => 'Thành công'
            ];
        case 2:
            return [
                'status' => 2,
                'class' => 'badge badge-module badge-warning',
                'text' => 'Thẻ sai mệnh giá'
            ];
        case 3:
            return [
                'status' => 3,
                'class' => 'badge badge-module badge-danger',
                'text' => 'Thẻ lỗi'
            ];
        case 4:
            return [
                'status' => 4,
                'class' => 'badge badge-module badge-warning',
                'text' => 'Hệ thống nạp thẻ bảo trì'
            ];
        case 100:
            return [
                'status' => 100,
                'class' => 'badge badge-module badge-danger',
                'text' => 'Gửi thẻ thất bại'
            ];
        default:
            return [
                'status' => 7,
                'class' => 'badge badge-module badge-secondary',
                'text' => 'Không xác định'
            ];
    }
}

function service_type($type) {
    switch ($type) {
        case 'hosting':
            return [
                'class' => 'badge badge-module badge-warning',
                'text' => 'Hosting'
            ];
        case 'vps':
            return [
                'class' => 'badge badge-module badge-success',
                'text' => 'VPS'
            ];
        case 'domain':
            return [
                'class' => 'badge badge-module badge-dark',
                'text' => 'Tên Miền'
            ];
        case 'license':
            return [
                'class' => 'badge badge-module badge-primary',
                'text' => 'License'
            ];
        case 'source_code':
            return [
                'class' => 'badge badge-module badge-info',
                'text' => 'Source Code'
            ];
        case 'account':
            return [
                'class' => 'badge badge-module badge-warning',
                'text' => 'Tài khoản'
            ];
        case 'service':
            return [
                'class' => 'badge badge-module badge-info',
                'text' => 'Dịch vụ'
            ];
        case 'random':
            return [
                'class' => 'badge badge-module badge-warning',
                'text' => 'Tài khoản random'
            ];
            
        case 'tuchon':
            return [
                'class' => 'badge badge-module badge-warning',
                'text' => 'Tài khoản tự chọn'
            ];
        default:
            return [
                'class' => 'badge badge-module badge-secondary',
                'text' => htmlspecialchars($type)
            ];
    }
}
function service_type_admin($type) {
    switch ($type) {
        case 'account':
            return [
                'class' => 'badge badge-outline-warning text-decoration-none',
                'text' => 'Tài khoản'
            ];
        case 'service':
            return [
                'class' => 'badge badge-outline-info text-decoration-none',
                'text' => 'Dịch vụ'
            ];
        case 'random':
            return [
                'class' => 'badge badge-outline-info text-decoration-none',
                'text' => 'Tài khoản random'
            ];
        case 'tuchon':
            return [
                'class' => 'badge badge-outline-danger text-decoration-none',
                'text' => 'Tài khoản tự chọn'
            ];
        case 'list':
            return [
                'class' => 'badge badge-outline-info text-decoration-none',
                'text' => 'Danh sách'
            ];
        case 'image':
            return [
                'class' => 'badge badge-outline-info text-decoration-none',
                'text' => 'Hình ảnh'
            ];
        default:
            return [
                'class' => 'badge badge-outline-secondary text-decoration-none',
                'text' => htmlspecialchars($type)
            ];
    }
}
function source_code_type($type) {
    switch ($type) {
        case 'license':
            return [
                'class' => 'badge badge-outline-warning',
                'text' => 'Tạo license sau khi mua'
            ];
        case 'download':
            return [
                'class' => 'badge badge-outline-primary',
                'text' => 'Tải về sau khi mua'
            ];
        default:
            return [
                'class' => 'badge badge-module badge-secondary',
                'text' => htmlspecialchars($type)
            ];
    }
}

function type_payment($paygate) {
    global $db; // để dùng được trong function
    
    switch ($paygate) {
        case 'balance':
            return [
                'type'  => 'balance',
                'class' => 'badge badge-module badge-info',
                'text'  => 'Số dư tài khoản'
            ];
        case 'bank':
            return [
                'type'  => 'bank',
                'class' => 'badge badge-module badge-primary',
                'text'  => 'Chuyển khoản ngân hàng'
            ];
        default:
            // Lấy thông tin từ bảng bankings
            $bank = $db->get_row("SELECT * FROM `bankings` WHERE `bankName` = '{$paygate}' ");
            if ($bank) {
                return [
                    'type'  => $paygate,
                    'class' => 'badge badge-module badge-secondary',
                    'text'  => $bank['textNameBank'] // lấy tên hiển thị
                ];
            }
            // fallback nếu không tìm thấy
            return [
                'type'  => $paygate,
                'class' => 'badge badge-module badge-secondary',
                'text'  => ucfirst($paygate)
            ];
    }
}
function type_action($data)
{
    if ($data == 'buy'){
        $show = 'Mua hàng';
    }
    else if ($data == 'deposit'){
        $show = 'Nạp tiền';
    }
    else if ($data == 'upgrade'){
        $show = 'Nâng cấp';
    }
    else if ($data == 'extend'){
        $show = 'Gia hạn';
    }
    return $show;
}
// RENDER
function msg_redirect($redirect_url, $status = null, $msg = null)
{
    // Nếu yêu cầu reload trang vừa gửi POST
    if ($redirect_url === 'reload') {
        if ($status && $msg) {
            if ($status === 'success') {
                $_SESSION['success'] = $msg;
            } else {
                $_SESSION['error'] = $msg;
            }
        }
        
        // Lấy URL trước khi POST
        $reloadUrl = $_SERVER['HTTP_REFERER'] ?? $_SERVER['REQUEST_URI'];
        echo LoadTo($reloadUrl, 0);
        exit;
    }

    // Redirect bình thường
    if ($status && $msg) {
        if ($status === 'success') {
            $_SESSION['success'] = $msg;
        } else {
            $_SESSION['error'] = $msg;
        }
    }
    echo LoadTo(BASE_URL($redirect_url), 0);
    exit;
}

function msg_ajax($status, $msg, $location = null) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'status' => $status,
        'msg' => $msg,
        'location' => $location
    ]);
    exit;
}

function renderServiceStats($start, $end, $label = '') {
    global $db;
    
    $services = [
        ['name' => 'Tài khoản',   'key' => 'account',      'type' => 'module',     'icon' => 'ti ti-cloud',   'color' => 'text-bg-primary'],
        ['name' => 'Dịch vụ',     'key' => 'service',      'type' => 'module',     'icon' => 'ti ti-meteor',   'color' => 'text-bg-warning'],
        ['name' => 'Nạp tiền',    'key' => 'deposit',      'type' => 'order_type', 'icon' => 'ti ti-wallet',  'color' => 'text-bg-amber'],
    ];
    
    foreach ($services as $svc) {
    $field = $svc['type'];
    $count = format_number($db->get_row("SELECT COUNT(*) AS total FROM `invoices` WHERE `$field` = '{$svc['key']}' AND `status` = 'paid' AND `created_at` BETWEEN '$start' AND '$end'")['total'] ?? 0);
    $revenue = format_number(sumPayAmount("`$field` = '{$svc['key']}' AND `status` = 'paid' AND `created_at` BETWEEN '$start' AND '$end'"));

echo <<<HTML
<div class="col-md-6 col-xl-4 col-xxl-3 mb-3">
    <div class="card h-100 overflow-hidden">
        <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="flex-shrink-0 user-img fs-42">
                    <span class="avatar-title rounded-circle {$svc['color']} fs-20">
                        <i class="{$svc['icon']}"></i>
                    </span>
                </div>
                <div>
                    <h5 class="mb-0">{$svc['name']}</h5>
                    <small class="text-muted">$label</small>
                </div>
            </div>
            <div class="mt-3">
                <p class="mb-1 text-muted">Số đơn</p>
                <h4 class="fw-semibold">{$count}</h4>
                <p class="mb-1 text-muted">Tổng tiền</p>
                <h4 class="fw-semibold">{$revenue}đ</h4>
            </div>
        </div>
    </div>
</div>
HTML;
}

// Lợi nhuận
$profit = format_number(sumNetProfit("`status` = 'paid' AND `created_at` BETWEEN '$start' AND '$end'"));
echo <<<HTML
<div class="col-md-6 col-xl-4 col-xxl-3 mb-3">
<div class="card h-100 overflow-hidden">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="flex-shrink-0 user-img fs-42">
                <span class="avatar-title rounded-circle text-bg-success fs-20">
                    <i class="ti ti-currency-dollar"></i>
                </span>
            </div>
            <div>
                <h5 class="mb-0">Lãi $label</h5>
                <small class="text-muted">Tổng lợi nhuận ròng</small>
            </div>
        </div>
        <div class="mt-3">
            <p class="mb-1 text-muted">Số tiền</p>
            <h4 class="fw-semibold">{$profit}đ</h4>
        </div>
    </div>
</div>
</div>
HTML;

// Thành viên mới
$newUser = format_number($db->get_row("SELECT COUNT(*) AS total FROM `users` WHERE `create_date` BETWEEN '$start' AND '$end'")['total'] ?? 0);
echo <<<HTML
<div class="col-md-6 col-xl-4 col-xxl-3 mb-3">
<div class="card h-100 overflow-hidden">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="flex-shrink-0 user-img fs-42">
                <span class="avatar-title rounded-circle text-bg-danger fs-20">
                    <i class="ti ti-user-plus"></i>
                </span>
            </div>
            <div>
                <h5 class="mb-0">Thành viên mới</h5>
                <small class="text-muted">$label</small>
            </div>
        </div>
        <div class="mt-3">
            <p class="mb-1 text-muted">Số lượng</p>
            <h4 class="fw-semibold">{$newUser}</h4>
        </div>
    </div>
</div>
</div>
HTML;

}
function render_toast_msg_client() {
    $types = ['success', 'error'];

    foreach ($types as $type) {
        if (!empty($_SESSION[$type])) {
            $msg = json_encode($_SESSION[$type], JSON_UNESCAPED_UNICODE);

            echo <<<JS
<script>
document.addEventListener("DOMContentLoaded", function () {
    Toast("{$type}", {$msg});
});
</script>
JS;
            unset($_SESSION[$type]);
        }
    }
}
function render_msg_client() {
    $types = [
        'error' => 'danger',
        'success' => 'success'
    ];

    foreach ($types as $key => $class) {
        if (!empty($_SESSION[$key])) {
            echo '<div class="alert alert-' . $class . ' alert-dismissible alert-custom">';
            echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>';
            echo '<ul class="mb-0 pl-3">';
            echo '<li>' . htmlspecialchars($_SESSION[$key]) . '</li>';
            echo '</ul>';
            unset($_SESSION[$key]);
            echo '</div>';
        }
    }
}
function render_msg_admin() {
    $types = [
        'success' => [
            'class' => 'success',
            'label' => 'Success',
            'icon' => 'solar:check-read-line-duotone'
        ],
        'error' => [
            'class' => 'danger',
            'label' => 'Error',
            'icon' => 'solar:danger-triangle-bold-duotone'
        ],
        'warning' => [
            'class' => 'warning',
            'label' => 'Warning',
            'icon' => 'solar:shield-warning-line-duotone'
        ],
        'info' => [
            'class' => 'info',
            'label' => 'Info',
            'icon' => 'solar:info-circle-bold-duotone'
        ]
    ];

    foreach ($types as $key => $item) {
        if (!empty($_SESSION[$key])) {
            echo '<div class="alert alert-' . $item['class'] . ' d-flex align-items-center alert-dismissible" role="alert">';
            echo '<iconify-icon icon="' . $item['icon'] . '" class="fs-20 me-1"></iconify-icon>';
            echo '<div class="lh-1">' . htmlspecialchars($_SESSION[$key]) . '</div>';
            echo '<button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
            unset($_SESSION[$key]);
        }
    }
}

// PAGINATION
function phan_trang_client($url, $start, $total, $kmess)
{
    $neighbors = 2;
    $curPage = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $totalPages = ceil($total / $kmess);

    // Fix URL base
    $url = rtrim($url, '&?');
    $base_url = strpos($url, '?') !== false ? $url . '&page=%d' : $url . '?page=%d';

    $out[] = '<div class="paging_simple_numbers"><ul class="pagination">';

    // Previous
    $out[] = $curPage > 1
        ? '<li class="page-item"><a class="page-link" href="' . sprintf($base_url, $curPage - 1) . '">‹</a></li>'
        : '<li class="page-item disabled"><span class="page-link">‹</span></li>';

    // First page + Ellipsis
    if ($curPage > $neighbors + 1) {
        $out[] = '<li class="page-item"><a class="page-link" href="' . sprintf($base_url, 1) . '">1</a></li>';
        if ($curPage > $neighbors + 2) {
            $out[] = '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }

    // Page range
    for ($i = max(1, $curPage - $neighbors); $i <= min($totalPages, $curPage + $neighbors); $i++) {
        $active = ($i === $curPage) ? 'active' : '';
        $out[] = '<li class="page-item ' . $active . '"><a class="page-link" href="' . sprintf($base_url, $i) . '">' . $i . '</a></li>';
    }

    // Last page + Ellipsis
    if ($curPage < $totalPages - $neighbors) {
        if ($curPage < $totalPages - $neighbors - 1) {
            $out[] = '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        $out[] = '<li class="page-item"><a class="page-link" href="' . sprintf($base_url, $totalPages) . '">' . $totalPages . '</a></li>';
    }

    // Next
    $out[] = $curPage < $totalPages
        ? '<li class="page-item"><a class="page-link" href="' . sprintf($base_url, $curPage + 1) . '">›</a></li>'
        : '<li class="page-item disabled"><span class="page-link">›</span></li>';

    $out[] = '</ul></div>';
    return implode('', $out);
}
function phan_trang_admin($url, $start, $total, $kmess)
{
    $curPage = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $totalPages = ceil($total / $kmess);
    if ($totalPages <= 1) return '';

    $url = rtrim($url, '&?');
    $base_url = strpos($url, '?') !== false ? $url . '&page=%d' : $url . '?page=%d';

    $html = '<div class="mt-3"><ul class="pagination mb-0">';

    // Previous
    if ($curPage > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($base_url, $curPage - 1) . '">‹</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">‹</span></li>';
    }

    $startPage = max(1, $curPage - 1);
    $endPage = min($totalPages, $curPage + 1);

    // First page
    if ($startPage > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($base_url, 1) . '">1</a></li>';
        if ($startPage > 2) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }

    // Main range
    for ($i = $startPage; $i <= $endPage; $i++) {
        $active = ($i == $curPage) ? ' active' : '';
        $html .= '<li class="page-item' . $active . '"><a class="page-link" href="' . sprintf($base_url, $i) . '">' . $i . '</a></li>';
    }

    // Last page
    if ($endPage < $totalPages) {
        if ($endPage < $totalPages - 1) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($base_url, $totalPages) . '">' . $totalPages . '</a></li>';
    }

    // Next
    if ($curPage < $totalPages) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($base_url, $curPage + 1) . '">›</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">›</span></li>';
    }

    $html .= '</ul></div>';
    return $html;
}
// CREATE WEBSITE
function aapanel_request($api_url, $api_key, $endpoint, $data = []) {
    $request_time  = time();
    $request_token = md5($request_time . md5($api_key));

    $data['request_time']  = $request_time;
    $data['request_token'] = $request_token;
    $data['api_key']       = $api_key;

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL            => $api_url . '/' . $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query($data),
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return ['message' => ['result' => "cURL Error: $err"]];
    }

    $json = json_decode($response, true);
    return is_array($json) ? ['message' => $json] : ['message' => ['result' => $response]];
}
function get_site_id($sitename, $config_aapanel) {
    $response = aapanel_request($config_aapanel['api_url'], $config_aapanel['api_key'], 'data?action=getData&table=sites', [
        'limit' => 1000
    ]);
    $sites = $response['message']['data'] ?? [];
    foreach ($sites as $site) {
        if ($site['name'] == $sitename) {
            return $site['id'];
        }
    }
    return null;
}
function domain_exists_in_site($domain, $config_aapanel) {
    $sitename = $config_aapanel['sitename'];
    $site_id = get_site_id($sitename, $config_aapanel);
    if (!$site_id) return false;

    $res = aapanel_request($config_aapanel['api_url'], $config_aapanel['api_key'], 'data?action=getData&table=domain', [
        'search' => $site_id,
        'list'   => true
    ]);

    $domains = $res['message'] ?? [];
    foreach ($domains as $d) {
        if ($d['name'] == $domain) return true;
    }
    return false;
}
function create_website_on_aapanel($domain, $config_aapanel, $main_domain = null) {
    $sitename = $config_aapanel['sitename'] ?? $main_domain;
    if (!$sitename) return ['status' => 'error', 'msg' => 'Thiếu sitename'];

    if (domain_exists_in_site($domain, $config_aapanel)) {
        return ['status' => 'success', 'msg' => 'Domain đã tồn tại trong site, không cần thêm.'];
    }

    $site_id = get_site_id($sitename, $config_aapanel);
    if (!$site_id) return ['status' => 'error', 'msg' => "Không tìm thấy site '$sitename'"];

    $res = aapanel_request($config_aapanel['api_url'], $config_aapanel['api_key'], 'site?action=AddDomain', [
        'id'      => $site_id,
        'webname'=> $sitename,
        'domain' => $domain
    ]);

    if ($res['message']['status'] ?? false) {
        return ['status' => 'success', 'msg' => 'Đã thêm domain thành công'];
    }

    return ['status' => 'error', 'msg' => $res['message']['msg'] ?? 'Không rõ lỗi khi thêm domain'];
}
function run_ssl_on_aapanel_domain($domain, $config_aapanel) {
    $sitename = $config_aapanel['sitename'] ?? null;
    if (!$sitename) return ['status' => 'error', 'msg' => 'Thiếu sitename'];

    $res = aapanel_request($config_aapanel['api_url'], $config_aapanel['api_key'], 'site?action=SetSSL', [
        'siteName' => $sitename,
        'domain'   => $domain,
        'ssl'      => 1,
        'force'    => 1
    ]);

    if ($res['message']['status'] ?? false) {
        return ['status' => 'success', 'msg' => 'Đã bật SSL cho domain: ' . $domain];
    }

    return ['status' => 'error', 'msg' => $res['message']['msg'] ?? 'Không thể bật SSL'];
}
function remove_domain_from_aapanel($domain, $config_aapanel) {
    $sitename = $config_aapanel['sitename'] ?? null;
    if (!$sitename) return ['status' => 'error', 'msg' => 'Thiếu sitename'];

    $site_id = get_site_id($sitename, $config_aapanel);
    if (!$site_id) return ['status' => 'error', 'msg' => "Không tìm thấy site '$sitename'"];

    $res = aapanel_request($config_aapanel['api_url'], $config_aapanel['api_key'], 'site?action=DelDomain', [
        'id'     => $site_id,
        'webname'=> $sitename,
        'domain' => $domain,
        'port'   => 80
    ]);

    if ($res['message']['status'] ?? false) {
        return ['status' => 'success', 'msg' => "Đã xoá domain '$domain' khỏi site '$sitename'"];
    }

    return ['status' => 'error', 'msg' => $res['message']['msg'] ?? 'Không thể xoá domain'];
}
// OTHER
function TruTienDichVu($time, $amount, $hsd) {
    $ngayMua = $time; 
    $ngayHienTai = time(); 
    $soNgay = floor(($ngayHienTai - $ngayMua) / (60 * 60 * 24));

    $giaDichVu = $amount; 
    $tienDaDung = floor($soNgay * ($giaDichVu / $hsd));
    
    $tienConLai = max(0, $giaDichVu - $tienDaDung);
    return $tienConLai;
}

