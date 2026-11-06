<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Chi tiết đơn hàng";
load_admin_data('service/service/order-view');
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
                <li class="breadcrumb-item"><a href="javascript:void(0);"><?=$_SERVER['HTTP_HOST'];?></a></li>
                <li class="breadcrumb-item active"><?= $title; ?></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <?php render_msg_admin(); ?>
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">

                        <div class="row">

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Danh mục</label>
                                <input type="text" value="<?= $category_name ?>" class="form-control" readonly>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Sản phẩm</label>
                                <input type="text" value="<?= $group_name ?>" class="form-control" readonly>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Gói</label>
                                <input type="text" value="<?= $pack_name ?>" class="form-control" readonly>
                            </div>
                            <?php foreach ($display_inputs as $input): ?>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label"><?= $input['label']; ?></label>
                                    <div class="input-group">
                                        <input type="text" value="<?= $input['value']; ?>" class="form-control" readonly id="copy_<?= $input['name'] ?>">
                                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('copy_<?= $input['name'] ?>')">
                                            <i class="ti ti-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="99" <?=$row['status']== 99 ? 'selected':''?>>Chờ xử lý</option>
                                    <option value="1" <?=$row['status']== 1 ? 'selected':''?>>Thành công</option>
                                    <option value="2" <?=$row['status']== 2 ? 'selected':''?>>Đang xử lý</option>
                                    <option value="3" <?=$row['status']== 3 ? 'selected':''?>>Hủy và không hoàn tiền</option>
                                    <option value="4" <?=$row['status']== 4 ? 'selected':''?>>Hủy và hoàn tiền</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Ghi chú</label>
                                <textarea type="text" name="note" class="form-control"><?=$row['note'] ?? 'Không rõ';?></textarea>
                            </div>
                            <?php if ($serviceData['service_additional']['image']): ?>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Hình ảnh</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <a data-fancybox="gallery" href="<?=BASE_URL($serviceData['service_additional']['image'])?>" data-caption="&lt;strong&gt;<?= $pack_name ?>&lt;/strong&gt;">
                                        <img src="<?=BASE_URL($serviceData['service_additional']['image'])?>" class="img-fluid" alt="<?= $pack_name ?>" style="height:70px;border-radius:6px">
                                    </a>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> Lưu thay đổi
                            </button>
                            <a href="<?=BASE_URL('admin/service/orders');?>" class="btn btn-secondary">Quay lại</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function copyToClipboard(elementId) {
  var copyText = document.getElementById(elementId);
  copyText.select();
  copyText.setSelectionRange(0, 99999); 
  navigator.clipboard.writeText(copyText.value).then(() => {
     
  });
}
</script>
<?php 
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php'); 
?>