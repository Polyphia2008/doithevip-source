<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    $title = "Bảo Mật Tài Khoản Bằng GoogleAuth";
    load_client_data('account/security/active/google-auth');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Header.php');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Navbar.php');
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
                <li class="breadcrumb-item">
                    <a>Kích hoạt bảo mật</a>
                </li>
            </ol>
        </div>
    </div>
    <div class="section-gap">
        <div class="container">
            <h4>
                <span class="text-uppercase">Tạo khóa bảo mật 2 lớp</span>
            </h4>
            <div class="blockContent">
                <div class="row">
                    <div class=" col-md-12">
                        <div class="card">
                            <form method="POST" action="<?=BASE_URL('ajax/security/action');?>" accept-charset="UTF-8">
                                <input type="hidden" class="input-text" name="csrf_token" value="<?= generate_csrf_token() ?>" required>
                                <div class="card-body row">
                                    <div class="col-md-12">
                                        <p>Để kích hoạt dịch vụ này, vui lòng tải ứng dụng Google Authenticator trên ChPlay nếu bạn dùng Android, và trên Appstore nếu bạn dùng IOS</p>
                                        <p>Android: <a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&amp;hl=vi">Tải ở đây</a>
                                        </p>
                                        <p>IOS: <a target="_blank" href="https://apps.apple.com/vn/app/google-authenticator/id388497605?l=vi">Tải ở đây</a>
                                        </p>
                                        <p>Sau khi cài đặt xong, bạn hãy mở app đó lên, nhấn vào nút thêm, sau đó quét mã vạch hiện ở ngay dưới. Lúc này bạn đã tạo được mã xác thực, sau đó điền vào để xác thực.</p>
                                        <p>Lưu ý: Cứ 30 giây hệ thống sẽ tự động tạo mã mới. Hãy lưu trữ khóa bảo mật để dùng trong trường hợp bạn mất điện thoại</p>
                                        <p class="text-danger" style="margin-top: 20px">KHÓA BẢO MẬT: <?= $twofactor_secret; ?></p>
                                        <p>
                                            <?= $qr_code_inline; ?>
                                        </p>
                                        <div class="form-group">
                                            <label for="ips">Mã xác thực:</label>
                                            <br>
                                            <input name="secret" maxlength="6" type="text" class="form-control" placeholder="Nhập mã trên App sau khi quét thành công" value="">
                                            <input name="type" value="GoogleAuth" type="hidden">
                                            <input name="status" value="active" type="hidden">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Tạo xác thực</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Footer.php');
?>