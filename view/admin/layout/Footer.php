            <footer class="footer">
                <div class="page-container">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start">
                            <script>document.write(new Date().getFullYear())</script> © Create By <span class="fw-bold text-decoration-underline text-uppercase text-reset fs-12"><a href="<?=$_ENV['AUTH_URL'];?>"><?=$_ENV['AUTH_NAME'];?></a></span>
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end footer-links d-none d-md-block">
                                <a href="javascript: void(0);">About</a>
                                <a href="javascript: void(0);">Support</a>
                                <a href="javascript: void(0);">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    </div>
    
    <div class="offcanvas offcanvas-end" tabindex="-1" id="theme-settings-offcanvas">
        <div class="d-flex align-items-center gap-2 px-3 py-3 offcanvas-header border-bottom border-dashed">
            <h5 class="flex-grow-1 mb-0">Theme Settings</h5>

            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-0 h-100" data-simplebar>
            <div class="p-3 border-bottom border-dashed">
                <h5 class="mb-3 fs-16 fw-bold">Color Scheme</h5>

                <div class="row">
                    <div class="col-4">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-color-light" value="light">
                            <label class="form-check-label p-3 w-100 d-flex justify-content-center align-items-center" for="layout-color-light">
                                <iconify-icon icon="solar:sun-bold-duotone" class="fs-32 text-muted"></iconify-icon>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Light</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-color-dark" value="dark">
                            <label class="form-check-label p-3 w-100 d-flex justify-content-center align-items-center" for="layout-color-dark">
                                <iconify-icon icon="solar:cloud-sun-2-bold-duotone" class="fs-32 text-muted"></iconify-icon>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Dark</h5>
                    </div>
                </div>
            </div>

            <div class="p-3 border-bottom border-dashed">
                <h5 class="mb-3 fs-16 fw-bold">Layout Mode</h5>

                <div class="row">
                    <div class="col-4">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-fluid" value="fluid">
                            <label class="form-check-label p-0 avatar-xl w-100" for="layout-mode-fluid">
                                <div>
                                    <span class="d-flex h-100">
                                        <span class="flex-shrink-0">
                                            <span class="bg-light d-flex h-100 border-end flex-column p-1 px-2">
                                                <span class="d-block p-1 bg-dark-subtle rounded mb-1"></span>
                                                <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                                <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                                <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                                <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column rounded-2">
                                                <span class="bg-light d-block p-1"></span>
                                            </span>
                                        </span>
                                    </span>
                                </div>

                                <div>
                                    <span class="d-flex h-100 flex-column">
                                        <span class="bg-light d-flex p-1 align-items-center border-bottom border-secondary border-opacity-25">
                                            <span class="d-block p-1 bg-dark-subtle rounded me-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded ms-auto"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded ms-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded ms-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded ms-1"></span>
                                        </span>
                                        <span class="bg-light d-block p-1"></span>
                                    </span>
                                </div>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Fluid</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-layout-mode" id="data-layout-detached" value="detached">
                            <label class="form-check-label p-0 avatar-xl w-100" for="data-layout-detached">
                                <span class="d-flex h-100 flex-column">
                                    <span class="bg-light d-flex p-1 align-items-center border-bottom ">
                                        <span class="d-block p-1 bg-dark-subtle rounded me-1"></span>
                                        <span class="d-block border border-3 border-secondary border-opacity-25 rounded ms-auto"></span>
                                        <span class="d-block border border-3 border-secondary border-opacity-25 rounded ms-1"></span>
                                        <span class="d-block border border-3 border-secondary border-opacity-25 rounded ms-1"></span>
                                        <span class="d-block border border-3 border-secondary border-opacity-25 rounded ms-1"></span>
                                    </span>
                                    <span class="d-flex h-100 p-1 px-2">
                                        <span class="flex-shrink-0">
                                            <span class="bg-light d-flex h-100 flex-column p-1 px-2">
                                                <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                                <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                                <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100"></span>
                                            </span>
                                        </span>
                                    </span>
                                    <span class="bg-light d-block p-1 mt-auto px-2"></span>
                                </span>

                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Detached</h5>
                    </div>
                </div>
            </div>

            <div class="p-3 border-bottom border-dashed">
                <h5 class="mb-3 fs-16 fw-bold">Topbar Color</h5>

                <div class="row">
                    <div class="col-3">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-topbar-color" id="topbar-color-light" value="light">
                            <label class="form-check-label p-0 avatar-lg w-100 bg-light" for="topbar-color-light">
                                <span class="d-flex align-items-center justify-content-center h-100">
                                    <span class="p-2 d-inline-flex shadow rounded-circle bg-white"></span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Light</h5>
                    </div>

                    <div class="col-3">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-topbar-color" id="topbar-color-dark" value="dark">
                            <label class="form-check-label p-0 avatar-lg w-100 bg-light" for="topbar-color-dark">
                                <span class="d-flex align-items-center justify-content-center h-100">
                                    <span class="p-2 d-inline-flex shadow rounded-circle bg-dark"></span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Dark</h5>
                    </div>

                    <div class="col-3">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-topbar-color" id="topbar-color-brand" value="brand">
                            <label class="form-check-label p-0 avatar-lg w-100 bg-light" for="topbar-color-brand">
                                <span class="d-flex align-items-center justify-content-center h-100">
                                    <span class="p-2 d-inline-flex shadow rounded-circle bg-primary"></span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Gradient</h5>
                    </div>
                </div>
            </div>

            <div class="p-3 border-bottom border-dashed">
                <h5 class="mb-3 fs-16 fw-bold">Menu Color</h5>

                <div class="row">
                    <div class="col-3">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-menu-color" id="sidenav-color-light" value="light">
                            <label class="form-check-label p-0 avatar-lg w-100 bg-light" for="sidenav-color-light">
                                <span class="d-flex align-items-center justify-content-center h-100">
                                    <span class="p-2 d-inline-flex shadow rounded-circle bg-white"></span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Light</h5>
                    </div>

                    <div class="col-3" style="--ct-dark-rgb: 64,73,84;">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-menu-color" id="sidenav-color-dark" value="dark">
                            <label class="form-check-label p-0 avatar-lg w-100 bg-light" for="sidenav-color-dark">
                                <span class="d-flex align-items-center justify-content-center h-100">
                                    <span class="p-2 d-inline-flex shadow rounded-circle bg-dark"></span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Dark</h5>
                    </div>
                    <div class="col-3">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-menu-color" id="sidenav-color-brand" value="brand">
                            <label class="form-check-label p-0 avatar-lg w-100 bg-light" for="sidenav-color-brand">
                                <span class="d-flex align-items-center justify-content-center h-100">
                                    <span class="p-2 d-inline-flex shadow rounded-circle bg-primary"></span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Brand</h5>
                    </div>
                </div>
            </div>

            <div class="p-3 .border-bottom .border-dashed">
                <h5 class="mb-3 fs-16 fw-bold">Sidebar Size</h5>

                <div class="row">
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-default" value="default">
                            <label class="form-check-label p-0 avatar-xl w-100" for="sidenav-size-default">
                                <span class="d-flex h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 border-end  flex-column p-1 px-2">
                                            <span class="d-block p-1 bg-dark-subtle rounded mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Default</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-compact" value="compact">
                            <label class="form-check-label p-0 avatar-xl w-100" for="sidenav-size-compact">
                                <span class="d-flex h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 border-end  flex-column p-1">
                                            <span class="d-block p-1 bg-dark-subtle rounded mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Compact</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-small" value="condensed">
                            <label class="form-check-label p-0 avatar-xl w-100" for="sidenav-size-small">
                                <span class="d-flex h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 border-end flex-column" style="padding: 2px;">
                                            <span class="d-block p-1 bg-dark-subtle rounded mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Condensed</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-small-hover" value="sm-hover">
                            <label class="form-check-label p-0 avatar-xl w-100" for="sidenav-size-small-hover">
                                <span class="d-flex h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 border-end flex-column" style="padding: 2px;">
                                            <span class="d-block p-1 bg-dark-subtle rounded mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Hover View</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-full" value="full">
                            <label class="form-check-label p-0 avatar-xl w-100" for="sidenav-size-full">
                                <span class="d-flex h-100">
                                    <span class="flex-shrink-0">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="d-block p-1 bg-dark-subtle mb-1"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Full Layout</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-fullscreen" value="fullscreen">
                            <label class="form-check-label p-0 avatar-xl w-100" for="sidenav-size-fullscreen">
                                <span class="d-flex h-100">
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Hidden</h5>
                    </div>
                </div>
            </div>

            <div class="p-3 border-bottom border-dashed d-none">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fs-16 fw-bold mb-0">Container Width</h5>

                    <div class="btn-group radio" role="group">
                        <input type="radio" class="btn-check" name="data-container-position" id="container-width-fixed" value="fixed">
                        <label class="btn btn-sm btn-soft-primary w-sm" for="container-width-fixed">Full</label>

                        <input type="radio" class="btn-check" name="data-container-position" id="container-width-scrollable" value="scrollable">
                        <label class="btn btn-sm btn-soft-primary w-sm ms-0" for="container-width-scrollable">Boxed</label>
                    </div>
                </div>
            </div>

            <div class="p-3 border-bottom border-dashed d-none">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fs-16 fw-bold mb-0">Layout Position</h5>

                    <div class="btn-group radio" role="group">
                        <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-fixed" value="fixed">
                        <label class="btn btn-sm btn-soft-primary w-sm" for="layout-position-fixed">Fixed</label>

                        <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-scrollable" value="scrollable">
                        <label class="btn btn-sm btn-soft-primary w-sm ms-0" for="layout-position-scrollable">Scrollable</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 px-3 py-2 offcanvas-header border-top border-dashed">
            <button type="button" class="btn w-100 btn-soft-danger" id="reset-layout">Reset</button>
        </div>

    </div>
    <script>
        $(document).ready(function () {
            $('#checkAll').on('change', function () {
                $('.row-checkbox').prop('checked', $(this).is(':checked'));
            });
        });
        $(document).ready(function () {
            $('.status-toggle').on('change', function () {
                const checkbox = $(this);
                const id = checkbox.data('id');
                const table = checkbox.data('table');
                const column = checkbox.data('column');
                const status = checkbox.is(':checked') ? 1 : 0;

                $.post("<?=BASE_URL('ajax/admin/status');?>", {
                    id: id,
                    table: table,
                    column: column,
                    value: status
                }).done(function (res) {
                    try {
                        const json = JSON.parse(res);
                        if (json.status === 'success') {

                        } else {
                            checkbox.prop('checked', !status);
                        }
                    } catch (e) {
                        checkbox.prop('checked', !status);
                    }
                });
            });
        });
        $(document).on('click', '.btn-delete-single', function () {
            const $btn = $(this);
            const id = $btn.data('id');
            const table = $btn.data('table');
            const column = $btn.data('column-remove') || 'id';

            Swal.fire({
                title: 'Xác nhận',
                text: 'Bạn có chắc chắn muốn xóa mục này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("<?= BASE_URL('ajax/admin/remove'); ?>", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({
                            id: id,
                            table: table,
                            column: column
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Đã xóa', data.msg, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Lỗi', data.msg, 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Lỗi', 'Không thể gửi yêu cầu đến máy chủ.', 'error');
                    });
                }
            });
        });
        $(document).on('click', '.btn-refund', function () {
            
            const $btn = $(this);
            const id = $btn.data('id');
            const table = $btn.data('table');
            const column = $btn.data('column') || 'id';

            console.log("Refund clicked", {id, table, column});
            Swal.fire({
                title: 'Xác nhận',
                text: 'Bạn có chắc chắn muốn hoàn tiền đơn này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("<?= BASE_URL('ajax/admin/refund'); ?>", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({
                            id: id,
                            table: table,
                            column: column
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Đã hoàn tiền', data.msg, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Lỗi', data.msg, 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Lỗi', 'Không thể gửi yêu cầu đến máy chủ.', 'error');
                    });
                }
            });
        });
        const table = document.getElementById('sortable-table');
        Sortable.create(table, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function (evt) {
                const order = [];
                const rows = document.querySelectorAll('#sortable-table tr');

                rows.forEach((row, index) => {
                    order.push({
                        id: row.getAttribute('data-id'),
                        position: index + 1
                    });
                });

                // Lấy table & column từ attr data-* trên hàng đầu tiên
                const firstRow = rows[0];
                const tableName = firstRow.getAttribute('data-table');
                const columnName = firstRow.getAttribute('data-column') || 'sort';

                fetch("<?= BASE_URL('ajax/admin/sort-order') ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        table: tableName,
                        column: columnName,
                        data: order
                    })
                })
                .then(res => res.json())
                .then(json => {
                    if (json.status === 'success') {
                        console.log(json.msg);
                    } else {
                        alert(json.msg);
                    }
                })
                .catch(() => alert('Lỗi gửi dữ liệu sắp xếp'));
            }
        });
        $(function() {
            $('#checkAll').on('change', function() {
                $('.checkbox_product').prop('checked', $(this).prop('checked'));
                updateSelectedRows();
            });

            // Chọn/bỏ chọn hàng khi click vào checkbox
            $(document).on('change', '.checkbox_product', function() {
                updateSelectedRows();
            });

            // Highlight row khi click vào bất kỳ vị trí nào trong hàng (trừ các nút)
            $(document).on('click', '.service-row', function(e) {
                if (!$(e.target).is('a, button, input, i, .handle')) {
                    const $checkbox = $(this).find('.checkbox_product');
                    $checkbox.prop('checked', !$checkbox.prop('checked'));
                    updateSelectedRows();
                }
            });

            function updateSelectedRows() {
                // Highlight các hàng được chọn
                $('.service-row').each(function() {
                    if ($(this).find('.checkbox_product').prop('checked')) {
                        $(this).addClass('selected');
                    } else {
                        $(this).removeClass('selected');
                    }
                });

                // Cập nhật số lượng đã chọn
                var count = $('.checkbox_product:checked').length;

                // Hiển thị/ẩn các nút hành động hàng loạt
                if (count > 0) {
                    $('#bulk-action-buttons').fadeIn(200);
                    $('#selected-counter').text('Đã chọn: ' + count);
                } else {
                    $('#bulk-action-buttons').fadeOut(200);
                    $('#selected-counter').text('');
                }

            }

            // Nút chọn tất cả
            $('#select-all-btn').on('click', function() {
                $('.checkbox_product').prop('checked', true);
                updateSelectedRows();
            });

            // Nút bỏ chọn tất cả
            $('#deselect-all-btn').on('click', function() {
                $('.checkbox_product').prop('checked', false);
                updateSelectedRows();
            });

            // Kích hoạt tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            // Sự kiện click nút Xóa nhanh
            $('#btn_delete_selected').on('click', function () {
                const selectedIds = [];
                let table = null;
                let column = null;

                $('.checkbox_product:checked').each(function () {
                    selectedIds.push($(this).val());

                    // Lấy bảng & cột từ dòng chứa checkbox
                    if (!table || !column) {
                        const $row = $(this).closest('tr');
                        table = $row.data('table');
                        column = $row.data('column-remove') || 'id';
                    }
                });

                if (selectedIds.length === 0) {
                    Swal.fire('Thông báo', 'Vui lòng chọn ít nhất một mục để xóa!', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Xác nhận xóa',
                    text: `Bạn có chắc chắn muốn xóa ${selectedIds.length} mục đã chọn?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa ngay',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let successCount = 0;
                        let failCount = 0;

                        const promises = selectedIds.map((id) => {
                            return fetch("<?= BASE_URL('ajax/admin/remove'); ?>", {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                body: new URLSearchParams({
                                    id: id,
                                    table: table,
                                    column: column
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    successCount++;
                                } else {
                                    failCount++;
                                }
                            })
                            .catch(() => {
                                failCount++;
                            });
                        });

                        Promise.all(promises).then(() => {
                            Swal.fire('Hoàn tất', `Đã xóa ${successCount} sản phẩm. Lỗi: ${failCount}`, 'success')
                                .then(() => location.reload());
                        });
                    }
                });
            });
            
        });
        function deleteSingle(id, table, column = 'id') {
            Swal.fire({
                title: 'Xác nhận',
                text: 'Bạn có chắc chắn muốn xóa mục này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("<?= BASE_URL('ajax/admin/remove.php'); ?>", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({
                            id: id,
                            table: table,
                            column: column,
                            csrf_token: "<?= $_SESSION['csrf_token'] ?? '' ?>"
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Đã xóa', data.msg, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Lỗi', data.msg, 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Lỗi', 'Không thể gửi yêu cầu đến máy chủ.', 'error');
                    });
                }
            });
        }
    </script>
    <script src="/assets/admin/plugins/ckeditor4101/ckeditor.js"></script>
    <script>
    $(document).ready(function () {
        "use strict";

        // Khởi tạo CKEditor nếu tồn tại
        if (document.getElementById("description")) {
            CKEDITOR.replace("description");
        }
        if (document.getElementById("notice_popup")) {
            CKEDITOR.replace("notice_popup");
        }
        if (document.getElementById("phanthuong_popup")) {
            CKEDITOR.replace("phanthuong_popup");
        }
        if (document.getElementById("alert")) {
            CKEDITOR.replace("alert");
        }

        // Basic Datatables
        $("#datatable, #datatable1, #datatable2").DataTable({
            "responsive": false,
            "autoWidth": false,
            language: {
                paginate: {
                    previous: "<i class='ti ti-chevron-left'></i>",
                    next: "<i class='ti ti-chevron-right'></i>"
                }
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            }
        });
    });
    </script>

    <!-- Vendor js -->
    <script src="/assets/admin/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="/assets/admin/js/app.js"></script>

    <!-- Apex Chart js -->
    <script src="/assets/admin/vendor/apexcharts/apexcharts.min.js"></script>

    <!-- Vector Map Js -->
    <script src="/assets/admin/vendor/jsvectormap/jsvectormap.min.js"></script>
    <script src="/assets/admin/vendor/jsvectormap/maps/world-merc.js"></script>
    <script src="/assets/admin/vendor/jsvectormap/maps/world.js"></script>
    
    <!-- Sweet Alerts js -->
    <script src="/assets/admin/vendor/sweetalert2/sweetalert2.min.js"></script>

    <!-- Datatables js -->
    <script src="/assets/admin/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/admin/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="/assets/admin/vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/admin/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="/assets/admin/vendor/datatables.net-fixedcolumns-bs5/js/fixedColumns.bootstrap5.min.js"></script>
    <script src="/assets/admin/vendor/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="/assets/admin/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/assets/admin/vendor/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="/assets/admin/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="/assets/admin/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="/assets/admin/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="/assets/admin/vendor/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="/assets/admin/vendor/datatables.net-select/js/dataTables.select.min.js"></script>

</body>

</html>