<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Danh sách gói";
load_admin_data('service/service/pack-list');
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
                    <h4 class="card-title mb-0">Danh Sách Gói</h4>
                    <a href="<?= BASE_URL('admin/service/pack-add/' . $id); ?>" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus"></i> Thêm Gói
                    </a>
                </div>
                
                <div class="card-body">
                    <form method="GET" class="mb-3 row g-2 align-items-center">
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
                            <input type="number" name="id_pack" class="form-control" placeholder="ID gói" value="<?= anti_xss($_GET['id_pack'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="price_sort" class="form-select">
                                <option value="">-- Giá bán --</option>
                                <option value="price_asc" <?= ($_GET['price_sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Tăng dần</option>
                                <option value="price_desc" <?= ($_GET['price_sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Giảm dần</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">-- Trạng thái --</option>
                                <option value="1" <?= ($_GET['status'] ?? '') == '1' ? 'selected' : '' ?>>Đang Bán</option>
                                <option value="0" <?= ($_GET['status'] ?? '') == '0' ? 'selected' : '' ?>>Đã Bán</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search"></i> Tìm kiếm
                            </button>
                            <a href="<?=BASE_URL('admin/service/product-list');?>" class="btn btn-danger w-100">
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
                                    <th style="width: 30px;">
                                        
                                    </th>
                                    <th>ID Gói</th>
                                    <th>Tên Danh Mục, Sản Phẩm</th>
                                    <th>Giá Bán</th>
                                    <th>Thumbnail</th>
                                    <th>Trạng Thái</th>
                                    <th>Thời Gian</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-table">
                                <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Không tìm thấy dữ liệu.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($products as $row): ?>
                                        <?php $image_pack = $row['image'] ?? ''; ?>
                                        <tr class="service-row" data-id="<?= $row['id'] ?>" data-table="pack_service" data-column="sort_order" data-column-remove="id">
                                            <td style="display: none;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input checkbox_product" data-id="<?= $row['id'] ?>" name="checkbox_product" value="<?= $row['id'] ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="drag-handle d-flex align-items-center pe-2" style="cursor: move;">
                                                    <i class="ti ti-grip-vertical" style="font-size: 30px; color: #999;"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <?= anti_xss($row['id']) ?>
                                            </td>
                                            <td>
                                                <?= $row['category_name'] ?><br>
                                                Sản Phẩm:   <?= $row['group_name'] ?>
                                            </td>
                                            
                                            <td>
                                                <b class="text-primary"><?= anti_xss($row['name']) ?></b><br>
                                                Giá tiền: <?= format_number($row['price']) ?> <sup>đ</sup>
                                            </td>
                                            <td>
                                                <?php if ($group['type'] === 'image'): ?>
                                                    <?php if (!empty($image_pack)): ?>
                                                        <a data-fancybox="gallery" href="<?= BASE_URL($image_pack); ?>"
                                                        data-caption="<strong>ID Pack <?= anti_xss($row['id']) ?></strong>">
                                                            <img src="<?= BASE_URL($image_pack); ?>"
                                                                style="width:80px;border-radius:3px;">
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">Chưa có ảnh</span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Không có ảnh</span>
                                                <?php endif; ?>

                                            </td>
                                            <td>
                                                <div>
                                                    <input type="checkbox"
                                                        class="status-toggle"
                                                        id="switch<?=$row['id'];?>"
                                                        data-id="<?=$row['id'];?>"
                                                        data-table="pack_service"
                                                        data-column="status"
                                                        data-switch="success"
                                                        <?= $row['status'] == '1' ? 'checked' : '' ?>>
                                                    <label for="switch<?=$row['id'];?>"
                                                        data-on-label="Hiện"
                                                        data-off-label="Ẩn"
                                                        class="mb-0 d-block">
                                                    </label>
                                                </div>
                                            </td>
                                            <td> Ngày Tạo: <?= format_datetime($row['created_at']) ?> <br> Ngày Cập Nhật: <?= format_datetime($row['updated_at']) ?> </td>
                                            <td>
                                                <a href="/admin/service/pack-edit/<?= $row['id'] ?>" class="btn btn-sm btn-primary me-1">
                                                    <i class="ti ti-edit"></i> Sửa
                                                </a>
                                                <button class="btn btn-sm btn-danger btn-delete-single" 
                                                    data-id="<?= $row['id'] ?>" 
                                                    data-table="pack_service" 
                                                    data-column-remove="id">
                                                    <i class="ti ti-trash"></i> Xóa
                                                </button>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    if ($total > $limit) {

                        // base url dạng path param
                        $base_url = BASE_URL("admin/service/pack-list/{$id}");

                        // giữ lại filter, bỏ page + id
                        $query = $_GET;
                        unset($query['page'], $query['id']);

                        if (!empty($query)) {
                            $base_url .= '?' . http_build_query($query);
                        }

                        echo '<div class="text-center">' . phan_trang_admin(
                            $base_url,
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
<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php');
?>