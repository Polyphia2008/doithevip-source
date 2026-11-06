<?php
header('Content-Type: application/json');

if (!isset($_REQUEST['signed_request'])) {
    echo json_encode([
        "error" => "signed_request_missing"
    ]);
    exit;
}

function base64_url_decode($input)
{
    return base64_decode(strtr($input, '-_', '+/'));
}

function parse_signed_request($signed_request, $app_secret)
{
    list($encoded_sig, $payload) = explode('.', $signed_request, 2);

    $sig = base64_url_decode($encoded_sig);
    $data = json_decode(base64_url_decode($payload), true);

    if (!isset($data['algorithm']) || strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
        return null;
    }

    $expected_sig = hash_hmac('sha256', $payload, $app_secret, true);

    if ($sig !== $expected_sig) {
        return null;
    }

    return $data;
}

$app_secret = $db->site('facebook_client_secret');
$data = parse_signed_request($_REQUEST['signed_request'], $app_secret);

if (!$data) {
    echo json_encode([
        "error" => "invalid_signed_request"
    ]);
    exit;
}

$fbId = $data['user_id'];
$db->remove("users", "`social_id` = '{$fbId}' AND `social_type` = 'facebook'");

echo json_encode([
    "url" => BASE_URL("account/facebook-delete-user"),
    "confirmation_code" => $fbId,
    "success" => true
]);
exit;
