<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
load_client_data('service/account-list');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Header.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Navbar.php');
?>
<div class="nav-15rfyaw nav-spacing ws-my-4 ws-px-2">
    <div class="ws-grid ws-grid-cols-12 ws-gap-2">
            <div class="ws-col-span-12">
                <div class="ws-rounded-lg ws-pt-2 ws-mb-4">
                    <div>
                        <label>
                            <p class="chakra-text nav-1cjtwfp">Danh mục</p>
                        </label>
                        <h2 class="ws-block ws-font-bold ws-text-lg ws-text-red-500"><?=$row['name'];?></h2>
                        <div class="ws-bg-white ws-rounded ws-px-2">
                            <div class="ws-py-2">
                                <b class="ws-block ws-mb-2 ws-font-medium ws-text-sm ws-text-zinc-800">Lọc tìm kiếm</b>
                                <div class="ws-grid ws-grid-cols-12 ws-gap-4">

                                    <div class="ws-col-span-12 md:ws-col-span-3">
                                        <div class="el-select el-select--large" style="width: 100%;">
                                            <div class="select-trigger el-tooltip__trigger el-tooltip__trigger">
                                                <div class="el-input el-input--large el-input--suffix" style="">
                                                    <input class="el-input__inner" type="text" id="id" autocomplete="off" tabindex="0" placeholder="ID" style="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ws-col-span-12 md:ws-col-span-3">
                                        <div class="el-select el-select--large" style="width: 100%;">
                                            <div class="select-trigger el-tooltip__trigger el-tooltip__trigger">
                                                <div class="el-input el-input--large el-input--suffix">
                                                    <select class="el-input__inner" placeholder="Tìm theo giá" id="price">
                                                        <option value="">Tìm theo giá</option>
                                                        <option value="duoi-50k">Dưới 50K</option>
                                                        <option value="tu-50k-200k">Từ 50K - 200K</option>
                                                        <option value="tu-200k-500k">Từ 200K - 500K</option>
                                                        <option value="tu-500k-1-trieu">Từ 500K - 1 Triệu</option>
                                                        <option value="tren-1-trieu">Trên 1 Triệu</option>
                                                        <option value="tren-5-trieu">Trên 5 Triệu</option>
                                                        <option value="tren-10-trieu">Trên 10 Triệu</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php foreach ($filter_fields as $i => $field): ?>
                                        <?php if (($field['show'] ?? '') !== 'on') continue; ?>
                                        <div class="ws-col-span-12 md:ws-col-span-3" id="datatk-<?= $i; ?>" style="display: none;">
                                            <div class="el-select el-select--large" style="width: 100%;">
                                                <?php if(($field['type'] ?? '') == 'input'){ ?>
                                                <div class="select-trigger el-tooltip__trigger el-tooltip__trigger">
                                                    <div class="el-input el-input--large el-input--suffix" style="">
                                                        <input class="el-input__inner" type="text" id="<?= $field['name'] ?? ''; ?>" autocomplete="off" tabindex="0" placeholder="<?= $field['label'] ?? ''; ?>" style="">
                                                    </div>
                                                </div>
                                                <?php } elseif(($field['type'] ?? '') == 'number'){ ?>
                                                <div class="select-trigger el-tooltip__trigger el-tooltip__trigger">
                                                    <div class="el-input el-input--large el-input--suffix" style="">
                                                        <input class="el-input__inner" type="text" id="<?= $field['name'] ?? ''; ?>" autocomplete="off" tabindex="0" placeholder="<?= $field['label'] ?? ''; ?>" style="">
                                                    </div>
                                                </div>
                                                <?php }elseif(($field['type'] ?? '') == 'select'){ ?>
                                                <div class="select-trigger el-tooltip__trigger el-tooltip__trigger">
                                                    <div class="el-input el-input--large el-input--suffix">
                                                        <select class="el-input__inner" placeholder="<?= $field['label'] ?? ''; ?>" id="<?= $field['name'] ?? ''; ?>">
                                                            <option value=""><?= $field['label'] ?? ''; ?></option>
                                                            <?php foreach (explode('|', $field['value'] ?? '') as $opt) { ?>
                                                            <option value="<?= trim($opt); ?>"><?= trim($opt); ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php if ($countShow > 0): ?>
                                    <div class="ws-col-span-12 md:ws-col-span-3">
                                        <button id="toggleButton" onclick="toggleAllVisibility()" type="button" class="el-button el-button--warning el-button--large ws-w-full" style="">
                                            <span class="">Tìm kiếm nâng cao</span>
                                        </button>
                                    </div>
                                    <?php endif; ?>
                                    <div class="ws-col-span-12 md:ws-col-span-3">
                                        <button onclick="fitler()" type="button" class="el-button el-button--primary el-button--large ws-w-full" style="">
                                            <span class=""> Tìm kiếm </span>
                                        </button>
                                    </div>
                                    <div class="ws-col-span-12 md:ws-col-span-3">
                                        <button onclick="load_account()" type="button" class="el-button el-button--info el-button--large ws-w-full" style="">
                                            <span class=""> Tất cả </span>
                                        </button>

                                    </div>
                                </div>
                            </div>
                            <?php if ($show_noti_sell && $has_group_orders): ?>
                            <div class="ws-home ws-border-t ws-border-dashed  tw-mb-4 tw-text-red-500 tw-font-bold tw-px-2 tw-bg-white tw-rounded tw-py-2 tw-relative ws-px-2 ws-mx-auto ws-my-4">
                                <span class="border-radius-5px tw-absolute tw-flex tw-items-center tw-justify-center tw-text-xl tw-h-10 tw-w-10 tw-bg-white md:tw-rounded" style="top: 0px; left: 0px; z-index: 5; color:#dc2626"><i class="fa-solid fa-bell"></i></span>
                                <div class="tw-flex tw-w-full tw-items-center tw-notification-home">
                                    <marquee scrollamount="20">
                                        <?php foreach ($group_orders as $order) : ?>
                                        <a style="text-align:center;">
                                            <span class="">
                                            <span style="background-color: #FBC02D;color: black; padding: 5px; border-radius: 4px">ID <?= $order['user_id'] ?></span>
                                                đã mua <?= $order['service_type_text'] ?> <span style="color: #279EFF">#<?= $order['service_id'] ?></span> giá <span style="color: #4D2DB7"><?= $order['amount_formatted'] ?><sup class="text-muted">đ</sup></span> -
                                                <span class="text-muted"> cách đây <span style="color: red"><?= $order['time_ago'] ?></span></span>
                                            </span> <b style="">|</b>
                                        </a>
                                        <?php endforeach; ?>
                                    </marquee>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="ws-mb-4">
                    <div>
                        <div class="ws-more-show ws-more ws-leading-7 ws-mb-4 ws-bg-white ws-rounded ws-py-2 ws-px-3 ws-transition-all ws-duration-200">
                            <?=$row['description']?>
                        </div>
                    </div>
                </div>
                <div class="ws-grid ws-grid-cols-12 ws-gap-2 md:ws-gap-4" id="list_account">
                </div>
            </div>
        </div>
</div>
<script>
    function toggleAllVisibility() {
        // Lấy tất cả các phần tử có id bắt đầu bằng "datatk-"
        var elements = document.querySelectorAll('[id^="datatk-"]');
        var button = document.getElementById('toggleButton'); // Lấy nút bằng ID
        var allHidden = true;
        elements.forEach(function(element) {
            if (element.style.display === "none" || element.style.display === "") {
                element.style.display = "block";
                allHidden = false; // Nếu có ít nhất một phần tử không ẩn, đặt allHidden là false
            } else {
                element.style.display = "none";
            }
        });
        // Thay đổi văn bản nút dựa trên trạng thái của các phần tử
        if (allHidden) {
            button.innerHTML = '<span class="">Tìm kiếm nâng cao</span>';
        } else {
            button.innerHTML = '<span class="">Ẩn tìm kiếm nâng cao</span>';
        }
    }
</script>
<?php if ($row['type'] == 'random'): ?>
<script>
function confirmPurchase(id) {
    Swal.fire({
        title: `Xác nhận mua tài khoản #${id}`,
        text: "Sau khi mua xong bạn có thể xem thông tin đăng nhập tại mục \"Lịch sử mua nick\", chắc chắn mua chứ?",
        icon: 'warning',
        input: 'text',
        inputPlaceholder: 'Nhập mã giảm giá (Nếu có)',
        showCancelButton: true,
        confirmButtonText: 'Xác nhận',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            if (result.value != ''){
                $.ajax({
                    url: "<?=BASE_URL('ajax/action/check-coupon');?>",
                    method: "POST",
                    dataType: 'json',
                    data: {
                        action: 'coupon-acc',
                        id: id,
                        coupon: result.value,
                        csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    success: function(data) {
                        if (data.status == 'error'){
                            Toast(data.status, data.msg);
                            return;
                        }
                        if (confirm(data.msg + ", Bạn có muốn mua?")){
                             $.ajax({
                                url: "<?=BASE_URL('ajax/invoice');?>",
                                method: "POST",
                                dataType: 'json',
                                data: {
                                    type: "mua-tai-khoan",
                                    id: id,
                                    coupon: result.value,
                                    csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                success: function(response) {

                                    if (response.status == 'success') {
                                        Toast(response.status, response.msg);
                                        setTimeout(function() {
                                            window.location = '/order/list?service_type=account';
                                        }, 1000);
                                    } else {
                                        Toast(response.status, response.msg);
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    Swal.fire('Lỗi!', 'Có lỗi xảy ra khi thực hiện giao dịch. Vui lòng thử lại sau.');
                                }
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire('Lỗi!', 'Có lỗi xảy ra khi thực hiện giao dịch. Vui lòng thử lại sau.');
                    }
                });
            } else {
                $.ajax({
                    url: "<?=BASE_URL('ajax/invoice');?>",
                    method: "POST",
                    dataType: 'json',
                    data: {
                        type: "mua-tai-khoan",
                        id: id,
                        csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            Toast(response.status, response.msg);
                            setTimeout(function() {
                                window.location = '/order/list?service_type=account';
                            }, 1000);
                        } else {
                            Toast(response.status, response.msg);
                        }
                    },
                    error: function() {
                        Swal.fire('Lỗi!', 'Có lỗi xảy ra khi thực hiện giao dịch. Vui lòng thử lại sau.');
                    }
                });
            }
        }
    });
}
</script>
<?php endif; ?>
<script>
page = 1;
id = "";
price = "";
<?php
    for($i=0; $i < count($filter_fields); $i++){
        if($filter_fields[$i]['show'] == 'on'){
    ?>
<?=$filter_fields[$i]['name']?> = "";
<?php } } ?>
slug = "<?=$row['slug']?>";
function load_account() {
    $("#list_account").hide();
    $.ajax({
        type: 'POST',
        url: '/ajax/action/get-list-account',
        data: {
            page: page,
            id: id,
            price: price,
            <?php
                    for($i=0; $i < count($filter_fields); $i++){
                        if($filter_fields[$i]['show'] == 'on'){
                ?>
            <?=$filter_fields[$i]['name']?>: <?=$filter_fields[$i]['name']?>,
            <?php } } ?>
            slug: slug
        },
        success: function(response) {
            $("#list_account").html('');
            $('#list_account').empty().append(response);
            $("#list_account").show();
        }
    });
}
function fitler() {
    id = $("#id").val();
    price = $("#price").val();
    <?php
            for($i=0; $i < count($filter_fields); $i++){
                if($filter_fields[$i]['show'] == 'on'){
        ?>
    <?=$filter_fields[$i]['name']?> = $("#<?=$filter_fields[$i]['name']?>").val();
    <?php } } ?>
    load_account();
}
load_account();
</script>
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Footer.php');
?>
