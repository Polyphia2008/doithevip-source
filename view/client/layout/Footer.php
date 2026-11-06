<div style="background:rgb(29, 24, 24);" class="ws-text-white ws-py-10">
    <div class="ws-px-4 md:ws-px-2 ws-mx-auto ws-max-w-8xl">
        <div class="ws-grid ws-grid-cols-12 ws-gap-6">
            <div class="ws-col-span-12 md:ws-col-span-3 lg:ws-col-span-3">
                <div class="ws-overflow-hidden ws-select-none ws-h-12 ws-mb-3" style="aspect-ratio:10 / 3;">
                    <img src="<?=$site['logo_light'] ?? '';?>" data-src="<?=$site['logo_light'] ?? '';?>" class="lazyLoad ws-select-none ws-rounded-lg ws-w-full ws-h-12 ws-mb-3">
                </div>
                <p class="ws-font-medium ws-text-sm ws-leading-6 ws-mb-3"><?=$site['title'] ?? '';?></p>
                <div class="ws-text-sm ws-text-red-500 ws-font-bold ws-my-1"> <?=$site['description'] ?? '';?> </div>

            </div>
            <?php foreach (($footerData ?? []) as $block) { ?>
            <div class="ws-col-span-12 md:ws-col-span-3">
                <label class="ws-block ws-font-semibold ws-text-xl ws-block ws-mb-3"><?= $block['name'] ?></label>
                <div class="ws-py-2 ws-px-3 ws-bg-zinc-800 ws-text-sm ws-rounded ws-mb-2">

                    <?php foreach ($block['children'] as $child): ?>
                    <p class="ws-pb-2">
                        <a href="<?= $child['url'] ?>" class="hover:ws-text-red-500 ws-transition ws-duration-200"> › <span class="ws-ml-1"><?= $child['name'] ?></span>
                        </a>
                    </p>
                    <?php endforeach; ?>

                </div>
            </div>
            <?php } ?>
            <?php if (!empty($site['phone']) || !empty($site['email'])): ?>
            <div class="ws-col-span-12 md:ws-col-span-3">
                <label class="ws-block ws-font-semibold ws-text-xl ws-block ws-mb-3">Liên hệ</label>
                <div class="ws-py-2 ws-px-3 ws-bg-zinc-800 ws-text-sm ws-rounded ws-mb-2">
                    <?php if (!empty($site['phone'])): ?>
                        <p class="ws-pb-2">
                            <a href="tel:<?= $site['phone']; ?>" class="hover:ws-text-red-500 ws-transition ws-duration-200"> › <span class="ws-ml-1"><?= $site['phone']; ?></span>
                            </a>
                        </p>
                    <?php endif; ?>
                    <?php if (!empty($site['email'])): ?>
                        <p class="ws-pb-2">
                            <a href="mailto:<?= $site['email']; ?>" class="hover:ws-text-red-500 ws-transition ws-duration-200"> › <span class="ws-ml-1"><?= $site['email']; ?></span>
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div style="background:rgb(29, 24, 24);" class="ws-text-white ws-py-2">
    <div class="ws-mt-4 ws-border-t ws-border-zinc-800 ws-pt-2 ws-text-center ws-text-sm">
        Powered By <a href="/" target="_blank" style="color:#ee4d2d"><b><?=$site['copyright'] ?? '';?></b></a>, All Rights Reserved - Version <b style="color:yellow"><?=$_ENV['APP_VERSION'];?></b>
    </div>
</div>
<script type="text/javascript" src="/assets/client/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<script type="text/javascript" src="/assets/client/plugins/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/assets/client/plugins/swiper/swiper-bundle.min.js"></script>
<script type="text/javascript" src="/assets/client/plugins/fancybox/fancybox.min.js"></script>
<script type="text/javascript" src="/assets/client/js/app.js?v=<?=time()?>"></script>
<div id="modal-container"></div>
<?php render_toast_msg_client(); ?>
<?=$script_footer ?? '';?>
<script>
// ==================== SPINNER TRÊN SUBMIT ====================
document.querySelectorAll("form").forEach(form => {
  form.addEventListener("submit", function () {
    const btn = form.querySelector('button[type="submit"]');
    if (btn) {
      btn.disabled = true;
      btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i> Đang xử lý...';
    }
  });
});
$(document).ready(function() {
    $('#datatable').DataTable({
        pageLength: 5,
        lengthChange: false,
        ordering: false,
        searching: false,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json"
        }
    });
});
</script>
</body>
</html>
