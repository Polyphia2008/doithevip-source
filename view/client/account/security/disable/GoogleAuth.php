<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    $title = "Bảo Mật Tài Khoản Bằng GoogleAuth";
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
                            <div class="position-relative mb-2">
                                <label for="" class="col-form-label font-weight-bold">Mã xác thực:</label>
                                <input type="password" class="form-control" name="secret" maxlength="6" placeholder="Nhập mã xác thực Google Auth" required="">
                            </div>
                            <div class="form-group">
                                <input name="type" value="GoogleAuth" type="hidden">
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