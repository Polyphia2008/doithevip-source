<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Quản lý chiến dịch Flash Sale";
load_admin_data('modules/flash-sale/flash-sale-add-product');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Header.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Navbar.php');
?>
<div class="page-container">

    <!-- TITLE -->
    <div class="page-title-head d-flex align-items-center gap-2 mb-3">
        <div class="flex-grow-1">
            <h4 class="fs-16 fw-bold text-uppercase mb-0"><?=$title;?></h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 fs-13">
                <li class="breadcrumb-item"><a href="#"><?=$_SERVER['HTTP_HOST'];?></a></li>
                <li class="breadcrumb-item">
                    <a href="<?=BASE_URL('admin/modules/flash-sale-list');?>">Flash Sale</a>
                </li>
                <li class="breadcrumb-item active">Sản phẩm</li>
            </ol>
        </div>
    </div>

    <div class="row">
    <div class="col-xl-12">

        <!-- ADD PRODUCT -->
        <div class="card">
            <div class="card-header border-bottom border-dashed">
                <h4 class="card-title mb-0">
                    Thêm sản phẩm Flash Sale <b class="text-danger"><?=$row['name'];?></b>
                </h4>
            </div>
            <div class="card-body">
                <form id="addProductsToFlashSaleForm">
                    <div id="productList">

                        <div class="product-item mb-3">
                            <div class="row g-2 align-items-end">

                                <div class="col-md-3">
                                    <label class="form-label">Loại sản phẩm</label>
                                    <select name="productType[]" class="form-select product-type" required>
                                        <option value="">-- Chọn loại --</option>
                                        <option value="account">Tài khoản tự chọn</option>
                                        <option value="groups_account">Danh mục tài khoản</option>
                                        <option value="groups_service">Danh mục dịch vụ</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Sản phẩm</label>
                                    <select name="productID[]" class="form-select product-select" required>
                                        <option value="">-- Chọn sản phẩm --</option>
                                    </select>
                                </div>

                                <div class="col-md-2 field-discount">
                                    <label class="form-label">Giá giảm</label>
                                    <input type="number" name="discountPrice[]" class="form-control">
                                </div>

                                <div class="col-md-2 field-description" style="display:none;">
                                    <label class="form-label">Mô tả</label>
                                    <textarea name="description[]" class="form-control" rows="1"></textarea>
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label">Xóa</label>
                                    <button type="button" class="btn btn-danger w-100 remove-product">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-plus"></i> Thêm sản phẩm
                        </button>
                        <button type="button" id="addMoreProducts" class="btn btn-primary">
                            <i class="ti ti-copy"></i> Thêm dòng
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- LIST -->
        <div class="card mt-4">
            <div class="card-header border-bottom border-dashed">
                <h4 class="card-title mb-0">
                    Danh sách sản phẩm Flash Sale
                    <b class="text-danger"><?=$row['name'];?></b>
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered table-hover align-middle text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>ID sản phẩm</th>
                                <th>Loại</th>
                                <th>Nội dung</th>
                                <th>Xóa</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($flash_products as $info): ?>
                            <tr>
                                <td><?=$info['id'];?></td>
                                <td><?=$info['product_id'];?></td>
                                <td><span class="badge bg-dark"><?=strtoupper($info['product_type']);?></span></td>
                                <td>
                                    <?php if($info['product_type']==='account'): ?>
                                        <span class="badge bg-success">
                                            Giảm <?=format_number($info['discount_price']);?> đ
                                        </span>
                                    <?php else: ?>
                                        <?=!empty($info['description']) ? nl2br(htmlspecialchars($info['description'])) : '<i>Không có mô tả</i>';?>
                                    <?php endif;?>
                                </td>
                                <td><?=format_datetime($info['created_at']);?></td>
                                <td>
                                    <button class="btn btn-sm btn-danger btn-delete-single" 
                                        data-id="<?= $info['id'] ?>" 
                                        data-table="flash_sale_products" 
                                        data-column-remove="id">
                                        <i class="ti ti-trash"></i> Xóa
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    </div>
</div>

<script>
const accountOptions = <?=json_encode($accountOptions);?>;
const groupOptions = <?=json_encode($groupOptions);?>;
const groupdichvuOptions = <?=json_encode($groupdichvuOptions);?>;

function loadOptions(type, select){
    let html = '<option value="">-- Chọn sản phẩm --</option>';
    if(type==='account') html += accountOptions.map(p=>`<option value="${p.id}">${p.text}</option>`).join('');
    if(type==='groups_account') html += groupOptions.map(p=>`<option value="${p.id}">${p.text}</option>`).join('');
    if(type==='groups_service') html += groupdichvuOptions.map(p=>`<option value="${p.id}">${p.text}</option>`).join('');
    select.innerHTML = html;

    const row = select.closest('.row');
    row.querySelector('.field-discount').style.display = type==='account'?'block':'none';
    row.querySelector('.field-description').style.display = type==='account'?'none':'block';
}

document.addEventListener('change',e=>{
    if(e.target.classList.contains('product-type')){
        loadOptions(e.target.value, e.target.closest('.row').querySelector('.product-select'));
    }
});

document.getElementById('addMoreProducts').onclick = ()=>{
    const item = document.querySelector('.product-item').cloneNode(true);
    item.querySelectorAll('input,select,textarea').forEach(e=>e.value='');
    document.getElementById('productList').appendChild(item);
};

document.getElementById('productList').onclick = e=>{
    if(e.target.closest('.remove-product')){
        e.target.closest('.product-item').remove();
    }
};

const flashSaleID = <?=$id;?>;

document.getElementById('addProductsToFlashSaleForm').onsubmit = e => {
    e.preventDefault();

    const data = [...document.querySelectorAll('.product-item')].map(row => ({
        flashSaleID,
        productType: row.querySelector('[name="productType[]"]').value,
        productID: row.querySelector('[name="productID[]"]').value,
        discountPrice: row.querySelector('[name="discountPrice[]"]')?.value || 0,
        description: row.querySelector('[name="description[]"]')?.value || ''
    }));

    fetch('/ajax/admin/modules/flash-sale', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(d => {
        Swal.close();

        Swal.fire({
            icon: d.status === 'success' ? 'success' : 'error',
            title: d.status === 'success' ? 'Thành công' : 'Thất bại',
            text: d.msg,
            confirmButtonText: 'OK'
        }).then(() => {
            if (d.status === 'success') {
                location.reload();
            }
        });
    })
    .catch(() => {
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Không thể kết nối tới máy chủ!'
        });
    });
};

</script>

<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php');
?>