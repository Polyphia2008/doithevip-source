<?php

$location_danhmuc_order = explode('-', $db->site('location_danhmuc'));



load_client_data('modules/doithe-slider');

load_client_data('modules/other/noti-sell');

load_client_data('modules/other/menu-quick-access');

load_client_data('modules/other/flash-sale');

load_client_data('modules/categories/mua-nick');

load_client_data('modules/categories/dich-vu');



$notice_popup = $db->site('notice_popup');

$show_notice_popup = !empty($notice_popup) && !isset($_COOKIE['hide_modal']);



if ($show_notice_popup) {

    $script_footer = <<<EOT

<script>

    $(document).ready(function() {

        $('#global-modal').modal('show');

    });



    function closeFor3Hours() {

        var d = new Date();

        d.setTime(d.getTime() + (3*60*60*1000));

        document.cookie = "hide_modal=1; expires=" + d.toUTCString() + "; path=/";

        $('#global-modal').modal('hide');

    }

</script>

<div class="modal fade chakra-portal" id="global-modal" role="dialog" aria-hidden="true" style="z-index: 2006; ">

    <div class="chakra-modal__overlay nav-hdd9l7 modal-dialog" role="dialog" aria-modal="true" aria-hidden="true" style="opacity: 1;"></div>

    <div class="el-dialog" tabindex="-1">

        <div data-focus-lock-disabled="false">

            <div class="chakra-modal__content-container nav-1u2cvaz" tabindex="-1">

                <section class="chakra-modal__content nav-6gzxwn">

                    <header class="chakra-modal__header nav-9fgtzh" id="chakra-modal--header-:rd:">Thông báo</header>

                    <button type="button" data-dismiss="modal" aria-label="Close" class="chakra-modal__close-btn nav-1ik4h6n close">

                        <svg viewBox="0 0 24 24" focusable="false" class="chakra-icon nav-onkibi" aria-hidden="true">

                            <path

                                fill="currentColor"

                                d="M.439,21.44a1.5,1.5,0,0,0,2.122,2.121L11.823,14.3a.25.25,0,0,1,.354,0l9.262,9.263a1.5,1.5,0,1,0,2.122-2.121L14.3,12.177a.25.25,0,0,1,0-.354l9.263-9.262A1.5,1.5,0,0,0,21.439.44L12.177,9.7a.25.25,0,0,1-.354,0L2.561.44A1.5,1.5,0,0,0,.439,2.561L9.7,11.823a.25.25,0,0,1,0,.354Z"

                            ></path>

                        </svg>

                    </button>

                    <div class="chakra-modal__body nav-qlig70" id="chakra-modal--body-:rd:">

                        <div class="nav-0">

                            {$notice_popup}

                        </div>

                    </div>

                    <footer class="chakra-modal__footer nav-1dq2k1l">

                        <button type="button" class="chakra-button nav-s9voqi11" onclick="closeFor3Hours()">Tắt thông báo</button>

                    </footer>

                </section>

            </div>

        </div>

    </div>

</div>

EOT;

} else {

    $script_footer = '';

}

