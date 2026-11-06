<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Chỉnh sửa thành viên";
load_admin_data('account/edit-user');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Header.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Navbar.php');
?>
<div class="page-container">
    <div class="page-title-head d-flex align-items-center gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-16 text-uppercase fw-bold mb-0"><?= $title; ?></h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0 fs-13">
                <li class="breadcrumb-item"><a href="javascript: void(0);"><?=$_SERVER['HTTP_HOST'];?></a></li>
                <li class="breadcrumb-item active"><?= $title; ?></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <?php render_msg_admin(); ?>
            <div class="col-12">
                <div class="mt-4 mt-md-0">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal-blance" class="btn btn-sm btn-wave btn-success me-1 mb-3 push waves-effect waves-light" onclick="action_blance('congtien');">
                        <i class="fa fa-fw fa-plus"></i> Cộng Tiền 
                    </button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal-blance" class="btn btn-sm btn-wave btn-danger me-1 mb-3 push waves-effect waves-light" onclick="action_blance('trutien');">
                        <i class="fa fa-fw fa-minus"></i> Trừ Tiền 
                    </button>
                    <a type="button" href="<?= BASE_URL('admin/history/transaction?user_id=' . $user['id']); ?>" class="btn btn-sm btn-wave btn-primary me-1 mb-3 push waves-effect waves-light">
                        <i class="fa fa-fw fa-history"></i> Lịch Sử Giao Dịch 
                    </a>
                    <a type="button" href="<?= BASE_URL('admin/history/logs?user_id=' . $user['id']); ?>" class="btn btn-sm btn-wave btn-dark me-1 mb-3 push waves-effect waves-light">
                        <i class="fa fa-fw fa-history"></i> Lịch Sử Hoạt Động
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                        
                        <div class="row g-3">
                            <!-- Left -->
                            <div class="col-md-6">
                                <label class="form-label">Tên Tài Khoản</label>
                                <input type="text" class="form-control" value="<?=$user['username'];?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Họ Và Tên</label>
                                <input type="text" class="form-control" name="name" value="<?=$user['name'];?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?=$user['email'];?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Số Điện Thoại</label>
                                <input type="text" class="form-control" name="phone" value="<?=$user['phone'];?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Token (<span class="text-danger">*</span>)</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="token" value="<?=$user['token'];?>" readonly>
                                    <button type="button" id="show_token" class="btn btn-danger" onclick="toggleTokenVisibility()">Show</button>
                                </div>
                                <small>Bảo mật thông tin này vì kẻ xấu có thể thực hiện đăng nhập tài khoản bằng Token</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Mật Khẩu (<span class="text-danger">*</span>)</label>
                                <input type="text" class="form-control" name="password" placeholder="**********">
                                <small>Nhập mật khẩu cần thay đổi, hệ thống sẽ tự động mã hóa (bỏ trống nếu không muốn thay đổi)</small>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Xác Thực 2 Bước</label>
                                <select class="form-select" name="twofactor">
                                    <option <?=empty($user['twofactor'])?'selected':'';?> value="">Tắt</option>
                                    <option <?=$user['twofactor']==='Email'?'selected':'';?> value="Email">Email</option>
                                    <option <?=$user['twofactor']==='GoogleAuth'?'selected':'';?> value="GoogleAuth">Google 2FA</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Nhóm (<span class="text-danger">*</span>)</label>
                                <select class="form-select" name="admin">
                                    <option <?=$user['admin']== 0?'selected':'';?> value="0">Thành Viên</option>
                                    <option <?=$user['admin']== 1?'selected':'';?> value="1">Quản Trị Viên</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Trạng Thái Tài Khoản</label>
                                <select class="form-select" name="banned">
                                    <option <?=$user['banned']==='0'?'selected':'';?> value="0"> Hoạt Động</option>
                                    <option <?=$user['banned']==='1'?'selected':'';?> value="1"> Bị Khóa</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Lý Do Khóa</label>
                                <input type="text" class="form-control" value="<?=$user['failed_reason'];?>" disabled>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Số Lần Đăng Nhập Sai</label>
                                <input type="number" class="form-control" value="<?=format_number($user['failed']);?>" disabled>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Số Dư</label>
                                <input type="text" class="form-control" value="<?=format_number($user['money']);?>đ" disabled>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tổng Nạp</label>
                                <input type="text" class="form-control" value="<?=format_number($user['total_money']);?>đ" disabled>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Đăng Nhập Với</label>
                                <input type="text" class="form-control" value="<?= !empty($user['social_type']) ? $user['social_type'] : 'Login'; ?>" disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Địa Chỉ IP Dùng Để Đăng Nhập</label>
                                <input type="text" class="form-control" value="<?=$user['ip'];?>" disabled>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Thiết Bị Đăng Nhập</label>
                                <input type="text" class="form-control" value="<?=$user['device'];?>" disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Đăng Ký Tài Khoản Vào Lúc</label>
                                <input type="text" class="form-control" value="<?=$user['create_date'];?>" disabled>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Đăng Nhập Gần Nhất</label>
                                <input type="text" class="form-control" value="<?=$user['update_date'];?>" disabled>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="<?=BASE_URL('admin/users');?>" class="btn btn-danger">
                                <i class="ti ti-arrow-left"></i> Quay Lại
                            </a>
                            <button type="submit" name="submit_user" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> Lưu Lại
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-blance" tabindex="-1" aria-labelledby="modal-block-popout" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel2"> Cập Nhật Số Dư </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label" for="example-hf-email"> Giao Dịch:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="blance_select" id="blance_select">
                                <option value="congtien"> Cộng Tiền </option>
                                <option value="trutien"> Trừ Tiền </option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label" for="example-hf-email"> Số Tiền:</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="amountInput"
                                placeholder="Nhập số tiền giao dịch" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label"
                            for="example-hf-email"> Lý do (nếu có):</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="reasonInput"></textarea>
                        </div>
                    </div>
                    
                    <div class="mb-4" id="save_rose" style="display: none;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="addNap">
                            <label class="form-check-label" for="addNap">
                                Tính là tiền khách nạp
                            </label>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-hero btn-danger" data-bs-dismiss="modal"><i class="fa fa-fw fa-times me-1"></i> Hủy </button>
                    <button type="submit" name="submit_balance" class="btn btn-hero btn-success"><i class="fa fa-fw fa-plus me-1"></i> Thực Hiện </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function action_blance(data){
    if(data == 'congtien'){
        $("#blance_select").val(data);
        hetcuu = 'congtien';
        document.getElementById("save_rose").style.display = 'block';
    } else if(data == 'trutien'){
        $("#blance_select").val(data);
        hetcuu = 'trutien';
        document.getElementById("save_rose").style.display = 'none';
    }
}
function toggleTokenVisibility() {
    var input = document.getElementById('token');
    var button = document.getElementById('show_token');
    if (input.type === 'password') {
        input.type = 'text';
        button.textContent = 'Hide';
    } else {
        input.type = 'password';
        button.textContent = 'Show';
    }
}
</script>
<?php 
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php'); 
?>
