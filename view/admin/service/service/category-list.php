<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Quản lý danh mục";
load_admin_data('service/service/category-list');
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
                    <h4 class="card-title mb-0">Danh Sách Danh Mục</h4>
                    <a href="/admin/service/category-add" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus"></i> Thêm Danh Mục
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
                            <input type="number" name="id" class="form-control" placeholder="ID danh mục" value="<?= anti_xss($_GET['id'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="name" class="form-control" placeholder="Tên danh mục" value="<?= anti_xss($_GET['name'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">-- Trạng thái --</option>
                                <option value="1" <?= ($_GET['status'] ?? '') == '1' ? 'selected' : '' ?>>Hiển thị</option>
                                <option value="0" <?= ($_GET['status'] ?? '') == '0' ? 'selected' : '' ?>>Ẩn</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search"></i> Tìm kiếm
                            </button>
                            <a href="<?=BASE_URL('admin/service/category-list');?>" class="btn btn-danger w-100">
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
                                    <th>Tên Danh Mục</th>
                                    <th>Thumbnail</th>
                                    <th>Trạng Thái</th>
                                    <th>Thời Gian</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-table">
                                <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Không tìm thấy dữ liệu.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($products as $row): ?>
                                        <?php $type = source_code_type($row['type'] ?? ''); ?>
                                        <tr class="service-row" data-id="<?= $row['id'] ?>" data-table="category_service" data-column="sort_order" data-column-remove="id">
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
                                                <?= anti_xss($row['name']) ?><br>
                                                Slug:   <b class="text-danger">
                                                            <?= (mb_strlen($row['slug'], 'UTF-8') > 50) 
                                                                ? mb_substr($row['slug'], 0, 50, 'UTF-8') . '...' 
                                                                : ($row['slug'] ?: 'Không Có'); ?>
                                                        </b>
                                            </td>
                                            <td>
                                                <a data-fancybox="gallery" href="<?= BASE_URL($row['image']) ?>" data-caption="<strong> <?= anti_xss($row['name']) ?> </strong>">
                                                    <img src="<?= BASE_URL($row['image']) ?>" class="img-fluid" alt="<?= anti_xss($row['name']) ?>" style="width: 300px; border-radius: 3px;">
                                                </a>
                                            </td>
                                            <td>
                                                <div>
                                                    <input type="checkbox"
                                                        class="status-toggle"
                                                        id="switch<?=$row['id'];?>"
                                                        data-id="<?=$row['id'];?>"
                                                        data-table="category_service"
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
                                                <a href="/admin/service/category-edit/<?= $row['id'] ?>" class="btn btn-sm btn-primary me-1">
                                                    <i class="ti ti-edit"></i> Sửa
                                                </a>
                                                <button class="btn btn-sm btn-danger btn-delete-single" 
                                                    data-id="<?= $row['id'] ?>" 
                                                    data-table="category_service" 
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
                            echo '<div class="text-center">' . phan_trang_admin(
                                BASE_URL('admin/service/category-list?' . http_build_query(array_merge($_GET, ['page' => null]))),
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