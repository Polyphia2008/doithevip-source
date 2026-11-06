<?php
/**
 * Sepay QR helper:
 * - Tạo URL QR (template “qronly”, “compact”, “classic”,…)
 * - Tải ảnh PNG từ URL
 * - Trả về Data URI base64 hoặc URL fallback
 */

/** Giữ chỉ chữ số trong số tài khoản */
function only_digits($s)
{
    return preg_replace('/\D+/', '', $s);
}

/**
 * Tạo URL QR sepay từ các tham số:
 * - $bankCode: mã ngân hàng (ví dụ “ACB”)
 * - $accountNo: số tài khoản (sẽ tự normal)
 * - $amount: số tiền (chuỗi hoặc số, nếu bỏ trống thì QR chỉ chứa info TK)
 * - $description: nội dung chuyển khoản
 * - $template: “qronly” / “compact” / “classic” / “acctonly”… tùy sepay
 * - $download: 1 hoặc 0 (xác định header download hay hiển thị trực tiếp)
 */
function sepay_qr_url($bankCode, $accountNo, $amount = '', $description = '', $template = 'qronly', int $download = 0) {
    $query = [
        'bank' => strtoupper(trim($bankCode)),
        'acc' => only_digits($accountNo),
        'template' => $template,
        'download' => $download ? '1' : '0',
    ];
    if ($amount !== null && $amount !== '') {
        $query['amount'] = (string) intval($amount);
    } else {
        $query['amount'] = '';
    }
    if (!empty($description)) {
        $query['des'] = $description;
    } else {
        $query['des'] = '';
    }
    return 'https://qr.sepay.vn/img?' . http_build_query($query);
}

/**
 * Tải ảnh PNG từ URL QR:
 * - Trả về binary data hoặc null nếu lỗi
 */
function fetch_qr_png($url, $timeoutSec = 8)
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => $timeoutSec,
            CURLOPT_TIMEOUT => $timeoutSec,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT => 'SepayQR/1.0',
        ]);
        $data = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($data !== false && $code >= 200 && $code < 300) ? $data : null;
    }
    $ctx = stream_context_create([
        'http' => ['method' => 'GET', 'timeout' => $timeoutSec, 'header' => "User-Agent: SepayQR/1.0\r\n"],
        'ssl' => ['verify_peer' => true, 'verify_peer_name' => true],
    ]);
    $data = @file_get_contents($url, false, $ctx);
    return $data !== false ? $data : null;
}

/**
 * Make QR + base64:
 * - 'url': trực tiếp cho thẻ <img> nếu bạn không cần mã hoá
 * - 'data_uri': Data URI base64 nếu tải thành công (tiện embed)
 */
function make_sepay_qr($bankCode, $accountNo, $amount = '', $description = '', $template = 'qronly', $download = 0): array {
    $url = sepay_qr_url($bankCode, $accountNo, $amount, $description, $template, $download);
    $png = fetch_qr_png($url);
    $dataUri = $png ? 'data:image/png;base64,' . base64_encode($png) : null;
    return ['url' => $url, 'data_uri' => $dataUri];
}

/** Tải binary (logo/ảnh bất kỳ) với cURL/fopen fallback */
function fetch_binary($url, $timeoutSec = 8) {
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => $timeoutSec,
            CURLOPT_TIMEOUT => $timeoutSec,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT => 'SepayQR/1.0',
        ]);
        $data = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($data !== false && $code >= 200 && $code < 300) ? $data : null;
    }
    $ctx = stream_context_create([
        'http' => ['method' => 'GET', 'timeout' => $timeoutSec, 'header' => "User-Agent: SepayQR/1.0\r\n"],
        'ssl' => ['verify_peer' => true, 'verify_peer_name' => true],
    ]);
    $data = @file_get_contents($url, false, $ctx);
    return $data !== false ? $data : null;
}

/**
 * Chèn logo vào giữa PNG QR (binary) và trả về PNG đã ghép (binary)
 * - $logoScale: tỉ lệ kích thước logo so với cạnh QR (0.2 = 20%)
 * - $padPx: viền nền trắng quanh logo để dễ quét
 * - $drawBorder: kẻ viền mỏng xung quanh nền trắng
 */
function overlay_logo_center_png(string $qrPng, string $logoPngOrIco, float $logoScale = 0.22, int $padPx = 8, bool $drawBorder = true): ?string {
    if (!function_exists('imagecreatefromstring')) return null;

    $qrIm = @imagecreatefromstring($qrPng);
    $lgIm = @imagecreatefromstring($logoPngOrIco);
    if (!$qrIm || !$lgIm) {
        if ($qrIm) imagedestroy($qrIm);
        if ($lgIm) imagedestroy($lgIm);
        return null;
    }

    // Bật alpha
    imagesavealpha($qrIm, true);
    imagealphablending($qrIm, true);
    imagesavealpha($lgIm, true);
    imagealphablending($lgIm, true);

    $qrW = imagesx($qrIm); $qrH = imagesy($qrIm);
    $side = min($qrW, $qrH);

    // Kích thước logo mục tiêu
    $targetLogoSide = max(20, (int)round($side * $logoScale)); // tránh quá nhỏ
    $lgW = imagesx($lgIm); $lgH = imagesy($lgIm);

    // Giữ tỉ lệ logo, đóng vào hình vuông
    $scale = min($targetLogoSide / $lgW, $targetLogoSide / $lgH);
    $newW = max(1, (int)round($lgW * $scale));
    $newH = max(1, (int)round($lgH * $scale));

    // Tạo canvas logo đã resize (có alpha)
    $lgResized = imagecreatetruecolor($newW, $newH);
    imagesavealpha($lgResized, true);
    $trans = imagecolorallocatealpha($lgResized, 0, 0, 0, 127);
    imagefill($lgResized, 0, 0, $trans);
    imagecopyresampled($lgResized, $lgIm, 0, 0, 0, 0, $newW, $newH, $lgW, $lgH);

    // Tạo nền trắng (hình vuông) lớn hơn logo để chống nhiễu
    $boxW = $newW + $padPx * 2;
    $boxH = $newH + $padPx * 2;
    $bg = imagecreatetruecolor($boxW, $boxH);
    imagesavealpha($bg, true);
    $trans2 = imagecolorallocatealpha($bg, 0, 0, 0, 127);
    imagefill($bg, 0, 0, $trans2);

    // Vẽ khối trắng với góc hơi bo đơn giản
    $white = imagecolorallocatealpha($bg, 255, 255, 255, 0);
    imagefilledrectangle($bg, 2, 2, $boxW-3, $boxH-3, $white);
    // Option viền xám nhạt
    if ($drawBorder) {
        $border = imagecolorallocatealpha($bg, 200, 200, 200, 0);
        imagerectangle($bg, 1, 1, $boxW-2, $boxH-2, $border);
    }

    // Ghép logo lên nền
    imagecopy($bg, $lgResized, $padPx, $padPx, 0, 0, $newW, $newH);

    // Vị trí giữa QR
    $dstX = (int)round(($qrW - $boxW) / 2);
    $dstY = (int)round(($qrH - $boxH) / 2);

    // Ghép nền+logo lên QR
    imagecopy($qrIm, $bg, $dstX, $dstY, 0, 0, $boxW, $boxH);

    // Xuất PNG
    ob_start();
    imagepng($qrIm);
    $out = ob_get_clean();

    imagedestroy($qrIm);
    imagedestroy($lgIm);
    imagedestroy($lgResized);
    imagedestroy($bg);

    return $out ?: null;
}

/**
 * Tạo QR sepay và chèn logo vào giữa:
 * - Trả về ['url' => ..., 'data_uri' => 'data:image/png;base64,...'] nếu thành công
 * - Nếu lỗi khi ghép logo, sẽ fallback về QR thường (từ make_sepay_qr)
 */
function make_sepay_qr_with_logo($bankCode, $accountNo, $amount = '', $description = '', $template = 'qronly', $download = 0, string $logoUrl = '', float $logoScale = 0.22, int $padPx = 8): array {
    // Lấy QR gốc
    $base = make_sepay_qr($bankCode, $accountNo, $amount, $description, $template, $download);

    // Nếu không có logoUrl -> trả QR gốc
    if (!$logoUrl) return $base;

    $qrPng = fetch_qr_png($base['url']);
    $logoBin = fetch_binary($logoUrl);

    if ($qrPng && $logoBin) {
        $merged = overlay_logo_center_png($qrPng, $logoBin, $logoScale, $padPx, false);
        if ($merged) {
            return [
                'url' => $base['url'], // vẫn trả URL QR để dự phòng
                'data_uri' => 'data:image/png;base64,' . base64_encode($merged),
            ];
        }
    }

    // Fallback giữ nguyên QR không logo
    return $base;
}