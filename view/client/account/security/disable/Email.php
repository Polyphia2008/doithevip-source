<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    $title = "Bảo Mật Tài Khoản Bằng Email";
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Header.php');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Navbar.php');
    check_not_login_redirect();
?>
<div id="main" class="main">
    <div class="section-breadcrumb">
        <div class="container">
            <ol class="breadcrumb bg-transparent rounded-0 mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="<?=BASE_URL('');?>">Trang chủ</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?=BASE_URL('account/security');?>">Bảo mật tài khoản</a>
                </li>
            </ol>
        </div>
    </div>
    <div class="section-gap">
        <div class="container">
            <?php render_msg_client(); ?>
            <div class="row">
                <div class="col-12"></div>
                <div class="col-12">
                    <div class="description mb-3">
                        <div class="title"> Bảo mật tài khoản </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="box-content table-responsive">
                        <form method="POST" action="<?=BASE_URL('ajax/security/action');?>">
                            <input type="hidden" class="input-text" name="csrf_token" value="<?= generate_csrf_token() ?>" required>
                            <div class="template-1-heading">
                                <h5 class="text-uppercase text-center">Xác thực</h5>
                                <p class="mb-0 text-dark lh-18 text-center"> Để kích hoạt được thao tác vui lòng xác thực danh tính của bạn </p>
                            </div>
                            <label>Mã xác thực</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" name="secret" placeholder="Mã xác thực đã được gửi vào email của bạn" required="">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="sendcode">Gửi mã cho tôi</button>
                                </div>
                                <span class="text-danger w-100" id="notice_send"></span>
                            </div>
                            <script type="text/javascript" src="/assets/client/plugins/jquery.min.js"></script>
                            <script>
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $('body').on('click', '#sendcode', function() {
                                    var type = "send-code-to-mail";
                                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                                    $('#sendcode').attr('disabled', true);
                                    $.ajax({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        url: '<?=BASE_URL('ajax/security/action');?>',
                                        method: 'POST',
                                        data: {
                                            type: type,
                                            csrf_token: csrf_token
                                        },
                                        beforeSend: function() {
                                            // $('.overlay').show();
                                        },
                                        success: function(response) {
                                            if (response.status === 'success') {
                                                $('#notice_send').html(response.message);
                                            } else {
                                                $('#notice_send').html(response.message);
                                            }
                                        }
                                    });
                                    setTimeout(function() {
                                        $("#sendcode").prop("disabled", false);
                                    }, 30000);
                                })
                            </script>
                            <div class="form-group">
                                <input name="type" value="Email" type="hidden">
                                <input name="status" value="disable" type="hidden">
                                <button class="btn btn-warning" style="width: 100%">Thực hiện</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Footer.php');
?>