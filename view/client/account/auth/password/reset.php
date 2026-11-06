<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    $title = "Khôi Phục Mật Khẩu";
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
                    <a href="<?=BASE_URL('account/password/reset');?>">Khôi phục mật khẩu</a>
                </li>
            </ol>
        </div>
    </div>
    <div class="section-gap">
        <div class="container">

            <?php render_msg_client(); ?>

            <div class="py-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="description mb-3">
                            <div class="text-left title"> Khôi phục mật khẩu </div>
                        </div>
                        <form method="POST" action="<?=BASE_URL('ajax/password/reset');?>" aria-label="Reset Password">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                            <div class="row row15">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label for="" class="col-form-label font-weight-bold"> E-Mail Address: </label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input id="email" type="email" class="form-control" name="email" value="" required>
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
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary"> Gửi mật khẩu vào Email </button>
                                </div>
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