<?php
if (empty($db->site('facebook_client_id')) || empty($db->site('facebook_client_secret'))) {
    msg_redirect('account/login', 'error', 'Chưa cấu hình đăng nhập Facebook.');
}

use League\OAuth2\Client\Provider\Facebook;

$provider = new Facebook([
    'clientId'     => $db->site('facebook_client_id'),
    'clientSecret' => $db->site('facebook_client_secret'),
    'redirectUri'  => BASE_URL('account/facebook-callback'),
    'graphApiVersion' => $db->site('facebook_graph_version') ?: 'v24.0',
]);

$_SESSION['facebook_oauth_state'] = $provider->getState();
$authUrl = $provider->getAuthorizationUrl();
header('Location: ' . $authUrl);
exit;
