<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    $title = "Đăng Ký";
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
                    <a href="<?=BASE_URL('account/register');?>">Đăng ký tài khoản</a>
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
                        Đăng ký
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-m1">
                            <form method="POST" action="<?=BASE_URL('ajax/register');?>" aria-label="Đăng ký">
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
                                                    value="" required="" autofocus="" placeholder="Tên đăng nhập">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row row15">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label for="" class="col-form-label font-weight-bold">
                                                    Họ và tên
                                                </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="name" value=""
                                                    placeholder="Họ và tên" id="fullname">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row row15">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label for="" class="col-form-label font-weight-bold">
                                                    Số đt hoặc email
                                                </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" autocomplete="off"
                                                    id="phoneOrEmail" name="phoneOrEmail" value=""
                                                    placeholder="Số đt hoặc email" required="">
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
                                        <button type="submit"
                                            class="btn btn-primary btn-default font-weight-bold w-100">
                                            Đăng ký
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <?php if (!empty($site['google_client_id']) && !empty($site['google_client_secret'])): ?>
                            <div class="row row15">
                                <div class="col-12">
                                    <a class="button-social" href="<?= BASE_URL('account/google'); ?>">
                                        <img src="<?= BASE_URL('assets/client/images/gg-logo.png'); ?>" alt=""> Đăng ký bằng Google
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($site['facebook_client_id']) && !empty($site['facebook_client_secret'])): ?>
                            <div class="row row15">
                                <div class="col-12">
                                    <a class="button-social" href="<?= BASE_URL('account/facebook'); ?>">
                                        <img src="<?= BASE_URL('assets/client/images/fb-logo.png'); ?>" alt=""> Đăng ký bằng Facebook
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
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Footer.php');
?>