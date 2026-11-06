<div class="modal fade el-overlay ws-recharge-modal" id="nap_ngan_hang" role="dialog" aria-hidden="true"
    style="z-index: 1001;"><!--[-->
    <div role="dialog" aria-modal="true" aria-labelledby="el-id-1024-1" aria-describedby="el-id-1024-2"
        class="modal-dialog el-overlay-dialog">
        <div class="el-dialog" tabindex="-1">
            <header class="chakra-modal__header nav-9fgtzh" id="chakra-modal--header-:rd:">Nạp ngân hàng</header>
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
                    <?php if (!empty(get_informations('deposit'))): ?>
                        <div class="alert-custom alert alert-danger">
                            <?= get_informations('deposit'); ?>
                        </div>
                    <?php endif; ?>
                    <form class="el-form el-form--default el-form--label-right">
                        <div class="el-form-item is-required asterisk-left">
                            <div class="el-form-item__content">
                                <label for="amount" class="ws-font-semibold ws-text-black">Ngân hàng</label>
                                <div class="el-select el-select--large" style="width: 100%;">
                                    <div class="select-trigger el-tooltip__trigger el-tooltip__trigger">
                                        <div class="el-input el-input--large el-input--suffix">
                                            <select class="el-input__inner" id="payment_method">
                                                <?php foreach ($db->get_list("SELECT * FROM `bankings` WHERE `invoice_pay` = 1 ") as $bank): ?>
                                                    <option value="<?=$bank['bankName'] ?>"><?=$bank['textNameBank'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="el-form-item is-required asterisk-left">
                            <div class="el-form-item__content">
                                <label for="amount" class="ws-font-semibold ws-text-black">Số tiền cần nạp</label>
                                <div class="el-select el-select--large" style="width: 100%;">
                                    <div class="select-trigger el-tooltip__trigger el-tooltip__trigger">
                                        <div class="el-input el-input--large el-input--suffix">
                                            <input class="el-input__inner" type="text" autocomplete="off" tabindex="0" placeholder="" id="net_amount">
                                        </div>
                                        <small class="text-danger">Nạp ngày tối đa: <?=format_number($db->site('deposit_daily_limit'));?></small>                                                                            
                                        <small class="text-danger">| Nạp tối thiểu: <?=format_number($db->site('deposit_min_amount'));?></small>                                                                            
                                        <small class="text-danger">| Nạp tối đa: <?=format_number($db->site('deposit_max_amount'));?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button id="submit_nap_ngan_hang" type="button" class="el-button el-button--primary el-button--large ws-w-[10rem] ws-font-extrabold">
                            Nạp ngay
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$("#submit_nap_ngan_hang").on("click", function() {
    $('#submit_nap_ngan_hang').html('<i class="fa fa-spinner fa-spin-custom" style="margin-right: 10px;"></i> Đang xử lý').prop('disabled',
        true);
    $.ajax({
        url: "/ajax/invoice",
        method: "POST",
        dataType: "JSON",
        data: {
            type: 'deposit',
            net_amount: $("#net_amount").val(),
            payment_method: $("#payment_method").val(),
            csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        success: function(response) {
            if (response.status === 'success') {
                Toast(response.status, response.msg);
                setTimeout(() => {
                    window.location.href = response.url;
                }, 800);
            } else {
                Toast(response.status, response.msg);
            }
            $('#napthecao').html(
                    'Nạp Thẻ Ngay')
                .prop('disabled', false);
        }
    });
});
</script>