<?php

check_not_login_redirect();

check_twofactor_conflict($data_user);



use PragmaRX\Google2FAQRCode\Google2FA;



$twofactor_secret = decrypt_data($data_user['twofactor_secret']);

$google2fa = new Google2FA();

$qr_code_inline = $google2fa->getQRCodeInline($_SERVER['HTTP_HOST'], $data_user['username'], $twofactor_secret);

