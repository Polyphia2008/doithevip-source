<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
load_client_data('service/service-list');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Header.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Navbar.php');
?>
<div class="nav-15rfyaw nav-spacing ws-my-4 ws-px-2">
    <div class="ws-pb-8">
        <div class="ws-rounded-lg ws-pt-2 ws-mb-4">
            <label class="ws-text-base">
                <small>DỊCH VỤ</small>
            </label>
            <h2 class="ws-block ws-font-bold ws-text-lg ws-text-red-500"><?=$row['name'];?></h2>
        </div>
        <div class="ws-grid ws-grid-cols-12 ws-gap-2">
            <div class="ws-col-span-12 sm:ws-col-span-12 <?= $row['type'] !== 'image' ? 'md:ws-col-span-6' : '' ?>">
                <div class="ws-mb-8 <?= $row['type'] !== 'image' ? 'md:ws-hidden' : '' ?>">
                    <div class="ws-bg-white ws-relative">
                        <div>

                            <div class="ws-more-show ws-more ws-leading-7 ws-mb-4 ws-bg-white ws-rounded ws-py-2 ws-px-3 ws-transition-all ws-duration-200">
                                <?=$row['description']?>
                            </div>

                        </div>
                    </div>
                </div>
                <?php if ($row['type'] !== 'image') { ?>
                <div class="el-form el-form--default el-form--label-right">
                    <div class="ws-mb-8 ws-grid ws-grid-cols-12 ws-gap-4">
                        <div class="ws-col-span-12">
                            <div class="ws-bg-white ws-p-3 ws-rounded">
                                <div><label class="ws-inline-block ws-font-semibold ws-text-base ws-mb-2">Bước 1: Chọn gói</label></div>
                                <div class="el-form-item is-required asterisk-left">
                                    <div class="el-form-item__content" style="margin-left:;">
                                        <div id="dichvu" class="ws-max-h-[28rem] ws-overflow-auto ws-w-full ws-mb-2">
                                            <?php
                                            foreach ($pack_services as $row_dichvu) {
                                                ?>
                                                <div data-money="<?= $row_dichvu['final_price']; ?>" value="<?= $row_dichvu['id']; ?>" class="ws-bg-zinc-100 ws-font-medium ws-rounded-lg ws-cursor-pointer ws-text-sm ws-capitalize ws-items-center ws-flex ws-relative ws-mr-1 ws-mb-1 ws-px-3 ws-py-2 <?= $row_dichvu['selected_class']; ?>" onclick="selectItem(this)">
                                                    <i class="ws-text-2xl fa-light fa-square-check"></i>
                                                    <div class="ws-ml-3">
                                                        <?= $row_dichvu['name']; ?>
                                                        <p class="ws-font-bold <?= ($row_dichvu['selected_class'] ? 'ws-text-red-600' : 'ws-text-red-600'); ?>"><?= format_number($row_dichvu['final_price']); ?>đ </p>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>

                                    </div>
                                </div>
                                <div class="ws-w-28 ws-my-4 ws-border-b ws-border-dashed"></div>
                                <div class="ws-mt-4">
                                    <label class="ws-font-medium ws-block ws-text-zinc-800 ws-text-xs ws-mb-1">CẦN THANH TOÁN:</label>
                                    <span class="ws-font-extrabold ws-text-2xl ws-text-red-600" id="thanhtoan">0 <span class="ws-text-sm"><small class="ws-font-medium">đ</small></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="ws-col-span-12">
                            <div class="ws-bg-white ws-p-3 ws-rounded">
                                <label class="ws-inline-block ws-font-semibold ws-text-base ws-mb-2">Bước 2: Điền thông tin</label>
                                <div>
                                    <div>
                                        <?php foreach ($filter_fields as $field): ?>
                                            <?php if (isset($field['show']) && $field['show'] !== 'on') continue; ?>
                                            <div class="el-form-item is-required asterisk-left">
                                                <div class="el-form-item__content">
                                                    <label class="ws-font-medium ws-block ws-text-zinc-800 ws-text-sm ws-mb-1"><?= $field['label'] ?? ''; ?>:</label>
                                                    <?php if (($field['type'] ?? 'text') === 'select') { ?>
                                                    <div class="el-select el-select--large" style="width: 100%;">
                                                        <div class="select-trigger el-tooltip__trigger el-tooltip__trigger">
                                                            <div class="el-input el-input--large el-input--suffix">
                                                                <select id="<?= $field['name'] ?? ''; ?>" class="el-input__inner input-param">
                                                                    <?php foreach (explode('|', $field['value'] ?? '') as $opt) { ?>
                                                                        <option value="<?= trim($opt); ?>"><?= trim($opt); ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php } else { ?>
                                                        <div class="el-input el-input--large">
                                                            <div class="el-input el-input--large el-input--suffix">
                                                                <input class="el-input__inner input-param"
                                                                       type="<?= $field['type'] ?? 'text'; ?>"
                                                                       id="<?= $field['name'] ?? ''; ?>"
                                                                       autocomplete="off"
                                                                       placeholder="Nhập <?= strtolower($field['label'] ?? ''); ?>"
                                                                       value="">
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="el-form-item is-required asterisk-left">
                                            <div class="el-form-item__content" style="margin-left:;">
                                                <label class="ws-font-medium ws-block ws-text-zinc-800 ws-text-sm ws-mb-1">Mã giảm giá (Nếu có):</label>
                                                <div class="el-input el-input--large" style="">
                                                    <div class="el-input el-input--large el-input--suffix" tabindex="-1">
                                                        <input class="el-input__inner" type="text" autocomplete="off" tabindex="0" placeholder="Nhập mã giảm giá (Nếu có)" style="" id="coupon" onchange="checkcoupon()" onkeyup="checkcoupon()">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="el-form-item asterisk-left">
                                            <div class="el-form-item__content" style="margin-left:;">
                                                <label class="ws-font-medium ws-block ws-text-zinc-800 ws-text-sm ws-mb-1">Ghi chú cày cho Admin:</label>
                                                <div class="el-textarea el-input--large" style="">
                                                    <textarea class="el-textarea__inner" tabindex="0" autocomplete="off" style="min-height: 31px;" placeholder="Nhập ghi chú nếu có" id="note"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ws-w-20 ws-my-4 ws-border-b"></div>
                                <div>
                                    <button ariadisabled="false" type="button" id="SubmitNormal" class="el-button el-button--primary el-button--large" style="">
                                        <span class="">Thanh toán</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } else { ?>
                <div class="ws-w-full">
                    <div class="ws-grid ws-grid-cols-12 md:ws-grid-cols-12 lg:ws-grid-cols-10 ws-gap-2">
                        <?php foreach ($pack_services as $ds) { ?>
                        <div onclick="dat_don(`<?=$ds['id'];?>`, `<?=$ds['name'];?>`, `<?=$ds['final_price'];?>`)" class="ws-col-span-6 md:ws-col-span-4 ws-cursor-pointer lg:ws-col-span-2 shake-el ws-border ws-bg-white ws-rounded-lg hover:ws-shadow-lg">
                            <div class="ws-relative ws-w-full ws-inline-block ws-mb-0">
                                <div class="ws-overflow-hidden ws-select-none ws-w-full ws-object-fill ws-object-center ws-rounded-t-sm ws-rounded-t-lg" style="aspect-ratio:16 / 9;">
                                    <img src="<?=base_url($ds['image']);?>" class="ws-select-none ws-rounded-lg ws-w-full ws-w-full ws-object-fill ws-object-center ws-rounded-t-sm ws-rounded-t-lg">

                                </div>
                            </div>
                            <h2 class="ws-px-3 ws-text-xs md:ws-text-sm ws-font-semibold ws-border-b ws-border-t ws-border-dashed ws-py-1 ws-my-2"><?=$ds['name'];?></h2>
                            <p class="ws-px-3 ws-mb-2 ws-text-sm ws-text-red-600 ws-font-bold">
                                <?=format_number($ds['final_price']);?> <small class="ws-ml-1 ws-relative ws-top-[-3px]">đ</small>
                            </p>
                        </div>
                        <?php }?>
                    </div>
                </div>
                <?php } ?>
            </div>
            <?php if ($row['type'] !== 'image') { ?>
            <div class="ws-hidden md:ws-block ws-col-span-12 md:ws-col-span-6">
                <div>
                    <div class="ws-bg-white ws-rounded-lg">
                        <div>

                            <div class="ws-more-show ws-more ws-leading-7 ws-mb-4 ws-rounded-lg ws-py-2 ws-px-3 ws-transition-all ws-duration-200">
                                <?=$row['description']?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php if ($row['type'] == 'image') { ?>
<div id="datdonmodal" class="el-overlay ws-notihomes" style="display: none; z-index: 1001;">

    <div role="dialog" aria-modal="true" aria-labelledby="el-id-1024-4" aria-describedby="el-id-1024-5" class="el-overlay-dialog" style="">

        <div class="el-dialog" tabindex="-1">
            <header class="el-dialog__header">
                <div class="ws-text-red-500 ws-font-bold">XÁC NHẬN ĐƠN HÀNG</div>
                <button onclick="tat_datdon()" aria-label="el.dialog.close" class="el-dialog__headerbtn" type="button">
                    <i class="el-icon el-dialog__close">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
                            <path fill="currentColor" d="M764.288 214.592 512 466.88 259.712 214.592a31.936 31.936 0 0 0-45.12 45.12L466.752 512 214.528 764.224a31.936 31.936 0 1 0 45.12 45.184L512 557.184l252.288 252.288a31.936 31.936 0 0 0 45.12-45.12L557.12 512.064l252.288-252.352a31.936 31.936 0 1 0-45.12-45.184z"></path>
                        </svg>
                    </i>
                </button>
            </header>
            <div id="el-id-1024-5" class="el-dialog__body">
                <div>
                    <form class="el-form el-form--default el-form--label-right">
                        <div class="ws-mb-0 ws-grid ws-grid-cols-12 ws-gap-4">
                            <div class="ws-col-span-12">
                                <div class="ws-bg-white">
                                    <div>
                                        <label class="ws-inline-block ws-text-black ws-font-semibold ws-text-base ws-mb-2">
                                            <i class="fa-solid fa-square-caret-right ws-relative ws-text-lg ws-top-[2px]"></i> Gói dịch vụ </label>
                                    </div>
                                    <div>
                                        <div class="ws-w-full ws-mb-2">
                                            <div class="ws-bg-red-600 ws-text-white ws-font-medium ws-rounded-lg ws-text-sm ws-items-center ws-flex ws-relative ws-mr-1 ws-mb-1 ws-px-1 ws-py-2">
                                                <div class="ws-ml-3" id="name_don"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ws-w-28 ws-mt-4 ws-mb-2 ws-border-b ws-border-dashed"></div>
                                    <div>
                                        <label class="ws-font-medium ws-block ws-text-zinc-800 ws-text-xs ws-mb-1">CẦN THANH TOÁN:</label>
                                        <span class="ws-font-extrabold ws-text-2xl ws-text-red-600" id="totalPrice">
                                            <p id="thanhtoan"></p>
                                            <input id="id_don" type="hidden" value="" />
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="ws-col-span-12">
                                <div class="ws-bg-white">
                                    <label class="ws-mb-2 ws-inline-block ws-text-black ws-font-semibold ws-text-base ws-mb-0">
                                        <i class="fa-solid fa-square-caret-right ws-relative ws-text-lg ws-top-[2px]"></i> Điền thông tin mua </label>
                                        <div>
                                        <div>
                                            <div>
                                                <div>
                                                    <?php foreach ($filter_fields as $field): ?>
                                                        <?php if (isset($field['show']) && $field['show'] !== 'on') continue; ?>
                                                            <div class="el-form-item is-required asterisk-left">
                                                                <div class="el-form-item__content">
                                                                    <label class="ws-font-medium ws-block ws-text-zinc-800 ws-text-sm ws-mb-1"><?= $field['label'] ?? ''; ?>:</label>
                                                                    <?php if (($field['type'] ?? 'text') === 'select') { ?>
                                                                    <div class="el-select el-select--large" style="width: 100%;">
                                                                        <div class="select-trigger el-tooltip__trigger el-tooltip__trigger">
                                                                            <div class="el-input el-input--large el-input--suffix">
                                                                                <select id="<?= $field['name'] ?? ''; ?>" class="el-input__inner">
                                                                                    <?php foreach (explode('|', $field['value'] ?? '') as $opt) { ?>
                                                                                        <option value="<?= trim($opt); ?>"><?= trim($opt); ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php } else { ?>
                                                                        <div class="el-input el-input--large">
                                                                            <div class="el-input el-input--large el-input--suffix">
                                                                                <input class="el-input__inner input-param"
                                                                                       type="<?= $field['type'] ?? 'text'; ?>"
                                                                                       id="<?= $field['name'] ?? ''; ?>"
                                                                                       autocomplete="off"
                                                                                       placeholder="Nhập <?= strtolower($field['label'] ?? ''); ?>"
                                                                                       value="">
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <div class="el-form-item is-required asterisk-left">
                                                        <div class="el-form-item__content" style="margin-left:;">
                                                            <label class="ws-font-medium ws-block ws-text-zinc-800 ws-text-sm ws-mb-1">Mã giảm giá (Nếu có):</label>
                                                            <div class="el-input el-input--large" style="">
                                                                <div class="el-input el-input--large el-input--suffix" tabindex="-1">
                                                                    <input class="el-input__inner" type="text" autocomplete="off" tabindex="0" placeholder="Nhập mã giảm giá (Nếu có)" style="" id="coupon" onchange="checkcoupon()" onkeyup="checkcoupon()">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="el-form-item asterisk-left">
                                                        <div class="el-form-item__content" style="margin-left:;">
                                                            <label class="ws-font-medium ws-block ws-text-zinc-800 ws-text-sm ws-mb-1">Ghi chú cày cho Admin:</label>
                                                            <div class="el-textarea el-input--large" style="">
                                                                <textarea class="el-textarea__inner" tabindex="0" autocomplete="off" style="min-height: 31px;" placeholder="Nhập ghi chú nếu có" id="note"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <!---->
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button ariadisabled="false" type="button" id="SubmitModal" class="el-button el-button--primary el-button--large" style="">
                                            <span class="">Thanh toán</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--v-if-->
        </div>

    </div>
</div>
<input name="idne" id="idne" type="hidden" value=""/>
<input name="name" id="name" type="hidden" value=""/>
<input name="price" id="price" type="hidden" value=""/>
<input type="hidden" id="dichvu_modal" value="">
<script>
    function dat_don(idne, name, price) {
        var formattedPrice = parseFloat(price).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
        document.getElementById("datdonmodal").style.display = "block";
        document.getElementById('idne').value = idne;
        document.getElementById('dichvu_modal').value = idne;
        document.getElementById('id_don').innerHTML = "#" + idne;
        document.getElementById('name').value = name;
        document.getElementById('name_don').innerHTML = name + '<p class="ws-font-bold ws-text-white">' + formattedPrice + '</p>';
        document.getElementById('price').value = price;
        document.getElementById('thanhtoan').innerHTML = formattedPrice;
    }
    function tat_datdon() {
        var x = document.getElementById("datdonmodal");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";

        }
    }
</script>
<?php } ?>
<script>
    var selectedItem = null; // Biến để theo dõi mục được chọn
    var dichvuValue = document.getElementById('dichvu').value;
    console.log(dichvuValue);
    function selectItem(element) {
        // Kiểm tra xem đã có mục được chọn hay chưa
        if (selectedItem !== null) {
            // Đặt lại trạng thái cho mục trước đó
            selectedItem.classList.remove('selected-item');
            selectedItem.classList.add('ws-bg-zinc-100');
            selectedItem.classList.remove('ws-bg-red-600');
            selectedItem.classList.remove('ws-text-white');
            selectedItem.querySelector('.ws-font-bold').classList.remove('ws-text-white');
            selectedItem.querySelector('.ws-font-bold').classList.add('ws-text-red-600');
        }
        // Cập nhật mục được chọn mới
        selectedItem = element;
        selectedItem.classList.add('selected-item');
        selectedItem.classList.remove('ws-bg-zinc-100');
        selectedItem.classList.add('ws-bg-red-600');
        selectedItem.classList.add('ws-text-white');
        selectedItem.querySelector('.ws-font-bold').classList.remove('ws-text-red-600');
        selectedItem.querySelector('.ws-font-bold').classList.add('ws-text-white');
        // Gọi hàm showPrice() khi có sự thay đổi
        showPrice();
    }
    function showPrice() {
    // Lấy giá trị từ thuộc tính data-money
    var thanhtoan = selectedItem ? selectedItem.dataset.money : 0;
    // Kiểm tra xem thanhtoan có giá trị không
    if (typeof thanhtoan !== 'undefined') {
        // Format giá tiền theo ý bạn (ví dụ: thêm dấu phẩy ngăn cách hàng nghìn)
        var formattedPrice = format_number(thanhtoan);
        // Cập nhật giá tiền vào <span class="ws-font-extrabold ws-text-2xl ws-text-red-600">
        var priceElement = document.getElementById('thanhtoan');
        priceElement.innerHTML = formattedPrice + ' <span class="ws-text-sm"><small class="ws-font-medium">đ</small></span>';
        // Kiểm tra xem có class 'ws-font-extrabold' và 'ws-text-red-600' không
        if (!priceElement.classList.contains('ws-font-extrabold')) {
            priceElement.classList.add('ws-font-extrabold');
            priceElement.classList.add('ws-text-2xl');
            priceElement.classList.add('ws-text-red-600');
        }
    }
    }
    // Hàm format_cash để thêm dấu phẩy ngăn cách hàng nghìn
    function format_number(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>
<script type="text/javascript">
$("#SubmitNormal, #SubmitModal").on("click", function () {
    let dichvu_id = selectedItem
        ? selectedItem.getAttribute('value')
        : $("#dichvu_modal").val();

    if (!dichvu_id) {
        Toast("error", "Vui lòng chọn gói dịch vụ");
        return;
    }
    // --- BẮT ĐẦU SỬA ĐỔI: LẤY DỮ LIỆU ĐỘNG ---
    let formData = {
        type: 'mua-dich-vu',
        coupon: $("#coupon").val(),
        note: $("#note").val(),
        dichvu: dichvu_id,
        csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    // Duyệt qua tất cả các input/select được tạo ra từ vòng lặp PHP (có class input-param)
    $('.input-param').each(function() {
        let fieldId = $(this).attr('id'); // Lấy name từ json (ví dụ: taikhoan, matkhau, bandatat2fachua)
        let fieldValue = $(this).val();
        if(fieldId) {
            formData[fieldId] = fieldValue; // Thêm vào object gửi đi
        }
    });
    // --- KẾT THÚC SỬA ĐỔI ---
    $(this).html('<i class="fa fa-spinner fa-pulse" style="margin-right:10px"></i> Đang xử lý')
        .prop('disabled', true);

    $.ajax({
        url: "<?=BASE_URL("ajax/invoice");?>",
        method: "POST",
        dataType: "JSON",
        data: formData, // Gửi object formData đã tạo ở trên
        success: function (response) {
            if (response.status === 'success') {
                Toast(response.status, response.msg);
                setTimeout(function () {
                    window.location = '/order/list?service_type=service';
                }, 1000);
            } else {
                Toast(response.status, response.msg);
            }
            $("#SubmitNormal, #SubmitModal")
                .html('Thanh toán')
                .prop('disabled', false);
        }
    });
});
let isCheckingCoupon = false;
function checkcoupon() {
    if (isCheckingCoupon) return; // chặn spam
    isCheckingCoupon = true;
    const btn = $('#thanhtoan');
    const oldHtml = btn.html();
    // chỉ disable nút
    btn.prop('disabled', true);
    $.ajax({
        url: "<?=BASE_URL('ajax/action/check-coupon');?>",
        method: "POST",
        dataType: 'json',
        data: {
            id: selectedItem ? selectedItem.getAttribute('value') : null,
            coupon: $("#coupon").val(),
            action: 'coupon-dichvu',
            csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        success: function (data) {
            if (data.status === 'success') {
                // thành công → cập nhật HTML nút
                btn.html(data.msg);
            } else {
                // lỗi → trả lại HTML cũ, không báo gì
                btn.html(oldHtml);
            }
        },
        error: function () {
            // lỗi ajax → trả lại HTML cũ
            btn.html(oldHtml);
        },
        complete: function () {
            isCheckingCoupon = false;
            btn.prop('disabled', false);
        }
    });
}


</script>
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Footer.php');
?>
