<div class="modal fade el-overlay ws-recharge-modal" id="nap_the_cao" role="dialog" aria-hidden="true"
    style="z-index: 1001;"><!--[-->
    <div role="dialog" aria-modal="true" aria-labelledby="el-id-1024-1" aria-describedby="el-id-1024-2"
        class="modal-dialog el-overlay-dialog">
        <div class="el-dialog" tabindex="-1">
            <header class="chakra-modal__header nav-9fgtzh" id="chakra-modal--header-:rd:">Nạp thẻ cào</header>
            <button type="button" data-dismiss="modal" aria-label="Close" class="chakra-modal__close-btn nav-1ik4h6n close">
                <svg viewBox="0 0 24 24" focusable="false" class="chakra-icon nav-onkibi" aria-hidden="true">
                    <path
                        fill="currentColor"
                        d="M.439,21.44a1.5,1.5,0,0,0,2.122,2.121L11.823,14.3a.25.25,0,0,1,.354,0l9.262,9.263a1.5,1.5,0,1,0,2.122-2.121L14.3,12.177a.25.25,0,0,1,0-.354l9.263-9.262A1.5,1.5,0,0,0,21.439.44L12.177,9.7a.25.25,0,0,1-.354,0L2.561.44A1.5,1.5,0,0,0,.439,2.561L9.7,11.823a.25.25,0,0,1,0,.354Z"
                    ></path>
                </svg>
            </button>
            <div class="chakra-modal__body nav-qlig70" id="chakra-modal--body-:rd:">
                <div>
                    <?php if (!empty(get_informations('deposit_card'))): ?>
                        <div class="alert-custom alert alert-danger">
                            <?= get_informations('deposit_card'); ?>
                        </div>
                    <?php endif; ?>
                    <form class="el-form el-form--default el-form--label-right">
                        <div class="el-form-item is-required asterisk-left"><!--v-if-->
                            <div class="el-form-item__content">
                                <label for="telco" class="ws-font-semibold ws-text-black">Loại thẻ</label>
                                <div class="ws-grid ws-grid-cols-12 ws-gap-2 ws-w-full ws-text-sm">
                                    <button
                                        class="ws-h-12 ws-w-full ws-flex ws-justify-center ws-items-center ws-col-span-4 ws-border-gray-300 ws-border-dashed ws-px-3 ws-rounded ws-font-bold ws-border"
                                        type="button" value="VIETTEL" onclick="selectOperatorModal(this)">
                                        <img src="/assets/client/images/thecao/VIETTEL.png"
                                            style="width: 4rem; filter: grayscale(100%);">
                                    </button>
                                    <button
                                        class="ws-h-12 ws-w-full ws-flex ws-justify-center ws-items-center ws-col-span-4 ws-border-gray-300 ws-border-dashed ws-px-3 ws-rounded ws-font-bold ws-border"
                                        type="button" value="VINAPHONE" onclick="selectOperatorModal(this)">
                                        <img src="/assets/client/images/thecao/VINAPHONE.png"
                                            style="width: 4rem; filter: grayscale(100%);">
                                    </button>
                                    <button
                                        class="ws-h-12 ws-w-full ws-flex ws-justify-center ws-items-center ws-col-span-4 ws-border-gray-300 ws-border-dashed ws-px-3 ws-rounded ws-font-bold ws-border"
                                        type="button" value="MOBIFONE" onclick="selectOperatorModal(this)">
                                        <img src="/assets/client/images/thecao/MOBIFONE.png"
                                            style="width: 4rem; filter: grayscale(100%);">
                                    </button>
                                    <button
                                        class="ws-h-12 ws-w-full ws-flex ws-justify-center ws-items-center ws-col-span-4 ws-border-gray-300 ws-border-dashed ws-px-3 ws-rounded ws-font-bold ws-border"
                                        type="button" value="GARENA" onclick="selectOperatorModal(this)">
                                        <img src="/assets/client/images/thecao/GARENA.png"
                                            style="width: 4rem; filter: grayscale(100%);">
                                    </button>
                                    <button
                                        class="ws-h-12 ws-w-full ws-flex ws-justify-center ws-items-center ws-col-span-4 ws-border-gray-300 ws-border-dashed ws-px-3 ws-rounded ws-font-bold ws-border"
                                        type="button" value="ZING" onclick="selectOperatorModal(this)">
                                        <img src="/assets/client/images/thecao/ZING.png"
                                            style="width: 4rem; filter: grayscale(100%);">
                                    </button>
                                    <button
                                        class="ws-h-12 ws-w-full ws-flex ws-justify-center ws-items-center ws-col-span-4 ws-border-gray-300 ws-border-dashed ws-px-3 ws-rounded ws-font-bold ws-border"
                                        type="button" value="VCOIN" onclick="selectOperatorModal(this)">
                                        <img src="/assets/client/images/thecao/VCOIN.png"
                                            style="width: 4rem; filter: grayscale(100%);">
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="el-form-item is-required asterisk-left">
                            <div class="el-form-item__content">
                                <label for="amount" class="ws-font-semibold ws-text-black">Mệnh giá</label>
                                <div class="el-select el-select--large" style="width: 100%;">
                                    <div class="select-trigger el-tooltip__trigger el-tooltip__trigger">
                                        <div class="el-input el-input--large el-input--suffix">
                                            <select class="el-input__inner" id="menhgia_modal">
                                                <option value="">-- Chọn đúng mệnh giá. Sai mất thẻ --</option>
                                                <option value="10000">Thẻ 10,000đ</option>
                                                <option value="20000">Thẻ 20,000đ</option>
                                                <option value="30000">Thẻ 30,000đ</option>
                                                <option value="50000">Thẻ 50,000đ</option>
                                                <option value="100000">Thẻ 100,000đ</option>
                                                <option value="200000">Thẻ 200,000đ</option>
                                                <option value="300000">Thẻ 300,000đ</option>
                                                <option value="500000">Thẻ 500,000đ</option>
                                                <option value="1000000">Thẻ 1,000,000đ</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ws-grid ws-grid-cols-12 md:ws-gap-4">
                            <div class="ws-col-span-12 md:ws-col-span-6">
                                <div class="el-form-item is-required asterisk-left">
                                    <div class="el-form-item__content">
                                        <label for="seri" class="ws-font-semibold ws-text-black">Seri</label>
                                        <div class="el-input el-input--large">
                                            <input class="el-input__inner" type="text" autocomplete="off" tabindex="0" placeholder="" id="seri_modal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ws-col-span-12 md:ws-col-span-6">
                                <div class="el-form-item is-required asterisk-left">
                                    <div class="el-form-item__content">
                                        <label for="code" class="ws-font-semibold ws-text-black">Mã thẻ</label>
                                        <div class="el-input el-input--large">
                                            <input class="el-input__inner" type="text" autocomplete="off" tabindex="0" placeholder="" id="pin_modal">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <button id="napthecao_modal" type="button" class="el-button el-button--primary el-button--large ws-w-[10rem] ws-font-extrabold">
                            Nạp tiền
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
let operator_modal = "";
function selectOperatorModal(button) {
    // Gán nhà mạng vào biến global
    operator_modal = button.value; // ← QUAN TRỌNG

    // Reset tất cả button
    var buttons = document.querySelectorAll('.ws-border-red-index-500');
    buttons.forEach(function(btn) {
        btn.classList.remove('ws-border-red-index-500');
        var img = btn.querySelector('img');
        if (img) img.style.filter = 'grayscale(100%)';
    });

    // Active button đang chọn
    button.classList.remove('ws-border-gray-300');
    button.classList.add('ws-border-red-index-500');

    // Bỏ grayscale ảnh được chọn
    var img = button.querySelector('img');
    if (img) img.style.filter = 'none';
}
$("#napthecao_modal").on("click", function() {
    if (operator_modal === "") {
        Toast("error", "Vui lòng chọn loại thẻ!");
        return;
    }
    $('#napthecao_modal').html('<i class="fa fa-spinner fa-spin-custom" style="margin-right: 10px;"></i> Đang xử lý').prop('disabled',
        true);
    $.ajax({
        url: "/ajax/invoice",
        method: "POST",
        dataType: "JSON",
        data: {
            type: 'deposit_card',
            loaithe: operator_modal,
            menhgia: $("#menhgia_modal").val(),
            seri: $("#seri_modal").val(),
            pin: $("#pin_modal").val(),
            csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        success: function(response) {
            if (response.status == 'success') {
                Toast(response.status, response.msg);
                setTimeout(function() {
                    window.location = '/';
                }, 1000);
            } else {
                Toast(response.status, response.msg)
            }
            $('#napthecao').html(
                    'Nạp Thẻ Ngay')
                .prop('disabled', false);
        }
    });
});
</script>