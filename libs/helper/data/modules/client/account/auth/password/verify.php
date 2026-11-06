<?php

$username = anti_xss($_GET['user'] ?? '');

$token = anti_xss($_GET['token'] ?? '');



if ($username === '' || $token === '') {

    msg_redirect('', 'error', 'Thiếu thông tin xác thực từ liên kết.');

}

