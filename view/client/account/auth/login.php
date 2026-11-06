<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    $title = "Đăng Nhập";
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Header.php');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Navbar.php');
    check_login_redirect();
?>
<div id="main" class="main">
    <div class="section-breadcrumb">
        <div class="container">
            <ol class="breadcrumb bg-transparent rounded-0 mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="<?=BASE_URL('');?>">Trang chủ</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?=BASE_URL('account/login');?>">Đăng nhập</a>
                </li>
            </ol>
        </div>
    </div>
    <div class="section-gap">
        <div class="container">

            <?php render_msg_client(); ?>

            <div class="py-4">
                <div class="description mb-3">
                    <div class="text-left title">
                        Đăng nhập tài khoản
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-m1">
                            <?php if (empty($_SESSION['verify'])): ?>
                            <form method="POST" action="<?=BASE_URL('ajax/login');?>" id="login-form" aria-label="Đăng nhập">
                                <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                                <div class="row row15">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label for="" class="col-form-label font-weight-bold">
                                                    Tên đăng nhập:
                                                </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input id="name" type="text" class="form-control" name="username"
                                                    value="" required="" autofocus="" placeholder="Email hoặc số điện thoại">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row row15">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label for="" class="col-form-label font-weight-bold">
                                                    Mật khẩu
                                                </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="password" class="form-control" autocomplete="off"
                                                    name="password" required="" placeholder="Mật khẩu" id="password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (($site['cf_enable'] ?? 0) == 1): ?>
                                <div class="row row15">
                                    <div class="col-lg-8 offset-lg-4">
                                        <div class="cf-turnstile"
                                             data-sitekey="<?= $site['cf_site_key'] ?? '' ?>">
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="row row15">
                                    <div class="col-lg-8 offset-lg-4">
                                        <button type="submit" class="btn btn-primary btn-default font-weight-bold">Đăng nhập</button>
                                        <a href="<?=BASE_URL('account/password/reset');?>" class="ml-3">Quên mật khẩu?</a>
                                    </div>
                                </div>
                            </form>
                            <?php else: ?>
                            <form method="POST" action="<?=BASE_URL('ajax/login/verify');?>" aria-label="Xác thực 2FA">
                                <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                                <input type="hidden" name="stoken" value="<?= $_SESSION['stoken'] ?? ''; ?>">
                                <div class="row row15">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label for="" class="col-form-label font-weight-bold">
                                                    Mã 2FA
                                                </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="password" class="form-control" name="secret" maxlength="6" required placeholder="Mã Google Authenticator">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row row15">
                                    <div class="col-lg-8 offset-lg-4">
                                        <button type="submit" class="btn btn-primary btn-default font-weight-bold">Xác nhận</button>
                                    </div>
                                </div>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <?php if (!empty($site['google_client_id']) && !empty($site['google_client_secret'])): ?>
                            <div class="row row15">
                                <div class="col-12">
                                    <a class="button-social" href="<?= BASE_URL('account/google'); ?>">
                                        <img src="<?= BASE_URL('assets/client/images/gg-logo.png'); ?>" alt=""> Đăng nhập bằng Google
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($site['facebook_client_id']) && !empty($site['facebook_client_secret'])): ?>
                            <div class="row row15">
                                <div class="col-12">
                                    <a class="button-social" href="<?= BASE_URL('account/facebook'); ?>">
                                        <img src="<?= BASE_URL('assets/client/images/fb-logo.png'); ?>" alt=""> Đăng nhập bằng Facebook
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $script_footer = <<<'HTML'
<script>
$("#login-form").on("submit", function(e) {
    e.preventDefault();
    const btn = $(this).find('button[type="submit"]');
    btn.prop('disabled', true);
    $.ajax({
        url: $(this).attr('action'),
        method: "POST",
        dataType: "JSON",
        data: $(this).serialize(),
        success: function(response) {
            if (response.status === 'success') {
                window.location = response.redirect || '/';
                return;
            }
            if (response.status === 'verify') {
                window.location = response.redirect || '/account/login';
                return;
            }
            alert(response.msg || 'Đăng nhập thất bại');
            btn.prop('disabled', false);
        },
        error: function() {
            alert('Không thể kết nối máy chủ.');
            btn.prop('disabled', false);
        }
    });
});
</script>
HTML;
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Footer.php');
?>