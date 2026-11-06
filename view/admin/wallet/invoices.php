<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Quản lý hóa đơn";
load_admin_data('wallet/invoices');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Header.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Navbar.php');
?>
<div class="page-container">
    <div class="page-title-head d-flex align-items-center gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-16 text-uppercase fw-bold mb-0"><?=$title;?></h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0 fs-13">
                <li class="breadcrumb-item">
                    <a href="javascript: void(0);"><?=$_SERVER['HTTP_HOST'];?></a>
                </li>
                <li class="breadcrumb-item active"><?=$title;?></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <?php render_msg_admin(); ?>
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Danh Sách Hóa Đơn</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?= BASE_URL('admin/wallet/invoices'); ?>" class="mb-3 row g-2 align-items-center">
                        <div class="col-md-2">
                            <select name="limit" class="form-select">
                                <option value="">-- Số dòng / trang --</option>
                                <?php foreach ([5, 10, 20, 50, 100, 500, 1000, 5000, 10000, 15000, 20000, 30000, 40000, 50000] as $opt): ?>
                                    <option value="<?=$opt?>" <?= ($limit == $opt ? 'selected' : '') ?>>
                                        <?=format_number($opt)?> dòng
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="trans_id" class="form-control" placeholder="Mã giao dịch" value="<?= anti_xss($_GET['trans_id'] ?? $_GET['order_code'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="user_id" class="form-control" placeholder="ID khách hàng" value="<?= anti_xss($_GET['user_id'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="username" class="form-control" placeholder="Tên khách hàng" value="<?= anti_xss($_GET['username'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="content" class="form-control" placeholder="Nội dung" value="<?= anti_xss($_GET['content'] ?? $_GET['description'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="paygate" class="form-control">
                                <option value="" selected="selected">Cổng nạp</option>
                                <?php foreach ($deposit_banks as $bank): ?>
                                    <option value="<?= $bank['bankName'] ?>" <?= ($_GET['paygate'] ?? '') === $bank['bankName'] ? 'selected' : '' ?>><?= $bank['textNameBank'] ?></option>
                                <?php endforeach; ?>
                                <option value="VIETTEL" <?= @$_GET['paygate'] === 'VIETTEL' ? 'selected' : '' ?>>Thẻ cào Viettel</option>
                                <option value="VINAPHONE" <?= @$_GET['paygate'] === 'VINAPHONE' ? 'selected' : '' ?>>Thẻ cào Vinaphone</option>
                                <option value="MOBIFONE" <?= @$_GET['paygate'] === 'MOBIFONE' ? 'selected' : '' ?>>Thẻ cào Mobifone</option>
                                <option value="GARENA" <?= @$_GET['paygate'] === 'GARENA' ? 'selected' : '' ?>>Thẻ cào Garena</option>
                                <option value="ZING" <?= @$_GET['paygate'] === 'ZING' ? 'selected' : '' ?>>Thẻ cào Zing</option>
                                <option value="VCOIN" <?= @$_GET['paygate'] === 'VCOIN' ? 'selected' : '' ?>>Thẻ cào Vcoin</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="order_type" class="form-control">
                                <option value="">-- Loại giao dịch --</option>
                                <option value="buy" <?= ($_GET['order_type'] ?? '') === 'buy' ? 'selected' : '' ?>>Mua hàng</option>
                                <option value="deposit" <?= ($_GET['order_type'] ?? '') === 'deposit' ? 'selected' : '' ?>>Nạp tiền</option>
                                <option value="upgrade" <?= ($_GET['order_type'] ?? '') === 'upgrade' ? 'selected' : '' ?>>Nâng cấp</option>
                                <option value="extend" <?= ($_GET['order_type'] ?? '') === 'extend' ? 'selected' : '' ?>>Gia hạn</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="from_date" class="form-control"
                                value="<?= anti_xss($_GET['from_date'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="to_date" class="form-control"
                                value="<?= anti_xss($_GET['to_date'] ?? '') ?>">
                        </div>

                        
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search"></i> Tìm kiếm
                            </button>
                            <a href="<?=BASE_URL('admin/wallet/invoices');?>" class="btn btn-danger w-100">
                                <i class="ti ti-trash"></i> Xóa lọc
                            </a>
                        </div>
                    </form>
                    <div class="d-flex mb-3 align-items-center flex-wrap gap-2">
                        <div class="btn-list" id="bulk-action-buttons" style="display: none;">
                            <button type="button" id="btn_delete_selected"
                                class="btn btn-outline-danger btn-sm btn-rounded shadow-danger btn-wave">
                                <i class="ti ti-trash"></i> Xóa nhanh
                            </button>
                            <span id="selected-counter" class="ms-2 align-self-center text-primary fw-semibold"></span>
                        </div>
                        <div class="ms-auto d-flex gap-2">
                            <button id="select-all-btn" type="button"
                                class="btn btn-outline-primary btn-sm btn-rounded shadow btn-wave">
                                <i class="ti ti-checks"></i> Chọn tất cả
                            </button>
                            <button id="deselect-all-btn" type="button"
                                class="btn btn-outline-primary btn-sm btn-rounded shadow btn-wave">
                                <i class="ti ti-x"></i> Bỏ chọn tất cả
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0 text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Mã GD</th>
                                    <th scope="col">Khách hàng</th>
                                    <th scope="col">Loại giao dịch</th>
                                    <th scope="col">Mô-đun</th>
                                    <th scope="col">Cổng thanh toán</th>
                                    <th scope="col">Số tiền</th>
                                    <th scope="col">Trạng Thái</th>
                                    <th scope="col">Nội dung</th>
                                    <th scope="col">Thời gian</th>
                                    <th scope="col"> Hành Động </th>
                                </tr>
                            </thead>
                            <tbody id="sortable-table">
                                <?php if (empty($invoices)): ?>
                                    <tr>
                                        <td colspan="11" class="text-center">Không tìm thấy dữ liệu.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($invoices as $row): ?>
                                        <tr class="service-row" data-id="<?= $row['id'] ?>" data-table="invoices" data-column="sort_order" data-column-remove="id">
                                            <td style="display: none;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input checkbox_product" data-id="<?= $row['id'] ?>" name="checkbox_product" value="<?= $row['id'] ?>">
                                                </div>
                                            </td>
                                            <th scope="row"><?=$row['id']?></th>
                                            <th scope="row">
                                                Mã GD Hóa Đơn: <span class="badge badge-soft-info"><?=$row['order_code']?></span><br>
                                                Mã GD Đơn Hàng: <span class="badge badge-soft-danger"><?=$row['order_code_service'] ?? '---' ?></span>
                                            </th>
                                            <th scope="row">
                                                <a class="text-primary" href="<?=BASE_URL('admin/users/'.$row['user_id'].'/edit');?>">
                                                    <?= $row['username'] ?> [ID <?=$row['user_id'];?>]
                                                </a>
                                            </td>
                                            <th scope="row"><?=type_action($row['order_type'])?></th>
                                            <th scope="row"><span class="badge badge-outline-dark"><?=$row['module'] ?? '---' ?></span></th>
                                            <th scope="row"><span class="badge badge-outline-info"><?= $row['paygate'] ?? 'Số dư'; ?></span></th>
                                            <th scope="row">
                                                Tổng Phí: <?= format_number($row['net_amount']) ?><sup>đ</sup><br> 
                                                Thanh Toán: <?= format_number($row['pay_amount']) ?><sup>đ</sup>  
                                            </th>
                                            <th scope="row"><span class="<?= $row['status_info']['class'] ?>"><?= $row['status_info']['text'] ?></span></th>
                                            <th scope="row" class="text-wrap" style="max-width: 220px; white-space: normal;">
                                                <?= anti_xss($row['description'] ?? '') ?>
                                            </th>
                                            <th scope="row"><span class="badge badge-soft-danger"><?=format_datetime($row['created_at'])?></span></th>
                                            <th scope="row">
                                                <?php if (empty($row['card_info'])): ?>
                                                    <a href="/admin/wallet/invoice-view/<?= $row['order_code'] ?>" 
                                                    class="btn btn-sm btn-info">
                                                        <i class="ti ti-eye me-1"></i> Chi tiết
                                                    </a>
                                                <?php else: ?>
                                                    <button type="button"
                                                            class="btn btn-sm btn-warning"
                                                            onclick="modal_chitiet(<?= (int)$row['id'] ?>)">
                                                        <i class="ti ti-eye me-1"></i> Chi tiết
                                                    </button>
                                                <?php endif; ?>

                                                <button class="btn btn-sm btn-danger btn-delete-single" 
                                                    data-id="<?= $row['id'] ?>" 
                                                    data-table="invoices" 
                                                    data-column-remove="id">
                                                    <i class="ti ti-trash"></i> Xóa
                                                </button>
                                            </th>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                        if ($total > $limit) {
                            echo '<div class="text-center">' . phan_trang_admin(
                                BASE_URL('admin/wallet/invoices?' . http_build_query(array_merge($_GET, ['page' => null]))),
                                $start,
                                $total,
                                $limit
                            ) . '</div>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modalContainer"></div>

<script>
function modal_chitiet(id) {
    $.ajax({
        url: "<?= BASE_URL('ajax/action/modal-view'); ?>",
        type: "POST",
        data: {
            type: "the_cao",
            id: id,
            csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        success: function (response) {

            // Inject modal HTML
            $('#modalContainer').html(response);

            // LẤY ĐÚNG ID MODAL TỪ HTML TRẢ VỀ
            const modalEl = document.getElementById('CharginModal');

            if (!modalEl) {
                console.error('Không tìm thấy modal!');
                return;
            }

            // Bootstrap 5 show modal
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        },
        error: function (err) {
            console.error('AJAX error:', err);
        }
    });
}
</script>

<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php');
?>