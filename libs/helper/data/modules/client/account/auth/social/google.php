<?php
if (empty($db->site('google_client_id')) || empty($db->site('google_client_secret'))) {
    msg_redirect('account/login', 'error', 'Chưa cấu hình đăng nhập Google.');
}

$client = new Google_Client();
$client->setClientId($db->site('google_client_id'));
$client->setClientSecret($db->site('google_client_secret'));
$client->setRedirectUri(BASE_URL('account/google-callback'));
$client->addScope("email");
$client->addScope("profile");

header('Location: ' . $client->createAuthUrl());
exit;
